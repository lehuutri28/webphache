<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - SECURE REST API: MEDIA UPLOAD ENDPOINT FOR N8N (WF-018)
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Securely receive generated images from n8n (Vertex AI Base64,
 *          public image URL, or multipart upload) and store permanently
 *          in /upload/news/ on phache.com.vn. Eliminates external wecha.vn links.
 * Security: Strict Bearer Token Auth, Fileinfo MIME Validation, Rate Limiting,
 *           Safe SEO Filename Generation, Directory Traversal Protection.
 * ============================================================================
 */

// 1. Initial Headers & Security Directives
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Allow Cross-Origin for webhook / n8n clients
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    http_response_code(204);
    exit;
}

// Enforce POST method only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Method Not Allowed. Only POST is accepted.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Bootstrap Core Environment & Load Configs
define('PHACHE_API_GATEWAY', true);
$docRoot = dirname(__DIR__) . DIRECTORY_SEPARATOR;

if (!defined('DOCROOT')) {
    define('DOCROOT', $docRoot);
}
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', DOCROOT . 'upload' . DIRECTORY_SEPARATOR);
}
if (!defined('NEWS_DIR')) {
    define('NEWS_DIR', UPLOAD_DIR . 'news' . DIRECTORY_SEPARATOR);
}

// Load Secure API Secret Token
$secretConfigFile = DOCROOT . 'config' . DIRECTORY_SEPARATOR . 'api_secret.php';
if (!file_exists($secretConfigFile)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Server Configuration Error: API secret configuration missing.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
require_once $secretConfigFile;

if (!defined('PHACHE_API_SECRET_TOKEN') || empty(PHACHE_API_SECRET_TOKEN)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Server Configuration Error: Invalid API secret token definition.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Polyfill hash_equals for PHP 5.6
if (!function_exists('hash_equals')) {
    function hash_equals($known_string, $user_string) {
        if (!is_string($known_string) || !is_string($user_string)) return false;
        $len = strlen($known_string);
        if ($len !== strlen($user_string)) return false;
        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= ord($known_string[$i]) ^ ord($user_string[$i]);
        }
        return $status === 0;
    }
}

function pl_upload_random_hex($length = 6) {
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes(ceil($length / 2)));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes(ceil($length / 2)));
    }
    return substr(md5(uniqid(mt_rand(), true)), 0, $length);
}

// 3. Authentication: Timing-Safe Bearer Token Validation
$authHeader = '';
if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
} elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $authHeader = trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
} elseif (function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $authHeader = trim($headers['Authorization']);
    } elseif (isset($headers['authorization'])) {
        $authHeader = trim($headers['authorization']);
    }
}

$receivedToken = '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    $receivedToken = trim($matches[1]);
}

if (empty($receivedToken) || !hash_equals(PHACHE_API_SECRET_TOKEN, $receivedToken)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Unauthorized: Invalid or missing Bearer API token.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 4. Rate Limiting Protection (Max 60 uploads per minute)
$rawIp = '127.0.0.1';
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $rawIp = $_SERVER['HTTP_CF_CONNECTING_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $rawIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $rawIp = $_SERVER['REMOTE_ADDR'];
}
$clientIp = filter_var(trim($rawIp), FILTER_VALIDATE_IP) ? trim($rawIp) : '127.0.0.1';

$cacheDir = UPLOAD_DIR . 'cache' . DIRECTORY_SEPARATOR;
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0755, true);
}
$rateLimitFile = $cacheDir . 'ratelimit_upload_' . md5($clientIp) . '.json';
$currentTime = time();
$rateLimitWindow = 60;
$maxRequests = 60; // 60 uploads per min

$rateData = ['count' => 0, 'first_request' => $currentTime];
if (file_exists($rateLimitFile)) {
    $existing = json_decode(@file_get_contents($rateLimitFile), true);
    if (is_array($existing) && isset($existing['first_request'])) {
        if (($currentTime - $existing['first_request']) < $rateLimitWindow) {
            $rateData = $existing;
        }
    }
}
$rateData['count']++;
@file_put_contents($rateLimitFile, json_encode($rateData));

if ($rateData['count'] > $maxRequests) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'error'   => 'Too Many Requests: Upload rate limit exceeded (max 60/min).'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 5. Slugify helper for SEO-friendly filename
function pl_upload_slugify($str) {
    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    ];
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9\-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

// 6. Ingest Image Payload: Base64, Image URL, or Multipart
$imgData = '';
$requestedName = '';
$caption = '';
$folder = 'news'; // default destination: /upload/news/

// A. Check for Multipart file upload ($_FILES)
if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
    $imgData = @file_get_contents($_FILES['file']['tmp_name']);
    $requestedName = !empty($_POST['filename']) ? $_POST['filename'] : $_FILES['file']['name'];
    if (!empty($_POST['caption'])) $caption = trim($_POST['caption']);
    if (!empty($_POST['folder'])) $folder = trim($_POST['folder']);
} elseif (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $imgData = @file_get_contents($_FILES['image']['tmp_name']);
    $requestedName = !empty($_POST['filename']) ? $_POST['filename'] : $_FILES['image']['name'];
    if (!empty($_POST['caption'])) $caption = trim($_POST['caption']);
    if (!empty($_POST['folder'])) $folder = trim($_POST['folder']);
} else {
    // B. Check for JSON Payload (Vertex AI Base64 or Image URL)
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true);

    if (is_array($payload)) {
        if (!empty($payload['filename'])) $requestedName = $payload['filename'];
        if (!empty($payload['caption'])) $caption = $payload['caption'];
        if (!empty($payload['alt'])) $caption = $payload['alt'];
        if (!empty($payload['folder'])) $folder = $payload['folder'];

        // 1. Process Base64 image (Standard output from Vertex AI / Gemini / Imagen)
        if (!empty($payload['image_base64'])) {
            $base64Str = $payload['image_base64'];
            // Strip data:image/...;base64, header if present
            if (preg_match('/^data:image\/(\w+);base64,/i', $base64Str, $match)) {
                $base64Str = substr($base64Str, strpos($base64Str, ',') + 1);
            }
            $imgData = base64_decode($base64Str, true);
        } elseif (!empty($payload['image_url']) && filter_var($payload['image_url'], FILTER_VALIDATE_URL)) {
            // 2. Process Image URL download
            $url = $payload['image_url'];
            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                $imgData = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($httpCode !== 200) $imgData = '';
            }
            if (empty($imgData)) {
                $ctx = stream_context_create([
                    'http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'],
                    'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
                ]);
                $imgData = @file_get_contents($url, false, $ctx);
            }
        }
    }
}

// 7. Validate Decoded Image Data
if (empty($imgData)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Bad Request: No valid image provided. Expecting "image_base64", "image_url", or multipart file.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$fileSize = strlen($imgData);
$maxSizeBytes = 12 * 1024 * 1024; // 12MB max
if ($fileSize > $maxSizeBytes) {
    http_response_code(413);
    echo json_encode([
        'success' => false,
        'error'   => 'Payload Too Large: Image exceeds 12MB limit.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 8. Strict MIME Type Validation via Magic Bytes
$mimeType = '';
$imgWidth = 0;
$imgHeight = 0;

if (function_exists('getimagesizefromstring')) {
    $imgInfo = @getimagesizefromstring($imgData);
    if ($imgInfo !== false && !empty($imgInfo['mime'])) {
        $mimeType = strtolower($imgInfo['mime']);
        $imgWidth = isset($imgInfo[0]) ? (int)$imgInfo[0] : 0;
        $imgHeight = isset($imgInfo[1]) ? (int)$imgInfo[1] : 0;
    }
}

if (empty($mimeType) && function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $rawMime = finfo_buffer($finfo, $imgData);
    finfo_close($finfo);
    if (!empty($rawMime)) {
        $mimeType = strtolower(trim(explode(';', $rawMime)[0]));
    }
}

$allowedMimes = [
    'image/jpeg' => 'jpg',
    'image/pjpeg'=> 'jpg',
    'image/jpg'  => 'jpg',
    'image/png'  => 'png',
    'image/x-png'=> 'png',
    'image/webp' => 'webp'
];

if (!isset($allowedMimes[$mimeType])) {
    http_response_code(415);
    echo json_encode([
        'success' => false,
        'error'   => 'Unsupported Media Type: Only JPEG, PNG, and WebP images are allowed. Detected: ' . ($mimeType ? $mimeType : 'Unknown')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$ext = $allowedMimes[$mimeType];

// 9. Destination Directory & Safe SEO Filename Generation
$validFolders = [
    'news'   => UPLOAD_DIR . 'news' . DIRECTORY_SEPARATOR,
    'images' => UPLOAD_DIR . 'images' . DIRECTORY_SEPARATOR
];
$targetFolderKey = isset($validFolders[$folder]) ? $folder : 'news';
$targetDir = $validFolders[$targetFolderKey];

if (!is_dir($targetDir)) {
    @mkdir($targetDir, 0755, true);
}

// Generate clean SEO name
$cleanSlug = '';
if (!empty($requestedName)) {
    $pathInfoName = pathinfo($requestedName, PATHINFO_FILENAME);
    $cleanSlug = pl_upload_slugify($pathInfoName);
}
if (empty($cleanSlug) && !empty($caption)) {
    $cleanSlug = pl_upload_slugify($caption);
}
if (empty($cleanSlug)) {
    $cleanSlug = 'do-uong-passionlink';
}
$cleanSlug = mb_substr($cleanSlug, 0, 45, 'UTF-8');

$fileName = 'pl_' . $cleanSlug . '_' . time() . '_' . pl_upload_random_hex(6) . '.' . $ext;
$savePath = $targetDir . $fileName;

// 10. Write File to Storage Permanently
if (@file_put_contents($savePath, $imgData) === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Server Error: Failed to write image to storage.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
@chmod($savePath, 0644);

// 11. Build Response URLs & Semantic HTML Figure Block
$publicUrl = "https://phache.com.vn/upload/{$targetFolderKey}/" . $fileName;
$thumbBase64 = base64_encode($publicUrl);
$thumbUrl = "https://phache.com.vn/index.php?t=ajax&p=tthumb&src={$thumbBase64}&w=800&h=533";

$displayCaption = !empty($caption) ? htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') : htmlspecialchars(ucwords(str_replace(['-', '_'], ' ', $cleanSlug)), ENT_QUOTES, 'UTF-8');
$htmlFigure = "<figure class=\"pl-article-figure\" style=\"margin:24px auto;text-align:center;max-width:100%;\">\n" .
              "  <img src=\"{$publicUrl}\" alt=\"{$displayCaption}\" loading=\"lazy\" decoding=\"async\" style=\"border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.08);max-width:100%;height:auto;\" />\n" .
              "  <figcaption style=\"font-size:14px;color:#64748b;font-style:italic;margin-top:8px;\">{$displayCaption}</figcaption>\n" .
              "</figure>";

// 12. Return HTTP 201 Created with JSON
http_response_code(201);
echo json_encode([
    'success' => true,
    'message' => 'Upload hình ảnh thành công lên phache.com.vn!',
    'data'    => [
        'filename'   => $fileName,
        'folder'     => $targetFolderKey,
        'url'        => $publicUrl,
        'thumb_url'  => $thumbUrl,
        'dimensions' => "{$imgWidth}x{$imgHeight}",
        'width'      => $imgWidth,
        'height'     => $imgHeight,
        'size_kb'    => round($fileSize / 1024, 1),
        'mime_type'  => $mimeType,
        'caption'    => $caption,
        'html_tag'   => $htmlFigure
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
