<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - SECURE REST API: MEDIA LIBRARY SEARCH & REFERENCE
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Securely provide authentic Passion Link drink photos for Agent C11
 *          to use as reference images (image-to-image AI) or featured images.
 * Security: Strict Bearer Token Auth, Path Traversal Defense, Rate Limiting.
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
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    http_response_code(204);
    exit;
}

// Accept GET or POST
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Method Not Allowed. Only GET or POST is accepted.'
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

// 4. Rate Limiting Protection (Max 30 requests per minute)
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
$rateLimitFile = $cacheDir . 'ratelimit_media_' . md5($clientIp) . '.json';
$currentTime = time();
$rateLimitWindow = 60;
$maxRequests = 30;

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
        'error'   => 'Too Many Requests: Rate limit exceeded for media library (max 30/min).'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 5. Parse Search Parameters (GET or POST JSON)
$keyword = '';
$category = 'all';
$limit = 12;
$random = false;

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $postData = json_decode($rawInput, true);
    if (is_array($postData)) {
        if (isset($postData['keyword'])) $keyword = trim($postData['keyword']);
        if (isset($postData['category'])) $category = trim($postData['category']);
        if (isset($postData['limit'])) $limit = intval($postData['limit']);
        if (isset($postData['random'])) $random = (bool)$postData['random'];
    }
}

// Fallback to GET query params
if (empty($keyword) && isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}
if (isset($_GET['category'])) {
    $category = trim($_GET['category']);
}
if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['random'])) {
    $random = in_array(strtolower($_GET['random']), ['1', 'true', 'yes']);
}

// Clamp limit
if ($limit <= 0) $limit = 12;
if ($limit > 50) $limit = 50;

// 6. Helper: Vietnamese unaccent & slug normalizer
function pl_clean_search_token($str) {
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
    $str = preg_replace('/[^a-z0-9]/', '', $str);
    return $str;
}

// Split keyword into normalized tokens
$searchTokens = [];
if (!empty($keyword)) {
    $rawTokens = preg_split('/[\s\-_,]+/', $keyword);
    foreach ($rawTokens as $rt) {
        $clean = pl_clean_search_token($rt);
        if (strlen($clean) >= 2) {
            $searchTokens[] = $clean;
        }
    }
}

// 7. Search Directories: /upload/images/, /upload/news/, /upload/gallery/
$targetFolders = [
    'images'  => UPLOAD_DIR . 'images' . DIRECTORY_SEPARATOR,
    'news'    => UPLOAD_DIR . 'news' . DIRECTORY_SEPARATOR,
    'gallery' => UPLOAD_DIR . 'gallery' . DIRECTORY_SEPARATOR
];

$validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
$candidates = [];

foreach ($targetFolders as $folderKey => $folderPath) {
    if (!is_dir($folderPath)) continue;

    $files = @scandir($folderPath);
    if (!$files) continue;

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || substr($file, 0, 1) === '.') continue;
        
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $validExtensions)) continue;

        // Skip small icons or banners
        if (stripos($file, 'icon') !== false || stripos($file, 'logo') !== false || stripos($file, 'banner') !== false) {
            continue;
        }

        // Clean filename for matching
        $baseName = pathinfo($file, PATHINFO_FILENAME);
        $cleanFileName = pl_clean_search_token($baseName);

        // Matching logic
        $score = 0;
        if (empty($searchTokens)) {
            // General drink match
            $drinkKeywords = ['tra', 'sua', 'tea', 'cafe', 'coffee', 'dao', 'chanh', 'kem', 'matcha', 'latte', 'topping', 'boba', 'cocktail', 'che', 'sinhto', 'nuocep', 'olong'];
            foreach ($drinkKeywords as $dk) {
                if (strpos($cleanFileName, $dk) !== false) {
                    $score += 1;
                }
            }
        } else {
            // Specific search token match
            foreach ($searchTokens as $token) {
                if (strpos($cleanFileName, $token) !== false) {
                    $score += 2;
                }
            }
        }

        if ($score > 0) {
            $candidates[] = [
                'filename'   => $file,
                'clean_name' => ucwords(str_replace(['-', '_'], ' ', $baseName)),
                'folder'     => $folderKey,
                'path'       => $folderPath . $file,
                'score'      => $score
            ];
        }
    }
}

// 8. Sort Candidates by relevance or shuffle if random
if ($random) {
    shuffle($candidates);
} else {
    usort($candidates, function($a, $b) {
        if ($a['score'] === $b['score']) {
            return strcmp($a['filename'], $b['filename']);
        }
        return ($a['score'] > $b['score']) ? -1 : 1;
    });
}

// 9. Format Results
$results = [];
$count = 0;

foreach ($candidates as $item) {
    if ($count >= $limit) break;

    $publicUrl = "https://phache.com.vn/upload/{$item['folder']}/" . rawurlencode($item['filename']);
    $thumbBase64 = base64_encode($publicUrl);
    $thumbUrl = "https://phache.com.vn/index.php?t=ajax&p=tthumb&src={$thumbBase64}&w=600&h=400";

    $fileSize = @filesize($item['path']);
    $imgSize = @getimagesize($item['path']);

    $results[] = [
        'id'          => $count + 1,
        'title'       => $item['clean_name'],
        'filename'    => $item['filename'],
        'folder'      => $item['folder'],
        'url'         => $publicUrl,
        'thumb_url'   => $thumbUrl,
        'dimensions'  => ($imgSize && isset($imgSize[0])) ? "{$imgSize[0]}x{$imgSize[1]}" : "unknown",
        'width'       => ($imgSize && isset($imgSize[0])) ? (int)$imgSize[0] : 0,
        'height'      => ($imgSize && isset($imgSize[1])) ? (int)$imgSize[1] : 0,
        'size_kb'     => $fileSize ? round($fileSize / 1024, 1) : 0,
        'relevance'   => $item['score']
    ];
    $count++;
}

// 10. Return JSON Response
http_response_code(200);
echo json_encode([
    'success'      => true,
    'query'        => [
        'keyword'  => $keyword,
        'category' => $category,
        'limit'    => $limit,
        'random'   => $random
    ],
    'total_found'  => count($candidates),
    'returned'     => count($results),
    'data'         => $results
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
