<?php
/**
 * Test CrmClient logic với mock HTTP backend
 *
 * Run: php integration/api-client/tests/test_crm_client_mock.php
 *
 * Compat: PHP 5.6+
 *
 * Mock pattern: extend CrmClient, override protected request().
 * Mỗi test reset queue + circuit + scripted responses.
 */

require_once __DIR__ . '/../CrmClient.php';

// ===== Mock subclass =====
class CrmClientMock extends CrmClient
{
    public $script = array();        // queue of [response_array | exception_msg]
    public $calls = array();         // log of [method, path, body]

    public function __construct($logDir) {
        parent::__construct(array(
            'base_url'    => 'https://mock.test/api',
            'jwt'         => 'mock-jwt',
            'database_id' => 'mock-db',
            'log_dir'     => $logDir,
        ));
    }

    public function setJwtForTest($jwt) {
        $ref = new ReflectionClass('CrmClient');
        $prop = $ref->getProperty('jwt');
        $prop->setAccessible(true);
        $prop->setValue($this, $jwt);
    }

    protected function request($method, $path, $body = null, $maxRetry = 3) {
        $this->calls[] = array('method' => $method, 'path' => $path, 'body' => $body);
        if (empty($this->script)) {
            throw new RuntimeException('mock: no scripted response left');
        }
        $next = array_shift($this->script);
        if (is_string($next) && strpos($next, '__THROW__:') === 0) {
            throw new RuntimeException(substr($next, 10));
        }
        return $next;
    }
}

// ===== Test harness =====
$pass = 0; $fail = 0; $failures = array();

function assertEq($actual, $expected, $name) {
    global $pass, $fail, $failures;
    if ($actual === $expected) { $pass++; }
    else {
        $fail++;
        $failures[] = "FAIL: $name — expected " . var_export($expected, true) . ", got " . var_export($actual, true);
    }
}

function freshClient() {
    $tmp = sys_get_temp_dir() . '/crm-test-' . uniqid();
    mkdir($tmp, 0755, true);
    return array(new CrmClientMock($tmp), $tmp);
}

function rmrf($dir) {
    if (!is_dir($dir)) return;
    foreach (glob($dir . '/{,.}[!.,!..]*', GLOB_BRACE) as $f) {
        if (is_dir($f)) rmrf($f); else @unlink($f);
    }
    @rmdir($dir);
}

// =============================================================
// TEST 1 — Lead mới (no match) → tạo customer + interaction
// =============================================================
echo "=== Test 1: lead mới → POST /customers + POST /interactions ===\n";
list($c, $tmp) = freshClient();
$c->script = array(
    array('items' => array()),                     // GET /customers?search=... → rỗng
    array('id' => 'cust-uuid-123'),                // POST /customers → return id
    array('id' => 'inter-uuid-456'),               // POST /customers/:id/interactions
);
$r = $c->pushLead(array(
    'form_type'   => 'cta',
    'phone'       => '0987654321',
    'name'        => 'Nguyễn Văn A',
    'course_name' => 'Pha chế trà sữa',
));
assertEq($r['ok'], true, 'pushLead OK');
assertEq($r['mode'], 'created', 'mode = created');
assertEq($r['customer_id'], 'cust-uuid-123', 'customer_id correct');
assertEq(count($c->calls), 3, '3 HTTP calls (search + create + interaction)');
assertEq($c->calls[0]['method'], 'GET', 'call 1 = GET');
assertEq($c->calls[1]['method'], 'POST', 'call 2 = POST');
assertEq($c->calls[1]['body']['classification'], 'lead_tiem_nang', 'classification = lead_tiem_nang');
assertEq($c->calls[2]['body']['referenceType'], 'lead_web', 'interaction referenceType');
rmrf($tmp);

// =============================================================
// TEST 2 — Lead có match → MERGE interaction (KHÔNG tạo customer)
// =============================================================
echo "=== Test 2: lead có match → MERGE interaction ===\n";
list($c, $tmp) = freshClient();
$c->script = array(
    array('items' => array(array('id' => 'existing-cust-789', 'fullName' => 'Khách Cũ'))),
    array('id' => 'new-inter-001'),                // POST interactions
);
$r = $c->pushLead(array(
    'form_type' => 'sign',
    'phone'     => '0905123456',
    'name'      => 'Trần Thị B',
));
assertEq($r['ok'], true, 'pushLead OK');
assertEq($r['mode'], 'merged', 'mode = merged');
assertEq($r['customer_id'], 'existing-cust-789', 'reuse existing customer');
assertEq(count($c->calls), 2, '2 HTTP calls (search + interaction, KHÔNG có POST /customers)');
rmrf($tmp);

// =============================================================
// TEST 3 — ERP down → enqueue, KHÔNG throw
// =============================================================
echo "=== Test 3: ERP down → enqueue ===\n";
list($c, $tmp) = freshClient();
$c->script = array('__THROW__:Connection refused');
$r = $c->pushLead(array(
    'form_type' => 'advisory',
    'phone'     => '0987111222',
    'name'      => 'Lê C',
));
assertEq($r['ok'], false, 'failed gracefully');
assertEq($r['mode'], 'queued', 'mode = queued');
assertEq(file_exists($r['queued']), true, 'queue file created on disk');
$queueFiles = glob($tmp . '/crm-queue/*.json');
assertEq(count($queueFiles), 1, '1 file in queue dir');
$queued = json_decode(file_get_contents($queueFiles[0]), true);
assertEq($queued['payload']['phone'], '0987111222', 'queue payload preserved');
rmrf($tmp);

// =============================================================
// TEST 4 — Validation: missing phone
// =============================================================
echo "=== Test 4: missing phone → enqueue with reason ===\n";
list($c, $tmp) = freshClient();
$r = $c->pushLead(array('name' => 'No Phone'));
assertEq($r['ok'], false, 'reject missing phone');
assertEq($r['mode'], 'queued', 'enqueued for review');
rmrf($tmp);

// =============================================================
// TEST 5 — Circuit breaker open sau 5 fail
// =============================================================
echo "=== Test 5: circuit breaker open sau 5 fail liên tiếp ===\n";
list($c, $tmp) = freshClient();
// 5 fail liên tiếp
for ($i = 0; $i < 5; $i++) {
    $c->script = array('__THROW__:network err ' . $i);
    $c->pushLead(array('phone' => '098000000' . $i, 'name' => 'X Y'));
}
$callsBeforeOpen = count($c->calls);
assertEq($callsBeforeOpen, 5, '5 HTTP attempts before breaker opens');

// Lần thứ 6 → circuit open → KHÔNG gọi HTTP, enqueue thẳng
$r = $c->pushLead(array('phone' => '0980000099', 'name' => 'X Y'));
assertEq($r['ok'], false, 'breaker open');
assertEq($r['mode'], 'queued', 'breaker → queued');
assertEq(count($c->calls), 5, 'KHÔNG có HTTP call mới khi circuit open');
assertEq($c->isCircuitOpen(), true, 'isCircuitOpen() = true');
rmrf($tmp);

// =============================================================
// TEST 6 — Circuit close lại sau success (half-open transition)
// =============================================================
echo "=== Test 6: circuit close lại sau success ===\n";
list($c, $tmp) = freshClient();
// Force open state
for ($i = 0; $i < 5; $i++) {
    $c->script = array('__THROW__:fail');
    $c->pushLead(array('phone' => '0980000' . sprintf('%03d', $i), 'name' => 'A B'));
}
assertEq($c->isCircuitOpen(), true, 'circuit open');

// Hack: force opened_at quá khứ để half-open (vì test không đợi 5 phút)
$state = json_decode(file_get_contents($tmp . '/crm-circuit.json'), true);
$state['opened_at'] = time() - 600;
file_put_contents($tmp . '/crm-circuit.json', json_encode($state));

assertEq($c->isCircuitOpen(), false, 'half-open transition');

// Half-open → success → close
$c->script = array(
    array('items' => array()),
    array('id' => 'recovery-cust'),
    array('id' => 'recovery-inter'),
);
$r = $c->pushLead(array('phone' => '0987111333', 'name' => 'Hồi Phục'));
assertEq($r['ok'], true, 'recovery success');
$state = json_decode(file_get_contents($tmp . '/crm-circuit.json'), true);
assertEq($state['state'], 'closed', 'breaker đóng lại');
assertEq($state['fails'], 0, 'fails reset = 0');
rmrf($tmp);

// =============================================================
// TEST 7 — findCustomerByPhone (cho B.3 auto-fill)
// =============================================================
echo "=== Test 7: findCustomerByPhone ===\n";
list($c, $tmp) = freshClient();
$c->script = array(array('items' => array(array(
    'id' => 'kh-001', 'fullName' => 'Khách A', 'email' => 'a@gmail.com', 'address' => 'HCM',
))));
$found = $c->findCustomerByPhone('0987654321');
assertEq($found['fullName'], 'Khách A', 'found customer');
assertEq($found['email'], 'a@gmail.com', 'email');

// Not found
$c->script = array(array('items' => array()));
$found = $c->findCustomerByPhone('0900000000');
assertEq($found, null, 'not found → null');

// Network err → null (không throw)
$c->script = array('__THROW__:err');
$found = $c->findCustomerByPhone('0901111111');
assertEq($found, null, 'network err → null');
rmrf($tmp);

// =============================================================
// TEST 8 — listAvailableCourses (cho B.4)
// =============================================================
echo "=== Test 8: listAvailableCourses ===\n";
list($c, $tmp) = freshClient();
$c->script = array(array('data' => array(
    array('courseId' => 'c1', 'courseName' => 'Pha chế A'),
    array('courseId' => 'c2', 'courseName' => 'Trà sữa B'),
)));
$courses = $c->listAvailableCourses('branch-uuid-x');
assertEq(count($courses), 2, '2 courses returned');
assertEq($courses[0]['courseName'], 'Pha chế A', 'first course name');
rmrf($tmp);

// =============================================================
// TEST 9 — rotateTokenIfNeeded: token còn nhiều ngày → KHÔNG rotate
// =============================================================
echo "=== Test 9: rotate KHÔNG cần thiết (còn nhiều ngày) ===\n";
list($c, $tmp) = freshClient();
// Build JWT giả với exp = now + 25 days
$payload = base64_encode(json_encode(array('exp' => time() + 25 * 86400, 'sub' => 'test')));
$fakeJwt = 'header.' . rtrim(strtr($payload, '+/', '-_'), '=') . '.sig';
$c->setJwtForTest($fakeJwt);

$r = $c->rotateTokenIfNeeded(5);
assertEq($r['ok'], true, 'rotate check OK');
assertEq($r['rotated'], false, 'KHÔNG rotate (còn 25 ngày)');
assertEq(count($c->calls), 0, 'KHÔNG có HTTP call');
rmrf($tmp);

// =============================================================
// TEST 10 — rotateTokenIfNeeded: token sắp expire → rotate
// =============================================================
echo "=== Test 10: token còn 3 ngày → rotate ===\n";
list($c, $tmp) = freshClient();
$payload = base64_encode(json_encode(array('exp' => time() + 3 * 86400, 'sub' => 'test')));
$fakeJwt = 'header.' . rtrim(strtr($payload, '+/', '-_'), '=') . '.sig';
$c->setJwtForTest($fakeJwt);

// Build new JWT với exp = now + 30 days
$newPayload = base64_encode(json_encode(array('exp' => time() + 30 * 86400, 'sub' => 'test')));
$newJwt = 'header.' . rtrim(strtr($newPayload, '+/', '-_'), '=') . '.sig';

$c->script = array(array('token' => $newJwt));
$r = $c->rotateTokenIfNeeded(5);
assertEq($r['ok'], true, 'rotate OK');
assertEq($r['rotated'], true, 'đã rotate');
assertEq(count($c->calls), 1, '1 HTTP call (rotate endpoint)');
assertEq($c->calls[0]['method'], 'POST', 'POST method');
assertEq(strpos($c->calls[0]['path'], '/auth/service-token/rotate') !== false, true, 'rotate path correct');
rmrf($tmp);

// =============================================================
// TEST 11 — rotate fail → return ok=false
// =============================================================
echo "=== Test 11: rotate fail → log error, không crash ===\n";
list($c, $tmp) = freshClient();
$payload = base64_encode(json_encode(array('exp' => time() + 3 * 86400, 'sub' => 'test')));
$fakeJwt = 'header.' . rtrim(strtr($payload, '+/', '-_'), '=') . '.sig';
$c->setJwtForTest($fakeJwt);

$c->script = array('__THROW__:Network err');
$r = $c->rotateTokenIfNeeded(5);
assertEq($r['ok'], false, 'rotate fail');
assertEq(isset($r['error']), true, 'có error msg');
rmrf($tmp);

// =============================================================
// SUMMARY
// =============================================================
echo "\n" . str_repeat('=', 50) . "\n";
echo "PASS: $pass\n";
echo "FAIL: $fail\n";
if ($fail > 0) {
    echo "\nFailures:\n";
    foreach ($failures as $f) echo "  - $f\n";
    exit(1);
}
echo "\n✅ ALL TESTS PASSED\n";
