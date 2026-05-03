<?php
/**
 * customer-lookup — proxy AJAX cho B.3 auto-fill form
 *
 * Deploy target: /template/api/customer-lookup.php (Mat Bao)
 * Compat: PHP 5.6+
 *
 * Frontend (auto-fill.js) gọi: POST /template/api/customer-lookup.php
 * Body: { phone: "0987654321" }
 *
 * Server-side gọi ERP /customers?search=<phone>, JWT giữ trên server
 * (KHÔNG expose JWT cho browser).
 *
 * Response:
 *   { found: true, name, email, address }
 *   { found: false }
 *   { error: "<msg>" } + HTTP 4xx
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

require_once dirname(dirname(__DIR__)) . '/library/function/normalize_phone.php';
require_once dirname(dirname(__DIR__)) . '/library/function/CrmClient.php';

// Load .env (config/.env)
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

// Đọc body JSON hoặc form-encoded
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) $input = $_POST;

$phoneInput = isset($input['phone']) ? $input['phone'] : '';
$phone = normalize_phone_vn($phoneInput);
if ($phone === null) {
    http_response_code(400);
    echo json_encode(array('error' => 'Số điện thoại không đúng chuẩn'));
    exit;
}

// Rate limit nhẹ theo IP
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
$rateFile = sys_get_temp_dir() . '/phache-lookup-' . md5($ip) . '.txt';
$now = time();
$hits = array();
if (file_exists($rateFile)) {
    $contents = (string)@file_get_contents($rateFile);
    $rawHits = explode("\n", trim($contents));
    foreach ($rawHits as $t) {
        if ($t !== '' && ($now - (int)$t) < 60) $hits[] = $t;
    }
}
if (count($hits) >= 30) {
    http_response_code(429);
    echo json_encode(array('error' => 'Quá nhiều yêu cầu, thử lại sau 1 phút'));
    exit;
}
$hits[] = (string)$now;
@file_put_contents($rateFile, implode("\n", $hits));

$client = new CrmClient();
$customer = $client->findCustomerByPhone($phone);

if ($customer === null) {
    echo json_encode(array('found' => false));
    exit;
}

// Subset minimal — KHÔNG expose totalSpent, classification, internal id
echo json_encode(array(
    'found'   => true,
    'name'    => isset($customer['fullName']) ? $customer['fullName'] : '',
    'email'   => isset($customer['email']) ? $customer['email'] : '',
    'address' => isset($customer['address']) ? $customer['address'] : '',
), JSON_UNESCAPED_UNICODE);
