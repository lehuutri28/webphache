<?php
/**
 * Test cases logic normalize_phone + validate_lead
 *
 * Run local: php integration/api-client/tests/test_normalize_and_validate.php
 *
 * Compat: PHP 5.6+ (chạy được cả host PHP 8 lẫn server PHP 5.6)
 *
 * Đây là smoke test thuần logic — KHÔNG cần DB / network / ERP.
 */

require_once __DIR__ . '/../normalize_phone.php';
require_once __DIR__ . '/../validate_lead.php';

$pass = 0;
$fail = 0;
$failures = array();

function assertEq($actual, $expected, $name) {
    global $pass, $fail, $failures;
    if ($actual === $expected) {
        $pass++;
    } else {
        $fail++;
        $failures[] = "FAIL: $name — expected " . var_export($expected, true) . ", got " . var_export($actual, true);
    }
}

// =====================================================
// 1) normalize_phone_vn — tốt
// =====================================================
echo "=== normalize_phone_vn ===\n";
assertEq(normalize_phone_vn('0987654321'),       '0987654321',  'standard 10-digit');
assertEq(normalize_phone_vn('0905123456'),       '0905123456',  'prefix 09');
assertEq(normalize_phone_vn('0387654321'),       '0387654321',  'prefix 03');
assertEq(normalize_phone_vn('+84987654321'),     '0987654321',  '+84 → 0');
assertEq(normalize_phone_vn('84987654321'),      '0987654321',  '84 prefix → 0');
assertEq(normalize_phone_vn('084987654321'),     '0987654321',  '084 → 0');
assertEq(normalize_phone_vn('0987 654 321'),     '0987654321',  'space-separated');
assertEq(normalize_phone_vn('0987-654-321'),     '0987654321',  'dash-separated');
assertEq(normalize_phone_vn('(0987)654.321'),    '0987654321',  'parens + dot');

// =====================================================
// 2) normalize_phone_vn — rác (phải trả null)
// =====================================================
assertEq(normalize_phone_vn(''),               null, 'empty string');
assertEq(normalize_phone_vn(null),             null, 'null');
assertEq(normalize_phone_vn('123'),            null, 'too short');
assertEq(normalize_phone_vn('99999999999'),    null, 'wrong prefix 99 + 11 digits');
assertEq(normalize_phone_vn('0123456789'),     null, 'old prefix 01 (đã đổi sau 2018)');
assertEq(normalize_phone_vn('0287654321'),     null, 'prefix 02 (landline)');
assertEq(normalize_phone_vn('aaaaa'),          null, 'all letters');
assertEq(normalize_phone_vn('09876543210'),    null, '11 digits VN format old');
assertEq(normalize_phone_vn('+1 987 654 3210'), null, 'US number prefix');

// =====================================================
// 3) validate_lead — happy path
// =====================================================
echo "\n=== validate_lead — happy path ===\n";
$r = validate_lead(array(
    'cta_name'    => 'Nguyễn Văn A',
    'cta_phone'   => '0987654321',
    'cta_course'  => 'Pha chế trà sữa',
    'cta_purpose' => 'Mở quán'
), array(
    'phone_field' => 'cta_phone',
    'name_field'  => 'cta_name',
    'content_field' => 'cta_purpose'
));
assertEq($r['ok'], true, 'cta valid → ok=true');
assertEq($r['normalized']['cta_phone'], '0987654321', 'normalized phone');
assertEq($r['normalized']['cta_name'], 'Nguyễn Văn A', 'normalized name');

// Form sign + email + address
$r = validate_lead(array(
    'sign_name'   => 'Trần Thị Bình',
    'sign_email'  => 'BINH@Gmail.COM',
    'sign_number' => '+84 905 123 456'
), array(
    'phone_field' => 'sign_number',
    'name_field'  => 'sign_name',
    'email_field' => 'sign_email'
));
assertEq($r['ok'], true, 'sign valid → ok=true');
assertEq($r['normalized']['sign_number'], '0905123456', 'sign phone normalized');
assertEq($r['normalized']['sign_email'], 'binh@gmail.com', 'email lowercased');

// =====================================================
// 4) validate_lead — chặn rác (Letri ưu tiên)
// =====================================================
echo "\n=== validate_lead — reject rác ===\n";

// Phone rác
$r = validate_lead(
    array('cta_name' => 'Nguyễn A', 'cta_phone' => '123'),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name')
);
assertEq($r['ok'], false, 'phone "123" rác → reject');
assertEq(isset($r['errors']['cta_phone']), true, 'errors có phone field');

// Phone trống
$r = validate_lead(
    array('cta_name' => 'Nguyễn A', 'cta_phone' => ''),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name')
);
assertEq($r['ok'], false, 'phone empty → reject');

// Name 1 từ
$r = validate_lead(
    array('cta_name' => 'Anh', 'cta_phone' => '0987654321'),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name')
);
assertEq($r['ok'], false, 'name 1 từ → reject');

// Name toàn số
$r = validate_lead(
    array('cta_name' => '12345', 'cta_phone' => '0987654321'),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name')
);
assertEq($r['ok'], false, 'name toàn số → reject');

// Name lặp ký tự
$r = validate_lead(
    array('cta_name' => 'aaaaaaa', 'cta_phone' => '0987654321'),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name')
);
assertEq($r['ok'], false, 'name lặp ký tự → reject');

// URL spam trong content
$r = validate_lead(
    array('cta_name' => 'Nguyễn A', 'cta_phone' => '0987654321', 'cta_purpose' => 'check https://spam.xyz'),
    array('phone_field' => 'cta_phone', 'name_field' => 'cta_name', 'content_field' => 'cta_purpose')
);
assertEq($r['ok'], false, 'content có URL → reject');

// Email sai format
$r = validate_lead(
    array('sign_name' => 'Nguyễn A', 'sign_number' => '0987654321', 'sign_email' => 'not-an-email'),
    array('phone_field' => 'sign_number', 'name_field' => 'sign_name', 'email_field' => 'sign_email')
);
assertEq($r['ok'], false, 'email sai format → reject');

// =====================================================
// SUMMARY
// =====================================================
echo "\n";
echo str_repeat('=', 50) . "\n";
echo "PASS: $pass\n";
echo "FAIL: $fail\n";
if ($fail > 0) {
    echo "\nFailures:\n";
    foreach ($failures as $f) echo "  - $f\n";
    exit(1);
}
echo "\n✅ ALL TESTS PASSED\n";
