<?php
/**
 * CrmRetryWorker — cron 5 phút/lần đọc /logs/crm-queue/ và retry push CRM
 *
 * Deploy target: /library/function/CrmRetryWorker.php (Mat Bao)
 * Compat: PHP 5.6+
 *
 * Cron jobs (cPanel UI):
 *   *\/5 * * * * /usr/bin/php /home/<user>/public_html/library/function/CrmRetryWorker.php >> /home/<user>/public_html/logs/cron.log 2>&1
 */

require_once __DIR__ . '/CrmClient.php';

// Load .env nếu chưa load
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

$logDir   = dirname(dirname(__DIR__)) . '/logs';
$queueDir = $logDir . '/crm-queue';
$doneDir  = $queueDir . '/done';
$failDir  = $queueDir . '/failed';
@mkdir($doneDir, 0755, true);
@mkdir($failDir, 0755, true);

// Single-instance lock
$lockFile = $logDir . '/crm-retry.lock';
$fp = fopen($lockFile, 'c');
if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
    fwrite(STDERR, "[CrmRetry] another instance running — skip\n");
    exit(0);
}

$client = new CrmClient();
$processed = 0;
$success = 0;
$failed = 0;

$files = glob($queueDir . '/*.json');
if (!is_array($files)) $files = array();
usort($files, function ($a, $b) { return filemtime($a) - filemtime($b); });

foreach ($files as $file) {
    if ($processed >= 200) break; // batch cap
    $processed++;

    $job = json_decode(@file_get_contents($file), true);
    if (!is_array($job) || empty($job['payload'])) {
        rename($file, $failDir . '/' . basename($file));
        continue;
    }

    $job['attempts'] = (isset($job['attempts']) ? $job['attempts'] : 0) + 1;
    $result = $client->pushLead($job['payload']);

    if (!empty($result['ok'])) {
        rename($file, $doneDir . '/' . basename($file));
        $success++;
    } elseif ($job['attempts'] >= 10) {
        $job['final_error'] = isset($result['mode']) ? $result['mode'] : 'unknown';
        @file_put_contents($file, json_encode($job, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        rename($file, $failDir . '/' . basename($file));
        $failed++;
    } else {
        @file_put_contents($file, json_encode($job, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        usleep(200000);
    }
}

$remainingFiles = glob($queueDir . '/*.json');
$remaining = is_array($remainingFiles) ? count($remainingFiles) : 0;
$line = sprintf(
    "[%s] [CrmRetry] processed=%d success=%d moved-to-failed=%d remaining=%d\n",
    date('Y-m-d H:i:s'), $processed, $success, $failed, $remaining
);
file_put_contents($logDir . '/crm-retry-' . date('Y-m-d') . '.log', $line, FILE_APPEND);
fwrite(STDOUT, $line);

flock($fp, LOCK_UN);
fclose($fp);
