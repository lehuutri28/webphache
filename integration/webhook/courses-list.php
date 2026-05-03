<?php
/**
 * courses-list — proxy GET danh sách khoá học từ ERP, có cache + flock
 *
 * Deploy target: /template/api/courses-list.php (Mat Bao)
 * Compat: PHP 5.6+
 *
 * Cache 15 phút trong /cache/courses.json
 * Cache stampede protection: flock + serve stale while rebuild
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');

require_once dirname(dirname(__DIR__)) . '/library/function/CrmClient.php';

// Load .env
$envFile = dirname(dirname(__DIR__)) . '/config/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            $parts = explode('=', $line, 2);
            $k = trim($parts[0]);
            $v = trim($parts[1], " \t\n\r\0\x0B\"'");
            if (getenv($k) === false) {
                putenv($k . '=' . $v);
            }
        }
    }
}

$branchId = '';
if (isset($_GET['branch_id'])) {
    $branchId = $_GET['branch_id'];
} else {
    $env = getenv('WECHA_ERP_DEFAULT_BRANCH_ID');
    if ($env !== false) $branchId = $env;
}

if ($branchId === '') {
    http_response_code(400);
    echo json_encode(array('error' => 'branch_id required'));
    exit;
}
if (!preg_match('/^[a-z0-9-]{8,64}$/i', $branchId)) {
    http_response_code(400);
    echo json_encode(array('error' => 'branch_id invalid'));
    exit;
}

$cacheDir  = dirname(dirname(__DIR__)) . '/cache';
@mkdir($cacheDir, 0755, true);
$cacheFile = $cacheDir . '/courses_' . md5($branchId) . '.json';
$lockFile  = $cacheDir . '/courses_' . md5($branchId) . '.lock';
$ttl = 15 * 60;

$now = time();
$cacheAge = file_exists($cacheFile) ? ($now - filemtime($cacheFile)) : PHP_INT_MAX;
$cacheValid = $cacheAge < $ttl;

if ($cacheValid) {
    readfile($cacheFile);
    exit;
}

// Cache miss/expired → thử lock để rebuild
$lock = fopen($lockFile, 'c');
if (!$lock) {
    http_response_code(500);
    echo json_encode(array('error' => 'cannot open lock'));
    exit;
}

if (flock($lock, LOCK_EX | LOCK_NB)) {
    // Process duy nhất rebuild
    try {
        $client = new CrmClient();
        $courses = $client->listAvailableCourses($branchId);
        $json = json_encode($courses, JSON_UNESCAPED_UNICODE);
        if ($json !== false) {
            file_put_contents($cacheFile, $json, LOCK_EX);
            echo $json;
        } else {
            echo '[]';
        }
    } catch (Exception $e) {
        if (file_exists($cacheFile)) {
            readfile($cacheFile);
        } else {
            http_response_code(502);
            echo json_encode(array('error' => 'upstream failed'));
        }
    }
    flock($lock, LOCK_UN);
} else {
    // Process khác đang rebuild → serve stale
    if (file_exists($cacheFile)) {
        readfile($cacheFile);
    } else {
        // Lần đầu chưa có cache → block chờ tối đa 5s
        $start = microtime(true);
        $served = false;
        while ((microtime(true) - $start) < 5.0) {
            usleep(200000);
            if (file_exists($cacheFile)) {
                readfile($cacheFile);
                $served = true;
                break;
            }
        }
        if (!$served) {
            http_response_code(503);
            echo json_encode(array('error' => 'cache rebuild timeout'));
        }
    }
}
fclose($lock);
