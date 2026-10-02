<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - SECURE REST API ENDPOINT: AUTOMATED NEWS PUBLISHING
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Securely receive and ingest SEO-optimized articles from n8n / automation
 * Compliance: Strict Bearer Token Auth, Rate Limiting, SQL Injection Immunity,
 *             Anti-XSS, Dynamic FAQPage JSON-LD, On-Page SEO Heading Normalization.
 * ============================================================================
 */

// 1. Initial Headers & Security Directives
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Allow Cross-Origin for webhook clients if necessary
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

// Polyfills and helpers for PHP 5.6+ compatibility
if (!function_exists('hash_equals')) {
    function hash_equals($known_string, $user_string) {
        if (!is_string($known_string) || !is_string($user_string)) {
            return false;
        }
        $len = strlen($known_string);
        if ($len !== strlen($user_string)) {
            return false;
        }
        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= ord($known_string[$i]) ^ ord($user_string[$i]);
        }
        return $status === 0;
    }
}

function pl_random_hex($length = 6) {
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes(ceil($length / 2)));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes(ceil($length / 2)));
    }
    return substr(md5(uniqid(mt_rand(), true)), 0, $length);
}

// 3. Authentication & Usage Telemetry: Centralized API Key Gateway
require_once __DIR__ . DIRECTORY_SEPARATOR . 'auth-guard.php';
$authenticatedKey = pl_verify_api_key_and_track('publish');

// 5. Connect Database via existing system configuration
$dbConfigFile = DOCROOT . 'config' . DIRECTORY_SEPARATOR . 'db.php';
if (!file_exists($dbConfigFile)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database connection configuration file not found.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
require_once $dbConfigFile;

global $obMySQLi;
if (!$obMySQLi || $obMySQLi->connect_errno) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database connection error: ' . ($obMySQLi ? $obMySQLi->connect_error : 'MySQLi initialization failed')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
$obMySQLi->set_charset("utf8mb4");

// Helper: Detect placeholder/dummy description strings
function pl_is_dummy_description($str) {
    if (empty($str)) {
        return true;
    }
    $trimmed = trim(strip_tags($str));
    if (mb_strlen($trimmed, 'UTF-8') < 20) {
        return true;
    }
    $dummyRegex = '/^(?:t\x{00F3}m t\x{1EAF}t b\x{00E0}i vi\x{1EBF}t|t\x{00F3}m t\x{1EAF}t|m\x{00F4} t\x{1EA3} b\x{00E0}i vi\x{1EBF}t|m\x{00F4} t\x{1EA3}|description|summary|excerpt|ch\x{01B0}a c\x{00F3} m\x{00F4} t\x{1EA3}|n\/a|none|ch\x{01B0}a c\x{00F3}|ti\x{00EA}u \x{0111}\x{1EC1}|b\x{00E0}i vi\x{1EBF}t)\b/iu';
    if (preg_match($dummyRegex, $trimmed)) {
        return true;
    }
    return false;
}

// Helper: Generate clean, rich summary excerpt from article HTML content
function pl_generate_clean_excerpt($html, $maxLength = 155) {
    if (empty($html)) {
        return '';
    }
    // Remove scripts, styles, iframes, embeds, videos, figures, tables, headings, and special modules
    $cleaned = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    $cleaned = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleaned);
    $cleaned = preg_replace('/<video\b[^>]*>(.*?)<\/video>/is', '', $cleaned);
    $cleaned = preg_replace('/<figure\b[^>]*>(.*?)<\/figure>/is', '', $cleaned);
    $cleaned = preg_replace('/<table\b[^>]*>(.*?)<\/table>/is', '', $cleaned);
    $cleaned = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $cleaned);
    $cleaned = preg_replace('/<h[1-6]\b[^>]*>(.*?)<\/h[1-6]>/is', '', $cleaned);
    $cleaned = preg_replace('/<div class="[^"]*(?:video|author|cta|retention|meta)[^"]*"[^>]*>(.*?)<\/div>/is', '', $cleaned);
    
    // Strip tags and decode entities
    $text = strip_tags($cleaned);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    // Normalize spaces and newlines
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    
    if (empty($text)) {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    }
    
    if (mb_strlen($text, 'UTF-8') <= $maxLength) {
        return $text;
    }
    
    // Trim to last word boundary
    $cut = mb_substr($text, 0, $maxLength, 'UTF-8');
    $lastSpace = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($lastSpace !== false && $lastSpace > ($maxLength - 25)) {
        $cut = mb_substr($cut, 0, $lastSpace, 'UTF-8');
    }
    return rtrim($cut, " \t\n\r\0\x0B.,;:-") . '...';
}

// 6. Parse and Validate Ingestion Payload
$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);

if (!is_array($payload) || empty($payload)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Bad Request: Missing or invalid JSON request body.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Special maintenance actions (authenticated via Bearer token)
if (isset($payload['action']) && in_array($payload['action'], array('heal_descriptions', 'inspect_descriptions'))) {
    $isInspectOnly = ($payload['action'] === 'inspect_descriptions');
    $overrides = (isset($payload['overrides']) && is_array($payload['overrides'])) ? $payload['overrides'] : array();

    $res = $obMySQLi->query("SELECT news_id, news_title, news_description, news_content FROM `news` ORDER BY news_id DESC LIMIT 100");
    $items = array();
    $updateStmt = null;
    if (!$isInspectOnly) {
        $updateStmt = $obMySQLi->prepare("UPDATE `news` SET `news_description` = ?, `news_date_modified` = ? WHERE `news_id` = ?");
    }

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $nid = intval($row['news_id']);
            $currentDesc = str_replace(array('&#34;', '&#39;'), array('"', "'"), $row['news_description']);
            $titleDecoded = str_replace(array('&#34;', '&#39;'), array('"', "'"), $row['news_title']);
            $isDummy = pl_is_dummy_description($currentDesc);
            $hasOverride = isset($overrides[$nid]) || isset($overrides[(string)$nid]);

            if ($isDummy || $hasOverride) {
                if ($hasOverride) {
                    $newDesc = trim(strip_tags(isset($overrides[$nid]) ? $overrides[$nid] : $overrides[(string)$nid]));
                } else {
                    $rawHtml = str_replace(array('&#34;', '&#39;'), array('"', "'"), $row['news_content']);
                    $newDesc = pl_generate_clean_excerpt($rawHtml, 155);
                    if (empty($newDesc)) {
                        $newDesc = 'Khám phá bí quyết pha chế và kinh nghiệm mở quán thực chiến tại Passion Link.';
                    }
                }

                if (!$isInspectOnly && $updateStmt) {
                    $encodedNewDesc = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $newDesc));
                    $nowTs = time();
                    $updateStmt->bind_param('sii', $encodedNewDesc, $nowTs, $nid);
                    $updateStmt->execute();
                }

                $items[] = array(
                    'news_id' => $nid,
                    'title' => $titleDecoded,
                    'old_description' => $currentDesc,
                    'new_description' => $newDesc,
                    'updated' => !$isInspectOnly
                );
            }
        }
        if ($updateStmt) {
            $updateStmt->close();
        }
    }

    echo json_encode(array(
        'success' => true,
        'action' => $payload['action'],
        'affected_count' => count($items),
        'items' => $items
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// Required fields validation
$title = isset($payload['title']) ? trim(strip_tags($payload['title'])) : '';
$rawContent = isset($payload['content_html']) ? $payload['content_html'] : (isset($payload['content']) ? $payload['content'] : '');

if (empty($title) || mb_strlen($title, 'UTF-8') < 5) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'error'   => 'Unprocessable Entity: "title" is required and must be at least 5 characters long.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($rawContent) || mb_strlen(trim(strip_tags($rawContent)), 'UTF-8') < 50) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'error'   => 'Unprocessable Entity: "content_html" is required and must contain substantial body content (min 50 chars).'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Category validation & mapping
$categoryMap = [
    31 => 'tin-tuc',
    25 => 'mo-quan',
    24 => 'day-pha-che-tra-sua-ngon',
    22 => 'cac-khoa-hoc-day-pha-che'
];
$categoryId = isset($payload['category_id']) ? intval($payload['category_id']) : 31;
if (!isset($categoryMap[$categoryId])) {
    $categoryId = 31;
}
$categorySlug = $categoryMap[$categoryId];

// 7. SEO Helper Functions
function pl_vietnamese_slugify($str) {
    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    ];
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9\-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

$slug = !empty($payload['slug']) ? pl_vietnamese_slugify($payload['slug']) : pl_vietnamese_slugify($title);

// Meta description (Multi-alias support & Anti-dummy protection)
$description = '';
$descAliases = array('description', 'summary', 'excerpt', 'short_description', 'meta_description', 'tom_tat', 'mo_ta');
foreach ($descAliases as $alias) {
    if (!empty($payload[$alias]) && is_string($payload[$alias])) {
        $candidate = trim(strip_tags($payload[$alias]));
        if (!pl_is_dummy_description($candidate)) {
            $description = $candidate;
            break;
        }
    }
}

// Fallback to intelligent excerpt generation if candidate is empty or dummy
if (empty($description)) {
    $description = pl_generate_clean_excerpt($rawContent, 155);
}

// Normalize length (keep within 140 - 165 chars for optimal SEO)
if (mb_strlen($description, 'UTF-8') > 165) {
    $cut = mb_substr($description, 0, 160, 'UTF-8');
    $lastSpace = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($lastSpace !== false && $lastSpace > 130) {
        $cut = mb_substr($cut, 0, $lastSpace, 'UTF-8');
    }
    $description = rtrim($cut, " \t\n\r\0\x0B.,;:-") . '...';
}

// Meta keywords
$keywords = !empty($payload['keywords']) ? trim(strip_tags($payload['keywords'])) : '';
if (empty($keywords)) {
    $keywords = strtolower($title) . ', phache.com.vn, passion link, học pha chế, mở quán';
}

// 8. Content Sanitization & Advanced On-Page SEO Engine
// A. Remove any malicious script / iframe / embed tags
$cleanContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $rawContent);
$cleanContent = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $cleanContent);
$cleanContent = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', '', $cleanContent);
$cleanContent = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $cleanContent);
$cleanContent = preg_replace('/on\w+="[^"]*"/i', '', $cleanContent);
$cleanContent = preg_replace('/on\w+=\'[^\']*\'/i', '', $cleanContent);

// B. Strict Single-H1 Rule: Convert any internal <h1> tags to <h2>
$cleanContent = preg_replace('/<h1\b([^>]*)>(.*?)<\/h1>/is', '<h2$1>$2</h2>', $cleanContent);

// C. Optimize all <img> tags inside content for Core Web Vitals (Lazy loading, alt, async)
$cleanContent = preg_replace_callback('/<img\b([^>]*)>/i', function($matches) use ($title) {
    $attrs = $matches[1];
    if (stripos($attrs, 'loading=') === false) {
        $attrs .= ' loading="lazy"';
    }
    if (stripos($attrs, 'decoding=') === false) {
        $attrs .= ' decoding="async"';
    }
    if (stripos($attrs, 'alt=') === false || preg_match('/alt=[\'"]\s*[\'"]/', $attrs)) {
        $attrs .= ' alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '"';
    }
    return '<img' . $attrs . '>';
}, $cleanContent);

// D. Auto-Rehost External Images (Chuyển toàn bộ ảnh wecha.vn về lưu trữ máy chủ phache.com.vn)
if (stripos($cleanContent, 'wecha.vn') !== false) {
    $cleanContent = preg_replace_callback('/src=([\'"])(https?:\/\/[^\'"]*wecha\.vn[^\'"]+)\1/i', function($m) use ($slug) {
        $extUrl = $m[2];
        $imgBytes = '';
        if (function_exists('curl_init')) {
            $ch = curl_init($extUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            $imgBytes = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode !== 200) $imgBytes = '';
        }
        if (empty($imgBytes)) {
            $imgBytes = @file_get_contents($extUrl);
        }
        if (!empty($imgBytes) && strlen($imgBytes) <= (10 * 1024 * 1024)) {
            $allowedExts = ['jpg' => 'jpg', 'jpeg' => 'jpg', 'png' => 'png', 'webp' => 'webp'];
            $fileExt = strtolower(pathinfo(parse_url($extUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            if (!isset($allowedExts[$fileExt])) $fileExt = 'jpg';
            $localName = 'pl_rehost_' . mb_substr($slug, 0, 30, 'UTF-8') . '_' . time() . '_' . pl_random_hex(4) . '.' . $fileExt;
            $savePath = DOCROOT . 'upload' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR . $localName;
            if (@file_put_contents($savePath, $imgBytes) !== false) {
                @chmod($savePath, 0644);
                return 'src=' . $m[1] . 'https://phache.com.vn/upload/news/' . $localName . $m[1];
            }
        }
        return $m[0];
    }, $cleanContent);
}

// E. Table Mobile Responsiveness: Wrap <table> with .table-responsive
if (strpos($cleanContent, '<table') !== false && strpos($cleanContent, 'table-responsive') === false) {
    $cleanContent = preg_replace('/(<table\b[^>]*>.*?<\/table>)/is', '<div class="table-responsive" style="overflow-x:auto;margin:20px 0;">$1</div>', $cleanContent);
}

// E. Process FAQs & Inject Schema.org FAQPage JSON-LD + Semantic HTML Accordion
$faqs = isset($payload['faqs']) && is_array($payload['faqs']) ? $payload['faqs'] : [];
if (!empty($faqs)) {
    $faqHtml = "\n\n<!-- SEO FAQ SECTION (AUTO-GENERATED BY C12 SEO ENGINE) -->\n";
    $faqHtml .= '<div class="pl-seo-faq-container" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin:32px 0;">' . "\n";
    $faqHtml .= '  <h3 style="color:#0f172a;font-size:22px;font-weight:700;margin-top:0;margin-bottom:18px;display:flex;align-items:center;gap:8px;">❓ Câu Hỏi Thường Gặp (FAQ)</h3>' . "\n";
    $faqHtml .= '  <div class="pl-faq-list" style="display:flex;flex-direction:column;gap:14px;">' . "\n";

    $faqSchemaItems = [];

    foreach ($faqs as $faq) {
        $q = isset($faq['q']) ? trim(strip_tags($faq['q'])) : '';
        $a = isset($faq['a']) ? trim(strip_tags($faq['a'])) : '';
        if (empty($q) || empty($a)) continue;

        $faqHtml .= '    <div class="pl-faq-item" style="background:#ffffff;border:1px solid #cbd5e1;border-radius:8px;padding:16px;">' . "\n";
        $faqHtml .= '      <h4 style="color:#1e293b;font-size:16px;font-weight:700;margin:0 0 8px 0;">' . htmlspecialchars($q, ENT_QUOTES, 'UTF-8') . '</h4>' . "\n";
        $faqHtml .= '      <p style="color:#475569;font-size:14px;line-height:1.6;margin:0;">' . htmlspecialchars($a, ENT_QUOTES, 'UTF-8') . '</p>' . "\n";
        $faqHtml .= '    </div>' . "\n";

        $faqSchemaItems[] = [
            '@type' => 'Question',
            'name'  => $q,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $a
            ]
        ];
    }
    $faqHtml .= '  </div>' . "\n";
    $faqHtml .= '</div>' . "\n";

    if (!empty($faqSchemaItems)) {
        $schemaData = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faqSchemaItems
        ];
        $faqHtml .= '<script type="application/ld+json">' . json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }

    $cleanContent .= $faqHtml;
}

// F. Conversion CTA Box (Passion Link Consultation Hook)
if (stripos($cleanContent, '0977.300.098') === false && stripos($cleanContent, '0977300098') === false) {
    $ctaBox = "\n\n<!-- PASSION LINK HIGH-CONVERSION CTA BLOCK -->\n";
    $ctaBox .= '<div class="pl-inarticle-cta" style="background:linear-gradient(135deg, #059669 0%, #0d9488 100%);color:#ffffff;border-radius:14px;padding:26px;margin:35px 0;text-align:center;box-shadow:0 10px 25px -5px rgba(5,150,105,0.25);">' . "\n";
    $ctaBox .= '  <span style="display:inline-block;background:rgba(255,255,255,0.2);padding:4px 14px;border-radius:30px;font-size:13px;font-weight:700;letter-spacing:0.5px;margin-bottom:10px;">🎓 TRUNG TÂM ĐÀO TẠO PHA CHẾ PASSION LINK</span>' . "\n";
    $ctaBox .= '  <h3 style="color:#ffffff;font-size:22px;font-weight:800;margin:6px 0 12px 0;">Bí Quyết Làm Chủ Quán Đồ Uống Lãi Chuẩn 70%</h3>' . "\n";
    $ctaBox .= '  <p style="color:#ecfdf5;font-size:15px;line-height:1.6;max-width:680px;margin:0 auto 18px auto;">Nhận ngay trọn bộ giáo trình công thức độc quyền và bảng tính giá vốn (Cost) chi tiết từ chuyên gia Passion Link.</p>' . "\n";
    $ctaBox .= '  <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">' . "\n";
    $ctaBox .= '    <a href="https://zalo.me/0977300098" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;background:#fbbf24;color:#78350f;font-weight:800;padding:12px 24px;border-radius:30px;text-decoration:none;font-size:15px;box-shadow:0 4px 12px rgba(251,191,36,0.35);">💬 Nhận Tư Vấn Zalo: 0977.300.098</a>' . "\n";
    $ctaBox .= '    <a href="https://phache.com.vn/khoa-tong-hop/" target="_blank" style="display:inline-flex;align-items:center;background:rgba(255,255,255,0.15);color:#ffffff;border:1px solid rgba(255,255,255,0.4);font-weight:700;padding:12px 20px;border-radius:30px;text-decoration:none;font-size:15px;">📋 Xem Khóa Học Tổng Hợp</a>' . "\n";
    $ctaBox .= '  </div>' . "\n";
    $ctaBox .= '</div>' . "\n";

    $cleanContent .= $ctaBox;
}

// 9. Featured Image Download & Processing
$featuredImage = '';
$imageTitle = !empty($payload['image_alt']) ? trim(strip_tags($payload['image_alt'])) : $title;

if (!empty($payload['image_url']) && filter_var($payload['image_url'], FILTER_VALIDATE_URL)) {
    $imageUrl = $payload['image_url'];
    $imgData = '';

    // A. Fetch Image Bytes with cURL + stream fallback
    if (function_exists('curl_init')) {
        $ch = curl_init($imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        $imgData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200 || empty($imgData)) {
            $imgData = '';
        }
    }

    if (empty($imgData)) {
        $ctx = stream_context_create([
            'http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        $imgData = @file_get_contents($imageUrl, false, $ctx);
    }

    if (!empty($imgData) && strlen($imgData) <= (8 * 1024 * 1024)) {
        // B. Robust MIME Type Detection (getimagesizefromstring + finfo fallback)
        $mimeType = '';
        if (function_exists('getimagesizefromstring')) {
            $imgInfo = @getimagesizefromstring($imgData);
            if ($imgInfo !== false && !empty($imgInfo['mime'])) {
                $mimeType = strtolower($imgInfo['mime']);
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

        if (isset($allowedMimes[$mimeType])) {
            $ext = $allowedMimes[$mimeType];
            $targetDir = DOCROOT . 'upload' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR;
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }
            $cleanImgSlug = mb_substr($slug, 0, 40, 'UTF-8');
            $imgFileName = 'pl_' . $cleanImgSlug . '_' . time() . '_' . pl_random_hex(6) . '.' . $ext;
            $savePath = $targetDir . $imgFileName;

            if (@file_put_contents($savePath, $imgData) !== false) {
                @chmod($savePath, 0644);
                $featuredImage = $imgFileName;
            }
        }
    }
}

// 10. Database Insertion via MySQLi Prepared Statement
$now = time();
$viewCount = 0;
$emptyStr = '';
// Auto-calculate news_order so the newest published article appears at the top of category listing
$currentMaxOrder = 1;
$maxOrderRes = $obMySQLi->query("SELECT MAX(news_order) AS max_order FROM `news` WHERE page_id = " . intval($categoryId));
if ($maxOrderRes && $row = $maxOrderRes->fetch_assoc()) {
    $currentMaxOrder = intval($row['max_order']);
}
$orderVal = !empty($payload['order']) ? intval($payload['order']) : ($currentMaxOrder + 1);

// Matching legacy quotes format:
$encodedTitle = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $title));
$encodedContent = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $cleanContent));
$encodedDescription = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $description));
$encodedKeywords = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $keywords));
$encodedImageTitle = trim(str_replace(array('"', "'"), array('&#34;', '&#39;'), $imageTitle));

$insertSql = "INSERT INTO `news` (
    `page_id`,
    `news_title`,
    `news_description`,
    `news_keyword`,
    `news_content`,
    `news_image`,
    `news_image_title`,
    `news_date_created`,
    `news_date_modified`,
    `news_view`,
    `news_gallery`,
    `news_content_more`,
    `news_gallery_more`,
    `news_order`
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $obMySQLi->prepare($insertSql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database Prepare Error: ' . $obMySQLi->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param(
    'issssssiiisssi',
    $categoryId,
    $encodedTitle,
    $encodedDescription,
    $encodedKeywords,
    $encodedContent,
    $featuredImage,
    $encodedImageTitle,
    $now,
    $now,
    $viewCount,
    $emptyStr,
    $emptyStr,
    $emptyStr,
    $orderVal
);

if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database Insertion Error: ' . $err
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$newArticleId = $stmt->insert_id;
$stmt->close();

// 11. Canonical URL Generation
$canonicalUrl = "https://phache.com.vn/{$categorySlug}/{$slug}-{$newArticleId}.html";

// 12. Sitemap Update Automation (Append to sitemap-news.xml)
$sitemapFile = DOCROOT . 'sitemap-news.xml';
if (file_exists($sitemapFile) && is_writable($sitemapFile)) {
    $sitemapContent = @file_get_contents($sitemapFile);
    if (!empty($sitemapContent) && strpos($sitemapContent, '<urlset') !== false) {
        $todayDate = date('Y-m-d');
        $newUrlEntry = "  <url>\n" .
                       "    <loc>{$canonicalUrl}</loc>\n" .
                       "    <lastmod>{$todayDate}</lastmod>\n" .
                       "    <changefreq>weekly</changefreq>\n" .
                       "    <priority>0.70</priority>\n" .
                       "  </url>\n";

        // Insert right after <urlset ...>
        $updatedSitemap = preg_replace('/(<urlset\b[^>]*>)/i', "$1\n{$newUrlEntry}", $sitemapContent, 1);
        if ($updatedSitemap && $updatedSitemap !== $sitemapContent) {
            @file_put_contents($sitemapFile, $updatedSitemap);
        }
    }
}

// 13. Output Clean Success JSON
http_response_code(201);
echo json_encode([
    'success' => true,
    'message' => 'Bài viết đã được xuất bản thành công lên phache.com.vn!',
    'data'    => [
        'news_id'       => (int)$newArticleId,
        'title'         => $title,
        'slug'          => $slug,
        'canonical_url' => $canonicalUrl,
        'category_id'   => $categoryId,
        'category_slug' => $categorySlug,
        'featured_img'  => !empty($featuredImage) ? 'https://phache.com.vn/upload/news/' . $featuredImage : '',
        'published_at'  => $now,
        'published_date'=> date('Y-m-d H:i:s', $now),
        'faqs_injected' => count($faqs),
        'seo_features'  => [
            'single_h1_enforced'    => true,
            'lazy_images_optimized' => true,
            'faq_jsonld_injected'   => !empty($faqs),
            'cta_hook_injected'     => true,
            'sitemap_updated'       => file_exists($sitemapFile) && is_writable($sitemapFile)
        ]
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
