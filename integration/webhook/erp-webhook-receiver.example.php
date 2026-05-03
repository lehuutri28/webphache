<?php
/**
 * ERP → Phache Webhook Receiver (mẫu PHP)
 *
 * Đặt ở public/erp-webhook.php trên phache.com.vn
 * Hoặc tích hợp vào framework (Laravel route / CodeIgniter controller)
 *
 * URL: https://phache.com.vn/erp-webhook
 *
 * ERP sẽ POST các event:
 *   - product.updated  → invalidate cache SP
 *   - product.discontinued → ẩn SP khỏi web
 *   - order.confirmed  → gửi email khách
 *   - order.shipped    → update tracking
 *   - customer.tier_changed → update privilege
 *
 * Verify HMAC từ header X-Wecha-Signature (env PHACHE_WEBHOOK_SECRET)
 */

declare(strict_types=1);

$secret = getenv('PHACHE_WEBHOOK_SECRET') ?: '';
if ($secret === '') {
    http_response_code(500);
    echo json_encode(['error' => 'Missing PHACHE_WEBHOOK_SECRET env']);
    exit;
}

$rawBody = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WECHA_SIGNATURE'] ?? '';
$expected = hash_hmac('sha256', $rawBody, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid signature']);
    error_log("[ERP-Webhook] HMAC mismatch from " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    exit;
}

$payload = json_decode($rawBody, true);
if (!$payload || !isset($payload['event'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

// Audit log
error_log(sprintf(
    "[ERP-Webhook] event=%s timestamp=%s ref=%s",
    $payload['event'],
    $payload['timestamp'] ?? 'n/a',
    $payload['reference_id'] ?? 'n/a'
));

// Dispatch event
$result = ['ok' => true];
switch ($payload['event']) {
    case 'product.updated':
    case 'product.discontinued':
        // TODO: invalidate cache SP — gọi tới legacy/ logic
        $result['action'] = 'product_cache_cleared';
        break;
    case 'order.confirmed':
    case 'order.shipped':
        // TODO: gửi email/SMS khách
        $result['action'] = 'notification_queued';
        break;
    case 'customer.tier_changed':
        // TODO: update DB legacy
        $result['action'] = 'customer_synced';
        break;
    default:
        $result['action'] = 'ignored';
}

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($result);
