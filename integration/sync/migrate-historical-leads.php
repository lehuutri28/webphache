<?php
/**
 * migrate-historical-leads — ETL 1 lần phache → ERP CRM
 *
 * CHẠY LOCAL (không deploy lên Mat Bao). Cần mysqldump phache về local
 * hoặc kết nối từ xa qua phpMyAdmin export.
 *
 * Compat: PHP 5.6+ (chạy CLI trên local dev box)
 *
 * Pipeline 4 bước:
 *   1) Đọc form_advisory + form_sign + call_to_action từ pha5bd2c_db
 *   2) Filter SĐT chuẩn VN — ước tính giữ 30-60%
 *   3) Dedup theo SĐT chuẩn hoá (giữ record mới nhất)
 *   4) So sánh ERP /customers?search=<phone>:
 *        - Match → MERGE interaction (KHÔNG tạo customer mới)
 *        - No match → tạo customer + interaction
 *
 * Usage:
 *   php migrate-historical-leads.php --dsn="mysql:host=...;dbname=pha5bd2c_db" --user=... --pass=... [--dry-run] [--limit=500] [--resume]
 *
 *   Env:
 *     WECHA_ERP_API_URL=https://erp.staging.wecha.vn/api
 *     WECHA_ERP_JWT=<service-phache jwt>
 *     WECHA_ERP_DATABASE_ID=<vat2026 uuid>
 */

require_once __DIR__ . '/../api-client/CrmClient.php';
require_once __DIR__ . '/../api-client/normalize_phone.php';

// --- Parse CLI args ---
$opts = getopt('', array('dsn:', 'user:', 'pass:', 'dry-run', 'limit::', 'resume'));
if (empty($opts['dsn']) || empty($opts['user'])) {
    fwrite(STDERR, "Usage: php migrate-historical-leads.php --dsn='mysql:host=...;dbname=pha5bd2c_db;charset=utf8mb4' --user=USER --pass=PASS [--dry-run] [--limit=500] [--resume]\n");
    exit(1);
}

$dryRun = isset($opts['dry-run']);
$batchLimit = (int)(isset($opts['limit']) ? $opts['limit'] : 500);

$stateFile = __DIR__ . '/migrate-state.json';
$reportFile = dirname(dirname(__DIR__)) . '/docs/migrate-historical-report-' . date('Y-m-d') . '.md';
@mkdir(dirname($reportFile), 0755, true);

if (file_exists($stateFile) && empty($opts['resume'])) {
    fwrite(STDERR, "State file tồn tại. --resume để tiếp tục, hoặc xoá file để chạy lại.\n");
    exit(2);
}

$state = file_exists($stateFile) ? json_decode(file_get_contents($stateFile), true) : null;
if (!is_array($state)) {
    $state = array(
        'started_at'       => date('c'),
        'last_advisory_id' => 0,
        'last_sign_id'     => 0,
        'last_cta_id'      => 0,
        'stats' => array(
            'raw' => 0, 'kept' => 0, 'dropped_phone' => 0, 'duplicate' => 0,
            'merged' => 0, 'created' => 0, 'error' => 0,
        ),
        'seen_phones' => array(),
    );
}

$pdo = new PDO($opts['dsn'], $opts['user'], isset($opts['pass']) ? $opts['pass'] : '', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
));

$client = $dryRun ? null : new CrmClient();

/**
 * Process 1 record: filter + dedup + push.
 */
function processLead($rec, $formType, &$state, $client, $dryRun) {
    $state['stats']['raw']++;

    $phoneRaw = isset($rec['phone']) ? $rec['phone'] : '';
    $phone = normalize_phone_vn($phoneRaw);
    if ($phone === null) {
        $state['stats']['dropped_phone']++;
        return;
    }

    $createdAt = isset($rec['created_at']) ? $rec['created_at'] : date('c');
    if (isset($state['seen_phones'][$phone]) && $state['seen_phones'][$phone] >= $createdAt) {
        $state['stats']['duplicate']++;
        return;
    }
    $state['seen_phones'][$phone] = $createdAt;
    $state['stats']['kept']++;

    if ($dryRun || $client === null) return;

    $payload = array(
        'form_type'   => $formType,
        'phone'       => $phone,
        'name'        => isset($rec['name']) ? $rec['name'] : 'Unknown',
        'email'       => isset($rec['email']) ? $rec['email'] : null,
        'address'     => isset($rec['address']) ? $rec['address'] : null,
        'course_name' => isset($rec['course']) ? $rec['course'] : null,
        'content'     => isset($rec['content']) ? $rec['content'] : null,
        'raw'         => $rec,
    );

    try {
        $r = $client->pushLead($payload);
        if (!empty($r['ok'])) {
            if ($r['mode'] === 'created') {
                $state['stats']['created']++;
            } else {
                $state['stats']['merged']++;
            }
        } else {
            $state['stats']['error']++;
        }
    } catch (Exception $e) {
        $state['stats']['error']++;
    }

    usleep(20000); // 20ms throttle/record
}

// --- form_advisory ---
$stmt = $pdo->prepare(
    "SELECT advisory_id, advisory_name AS name, advisory_email AS email, advisory_number AS phone,
            advisory_demand AS course, advisory_content AS content
     FROM form_advisory WHERE advisory_id > :last
     ORDER BY advisory_id ASC LIMIT :lim"
);
$stmt->bindValue(':last', $state['last_advisory_id'], PDO::PARAM_INT);
$stmt->bindValue(':lim', $batchLimit, PDO::PARAM_INT);
$stmt->execute();
foreach ($stmt as $row) {
    processLead($row, 'advisory', $state, $client, $dryRun);
    $state['last_advisory_id'] = (int)$row['advisory_id'];
}

// --- form_sign ---
$stmt = $pdo->prepare(
    "SELECT sign_id, sign_name AS name, sign_email AS email, sign_number AS phone,
            sign_birthday AS birthday, sign_home AS address, sign_day AS course
     FROM form_sign WHERE sign_id > :last
     ORDER BY sign_id ASC LIMIT :lim"
);
$stmt->bindValue(':last', $state['last_sign_id'], PDO::PARAM_INT);
$stmt->bindValue(':lim', $batchLimit, PDO::PARAM_INT);
$stmt->execute();
foreach ($stmt as $row) {
    processLead($row, 'sign', $state, $client, $dryRun);
    $state['last_sign_id'] = (int)$row['sign_id'];
}

// --- call_to_action ---
$stmt = $pdo->prepare(
    "SELECT cta_id, cta_name AS name, cta_phone AS phone, cta_course AS course,
            cta_purpose AS content, cta_create AS created_at
     FROM call_to_action WHERE cta_id > :last
     ORDER BY cta_id ASC LIMIT :lim"
);
$stmt->bindValue(':last', $state['last_cta_id'], PDO::PARAM_INT);
$stmt->bindValue(':lim', $batchLimit, PDO::PARAM_INT);
$stmt->execute();
foreach ($stmt as $row) {
    processLead($row, 'cta', $state, $client, $dryRun);
    $state['last_cta_id'] = (int)$row['cta_id'];
}

// --- Save state ---
file_put_contents($stateFile, json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// --- Report ---
$s = $state['stats'];
$report = "# Migrate Historical Leads — Report\n\n";
$report .= "**Run**: " . date('c') . " " . ($dryRun ? '(DRY-RUN)' : '(LIVE)') . "\n";
$report .= "**Started**: {$state['started_at']}\n\n";
$report .= "## Stats\n\n";
$report .= "| Metric | Count |\n|---|---:|\n";
$report .= "| Raw input | {$s['raw']} |\n";
$report .= "| Dropped (phone không chuẩn) | {$s['dropped_phone']} |\n";
$report .= "| Duplicate (cùng SĐT) | {$s['duplicate']} |\n";
$report .= "| Kept (unique + chuẩn) | {$s['kept']} |\n";
$report .= "| Merged (KH có sẵn ERP) | {$s['merged']} |\n";
$report .= "| Created (KH mới) | {$s['created']} |\n";
$report .= "| Error | {$s['error']} |\n\n";
$report .= "## Checkpoint\n\n";
$report .= "- last_advisory_id: {$state['last_advisory_id']}\n";
$report .= "- last_sign_id: {$state['last_sign_id']}\n";
$report .= "- last_cta_id: {$state['last_cta_id']}\n\n";
$report .= "Re-run với `--resume` để tiếp tục.\n";

file_put_contents($reportFile, $report);
echo "Done. Report: $reportFile\n";
echo "Stats: kept={$s['kept']} dropped={$s['dropped_phone']} dup={$s['duplicate']} merged={$s['merged']} created={$s['created']} err={$s['error']}\n";
