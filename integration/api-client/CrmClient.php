<?php
/**
 * CrmClient — wrapper HTTP push lead phache → ERP Wecha CRM
 *
 * Deploy target: /library/function/CrmClient.php (Mat Bao)
 * Compat: PHP 5.6+ (KHÔNG type hints, KHÔNG ?? operator, KHÔNG \Throwable)
 *
 * Auth: Service-account JWT TTL 30d + AUTO-ROTATE (Opus CEO ERP brief 2/5/2026)
 *   - User: service-phache@vuaantoan.com (tenant VUA AN TOÀN — 2eb99a91-...)
 *   - Role: phache_web_service (4 perm: customers.view/create + customer_crm.interactions.write + courses.available.read)
 *   - Lưu trong /config/.env: WECHA_ERP_API_URL, WECHA_ERP_JWT (initial seed)
 *   - Auto-rotate: token mới đọc từ /cache/jwt-current.txt (do TokenRotator.php cập nhật mỗi 25 ngày)
 *   - Rate limit: 60 req/phút (server-side enforce)
 *
 * Logic pushLead:
 *   1) GET /customers?search=<phone>&limit=1 → tra customer tồn tại
 *   2a) Match → POST /customers/:id/interactions (NOT đổi classification cũ)
 *   2b) No match → POST /customers (classification=lead_tiem_nang) + interaction
 *
 * Fire-and-forget: nếu fail → push vào /logs/crm-queue/<uuid>.json để retry
 *
 * Circuit breaker (Opus CEO ERP đề xuất 2/5):
 *   - Sau 5 fail liên tiếp trong 60s → mở mạch (open) 5 phút → enqueue trực tiếp,
 *     KHÔNG gọi HTTP để tiết kiệm timeout 15s × N request khi ERP xuống.
 *   - Sau 5 phút → half-open → cho thử 1 request, success → close, fail → open lại.
 *   - State lưu file `/logs/crm-circuit.json` (atomic rename).
 */

if (!class_exists('CrmClient')) {

class CrmClient
{
    private $baseUrl;
    private $jwt;
    private $databaseId;
    private $timeout;
    private $logDir;
    private $queueDir;
    private $circuitFile;
    // Circuit breaker config
    private $cbFailThreshold = 5;     // 5 fail liên tiếp → open
    private $cbWindowSec     = 60;    // trong vòng 60 giây
    private $cbOpenSec       = 300;   // open 5 phút rồi half-open thử lại

    public function __construct($config = array())
    {
        $baseUrl = isset($config['base_url']) ? $config['base_url'] : getenv('WECHA_ERP_API_URL');
        $this->baseUrl = rtrim($baseUrl ? $baseUrl : '', '/');

        // JWT priority: 1) explicit config, 2) cache file (rotated), 3) env (initial seed)
        if (isset($config['jwt'])) {
            $this->jwt = $config['jwt'];
        } else {
            $this->jwt = $this->loadCurrentJwt();
        }

        $this->databaseId = isset($config['database_id']) ? $config['database_id'] : getenv('WECHA_ERP_DATABASE_ID');
        $this->timeout    = (int)(isset($config['timeout']) ? $config['timeout'] : 15);

        if (isset($config['log_dir'])) {
            $this->logDir = $config['log_dir'];
        } elseif (defined('SOURCE_DIR')) {
            $this->logDir = SOURCE_DIR . '/logs';
        } else {
            $this->logDir = __DIR__ . '/../../logs';
        }
        $this->queueDir    = $this->logDir . '/crm-queue';
        $this->circuitFile = $this->logDir . '/crm-circuit.json';

        if (!is_dir($this->logDir))   @mkdir($this->logDir, 0755, true);
        if (!is_dir($this->queueDir)) @mkdir($this->queueDir, 0755, true);

        if (empty($this->baseUrl) || empty($this->jwt)) {
            $this->log('boot', 'WARN', 'Missing WECHA_ERP_API_URL or JWT — push sẽ bị queue.');
        }
    }

    /**
     * Load JWT current từ cache file (do TokenRotator cập nhật).
     * Fallback về env WECHA_ERP_JWT nếu cache chưa có.
     */
    protected function loadCurrentJwt() {
        $cachePath = (defined('SOURCE_DIR') ? SOURCE_DIR : dirname(dirname(__DIR__))) . '/cache/jwt-current.txt';
        if (file_exists($cachePath) && is_readable($cachePath)) {
            $tok = trim((string)@file_get_contents($cachePath));
            if ($tok !== '') return $tok;
        }
        $env = getenv('WECHA_ERP_JWT');
        return $env !== false ? $env : '';
    }

    /**
     * Public method để TokenRotator gọi rotate.
     * @param int $daysBeforeExpiry Số ngày trước expiry để rotate (default 5)
     * @return array ['ok'=>bool, 'rotated'=>bool, 'expires_at'=>iso_string]
     */
    public function rotateTokenIfNeeded($daysBeforeExpiry = 5) {
        try {
            // Decode JWT payload (base64) để xem expiry
            $parts = explode('.', $this->jwt);
            if (count($parts) !== 3) {
                throw new RuntimeException('Invalid JWT format');
            }
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            if (!is_array($payload) || empty($payload['exp'])) {
                throw new RuntimeException('JWT missing exp claim');
            }
            $exp = (int)$payload['exp'];
            $secsLeft = $exp - time();
            $threshold = $daysBeforeExpiry * 86400;

            if ($secsLeft > $threshold) {
                return array('ok' => true, 'rotated' => false, 'expires_at' => date('c', $exp), 'secs_left' => $secsLeft);
            }

            // Token sắp expire → call /auth/service-token/rotate
            $resp = $this->request('POST', '/auth/service-token/rotate', array());
            $newToken = isset($resp['token']) ? $resp['token']
                : (isset($resp['data']['token']) ? $resp['data']['token'] : null);
            if (empty($newToken)) {
                throw new RuntimeException('Rotate response missing token field');
            }

            // Atomic write
            $cacheDir = dirname((defined('SOURCE_DIR') ? SOURCE_DIR : dirname(dirname(__DIR__))) . '/cache/jwt-current.txt');
            if (!is_dir($cacheDir)) @mkdir($cacheDir, 0755, true);
            $cachePath = $cacheDir . '/jwt-current.txt';
            $tmp = $cachePath . '.tmp.' . getmypid();
            @file_put_contents($tmp, $newToken);
            @chmod($tmp, 0600);
            @rename($tmp, $cachePath);

            $this->jwt = $newToken;
            $newPayload = json_decode(base64_decode(strtr(explode('.', $newToken)[1], '-_', '+/')), true);
            $newExp = isset($newPayload['exp']) ? date('c', $newPayload['exp']) : 'unknown';
            $this->log('rotate', 'OK', "rotated token, new expiry $newExp");
            return array('ok' => true, 'rotated' => true, 'expires_at' => $newExp);
        } catch (Exception $e) {
            $this->log('rotate', 'ERR', $e->getMessage());
            return array('ok' => false, 'rotated' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Push 1 lead lên ERP CRM. Fire-and-forget, không throw.
     *
     * @param array $payload
     * @return array ['ok'=>bool, 'mode'=>string, 'customer_id'=>string|null, 'queued'=>string|null]
     */
    public function pushLead($payload)
    {
        try {
            if (empty($payload['phone']) || empty($payload['name'])) {
                throw new InvalidArgumentException('payload missing phone/name');
            }
            if (empty($this->baseUrl) || empty($this->jwt)) {
                return $this->enqueue($payload, 'no_credentials');
            }

            // Circuit breaker — nếu mạch đang open, enqueue thẳng không gọi HTTP
            if ($this->isCircuitOpen()) {
                return $this->enqueue($payload, 'circuit_open');
            }

            // Bước 1 — search customer
            $found = $this->request('GET', '/customers?search=' . rawurlencode($payload['phone']) . '&limit=1');
            $items = isset($found['items']) ? $found['items'] : (isset($found['data']) ? $found['data'] : array());

            if (!empty($items) && !empty($items[0]['id'])) {
                $customerId = $items[0]['id'];
                $mode = 'merged';
            } else {
                // Bước 2b — tạo customer mới
                $created = $this->request('POST', '/customers', array(
                    'fullName'       => $payload['name'],
                    'phone'          => $payload['phone'],
                    'email'          => isset($payload['email']) ? $payload['email'] : null,
                    'address'        => isset($payload['address']) ? $payload['address'] : null,
                    'classification' => 'lead_tiem_nang',
                    'notes'          => 'Lead từ phache.com.vn (' . (isset($payload['form_type']) ? $payload['form_type'] : '?') . ')',
                ));
                $customerId = isset($created['id']) ? $created['id']
                    : (isset($created['data']['id']) ? $created['data']['id'] : null);
                if (empty($customerId)) {
                    throw new RuntimeException('Tạo customer thất bại — response không có id');
                }
                $mode = 'created';
            }

            // Bước 3 — ghi interaction
            $title = 'Đăng ký từ phache.com.vn';
            if (!empty($payload['course_name'])) {
                $title .= ' — Khoá ' . $payload['course_name'];
            }
            $this->request('POST', '/customers/' . rawurlencode($customerId) . '/interactions', array(
                'interactionType' => 'note',
                'direction'       => 'inbound',
                'title'           => $title,
                'content'         => $this->buildInteractionContent($payload),
                'referenceType'   => 'lead_web',
                'status'          => 'open',
            ));

            $this->log('pushLead', 'OK', "$mode customer=$customerId phone={$payload['phone']}");
            $this->circuitOnSuccess();
            return array('ok' => true, 'mode' => $mode, 'customer_id' => $customerId);

        } catch (Exception $e) {
            $phone = isset($payload['phone']) ? $payload['phone'] : '?';
            $this->log('pushLead', 'ERR', $e->getMessage() . ' phone=' . $phone);
            $this->circuitOnFailure();
            return $this->enqueue($payload, $e->getMessage());
        }
    }

    // ============================================================
    // Circuit breaker
    // ============================================================

    private function loadCircuit() {
        if (!file_exists($this->circuitFile)) {
            return array('state' => 'closed', 'fails' => 0, 'first_fail_at' => 0, 'opened_at' => 0);
        }
        $j = json_decode((string)@file_get_contents($this->circuitFile), true);
        return is_array($j) ? $j : array('state' => 'closed', 'fails' => 0, 'first_fail_at' => 0, 'opened_at' => 0);
    }

    private function saveCircuit($c) {
        $tmp = $this->circuitFile . '.tmp.' . getmypid();
        @file_put_contents($tmp, json_encode($c));
        @rename($tmp, $this->circuitFile);
    }

    public function isCircuitOpen() {
        $c = $this->loadCircuit();
        $now = time();
        if ($c['state'] === 'open') {
            if (($now - $c['opened_at']) >= $this->cbOpenSec) {
                // Half-open: cho 1 request thử
                $c['state'] = 'half_open';
                $this->saveCircuit($c);
                return false;
            }
            return true;
        }
        return false;
    }

    private function circuitOnSuccess() {
        $c = $this->loadCircuit();
        if ($c['state'] !== 'closed' || $c['fails'] > 0) {
            $this->log('circuit', 'CLOSED', 'breaker reset after success');
        }
        $this->saveCircuit(array('state' => 'closed', 'fails' => 0, 'first_fail_at' => 0, 'opened_at' => 0));
    }

    private function circuitOnFailure() {
        $c = $this->loadCircuit();
        $now = time();
        if ($c['state'] === 'half_open') {
            // Half-open thử fail → open lại
            $this->saveCircuit(array('state' => 'open', 'fails' => $c['fails'] + 1, 'first_fail_at' => $c['first_fail_at'], 'opened_at' => $now));
            $this->log('circuit', 'OPEN', 'half-open re-failed, back to open');
            return;
        }
        // Reset fail count nếu ngoài window
        if (($now - $c['first_fail_at']) > $this->cbWindowSec) {
            $c['fails'] = 0;
            $c['first_fail_at'] = $now;
        } elseif ($c['fails'] === 0) {
            $c['first_fail_at'] = $now;
        }
        $c['fails']++;
        if ($c['fails'] >= $this->cbFailThreshold) {
            $this->saveCircuit(array('state' => 'open', 'fails' => $c['fails'], 'first_fail_at' => $c['first_fail_at'], 'opened_at' => $now));
            $this->log('circuit', 'OPEN', "tripped after {$c['fails']} fails in {$this->cbWindowSec}s — open {$this->cbOpenSec}s");
        } else {
            $c['state'] = 'closed';
            $this->saveCircuit($c);
        }
    }

    /**
     * Search customer theo phone (cho B.3 auto-fill).
     * @param string $phone
     * @return array|null
     */
    public function findCustomerByPhone($phone)
    {
        try {
            $r = $this->request('GET', '/customers?search=' . rawurlencode($phone) . '&limit=1');
            $items = isset($r['items']) ? $r['items'] : (isset($r['data']) ? $r['data'] : array());
            return isset($items[0]) ? $items[0] : null;
        } catch (Exception $e) {
            $this->log('findCustomerByPhone', 'ERR', $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy danh sách khoá học available (cho B.4).
     * @param string $branchId
     * @return array
     */
    public function listAvailableCourses($branchId)
    {
        try {
            $r = $this->request('GET', '/courses/available?branch_id=' . rawurlencode($branchId));
            if (isset($r['data']))  return $r['data'];
            if (isset($r['items'])) return $r['items'];
            return is_array($r) ? $r : array();
        } catch (Exception $e) {
            $this->log('listAvailableCourses', 'ERR', $e->getMessage());
            return array();
        }
    }

    /**
     * HTTP request với retry exponential backoff khi 5xx/timeout.
     * protected để test mock có thể override.
     */
    protected function request($method, $path, $body = null, $maxRetry = 3)
    {
        $url = $this->baseUrl . $path;
        $attempt = 0;
        $lastErr = '';

        while ($attempt < $maxRetry) {
            $attempt++;
            $ch = curl_init($url);
            $headers = array(
                'Authorization: Bearer ' . $this->jwt,
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Source: phache.com.vn',
            );
            if (!empty($this->databaseId)) {
                $headers[] = 'X-Database-Id: ' . $this->databaseId;
            }
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_CUSTOMREQUEST  => $method,
                CURLOPT_TIMEOUT        => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ));
            if ($body !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
            }
            $resp = curl_exec($ch);
            $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err  = curl_error($ch);
            curl_close($ch);

            if ($err !== '') {
                $lastErr = "curl: $err";
            } elseif ($code >= 500 || $code === 0) {
                $lastErr = "HTTP $code body=" . substr((string)$resp, 0, 200);
            } elseif ($code >= 400) {
                throw new RuntimeException("HTTP $code: " . substr((string)$resp, 0, 500));
            } else {
                $decoded = json_decode($resp, true);
                return is_array($decoded) ? $decoded : array();
            }

            // 5xx/timeout → retry
            if ($attempt < $maxRetry) {
                usleep((int)(pow(2, $attempt) * 200000)); // 0.4s, 0.8s, 1.6s
            }
        }

        throw new RuntimeException("Retry exhausted ($maxRetry): $lastErr");
    }

    private function buildInteractionContent($payload)
    {
        $lines = array();
        $lines[] = 'Nguồn: phache.com.vn (' . (isset($payload['form_type']) ? $payload['form_type'] : '?') . ')';
        if (!empty($payload['phone']))       $lines[] = 'SĐT: ' . $payload['phone'];
        if (!empty($payload['email']))       $lines[] = 'Email: ' . $payload['email'];
        if (!empty($payload['address']))     $lines[] = 'Địa chỉ: ' . $payload['address'];
        if (!empty($payload['course_name'])) $lines[] = 'Khoá quan tâm: ' . $payload['course_name'];
        if (!empty($payload['content']))     $lines[] = 'Nội dung: ' . $payload['content'];
        if (!empty($payload['raw'])) {
            $lines[] = '— Raw form data —';
            $lines[] = json_encode($payload['raw'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        return implode("\n", $lines);
    }

    private function enqueue($payload, $reason)
    {
        // PHP 5.6 không có random_bytes() — dùng openssl_random_pseudo_bytes (có từ 5.3)
        $bytes = function_exists('random_bytes')
            ? random_bytes(8)
            : openssl_random_pseudo_bytes(8);
        $id = bin2hex($bytes);
        $file = $this->queueDir . '/' . date('Y-m-d_His') . '_' . $id . '.json';
        @file_put_contents($file, json_encode(array(
            'queued_at' => date('c'),
            'reason'    => $reason,
            'payload'   => $payload,
            'attempts'  => 0,
        ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $phone = isset($payload['phone']) ? $payload['phone'] : '?';
        $this->log('enqueue', 'QUEUE', "$id reason=$reason phone=$phone");
        return array('ok' => false, 'mode' => 'queued', 'queued' => $file);
    }

    private function log($action, $level, $msg)
    {
        $line = '[' . date('Y-m-d H:i:s') . "] [$level] [$action] $msg\n";
        @file_put_contents($this->logDir . '/crm-' . date('Y-m-d') . '.log', $line, FILE_APPEND);
    }
}

}
