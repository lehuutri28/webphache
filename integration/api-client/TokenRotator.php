<?php
/**
 * TokenRotator — cron daily kiểm tra JWT có sắp expire không, gọi rotate
 *
 * Deploy target: /library/function/TokenRotator.php (Mat Bao)
 * Compat: PHP 5.6+
 *
 * Brief: orchestrator-ceo/briefs/2026-05-02-BRIEF-PHACHE-SERVICE-ACCOUNT-JWT.md
 *
 * Logic:
 *   - JWT TTL 30 ngày
 *   - 5 ngày trước expiry → gọi POST /auth/service-token/rotate (ERP)
 *   - ERP verify token còn valid + còn quyền service_token.rotate → mint token mới TTL 30d → invalidate token cũ sau 6h grace period
 *   - Web phache cập nhật cache/jwt-current.txt (atomic rename, chmod 600)
 *
 * Cron jobs (cPanel UI):
 *   0 3 * * * /usr/bin/php /home/<user>/public_html/library/function/TokenRotator.php >> /home/<user>/public_html/logs/token-rotator.log 2>&1
 *   (chạy 3h sáng mỗi ngày — ngoài giờ traffic)
 *
 * Lock: single-instance flock như CrmRetryWorker
 */

require_once __DIR__ . '/CrmClient.php';

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

$logDir = dirname(dirname(__DIR__)) . '/logs';
@mkdir($logDir, 0755, true);

// Single-instance lock
$lockFile = $logDir . '/token-rotator.lock';
$fp = fopen($lockFile, 'c');
if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
    fwrite(STDERR, "[TokenRotator] another instance running — skip\n");
    exit(0);
}

try {
    $client = new CrmClient();
    $result = $client->rotateTokenIfNeeded(5);  // 5 days before expiry

    $line = '[' . date('Y-m-d H:i:s') . '] [TokenRotator] '
          . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($logDir . '/token-rotator-' . date('Y-m-d') . '.log', $line, FILE_APPEND);
    fwrite(STDOUT, $line);

    if (!empty($result['rotated'])) {
        // Optional: gửi email/Zalo cho admin Letri biết đã rotate
        // mail('letri@vuaantoan.com', 'Phache JWT rotated', $line);
    }

    if (empty($result['ok'])) {
        // Nếu rotate fail + token sắp expire trong vài ngày → cần can thiệp
        // Ghi vào log riêng để Letri thấy ngay
        file_put_contents($logDir . '/token-rotator-ALERT.log',
            "[" . date('c') . "] ROTATE FAILED: " . (isset($result['error']) ? $result['error'] : 'unknown') . "\n",
            FILE_APPEND);
    }
} catch (Exception $e) {
    file_put_contents($logDir . '/token-rotator-ALERT.log',
        "[" . date('c') . "] EXCEPTION: " . $e->getMessage() . "\n",
        FILE_APPEND);
}

flock($fp, LOCK_UN);
fclose($fp);
