<?php
/**
 * normalize_phone — chuẩn hoá SĐT VN
 *
 * Deploy target: /library/function/normalize_phone.php (Mat Bao)
 * Compat: PHP 5.6+ (KHÔNG dùng type hints scalar / return type)
 *
 * Áp dụng tại:
 *   - validate_lead.php (chặn rác submit)
 *   - CrmClient.php (search ERP customers)
 *   - migrate-historical-leads.php (lọc 101k record cũ)
 *   - auto-fill.js → customer-lookup.php (fetch fill form)
 */

if (!function_exists('normalize_phone_vn')) {
    /**
     * @param string|null $raw
     * @return string|null Trả "0xxxxxxxxx" nếu hợp lệ, null nếu rác
     */
    function normalize_phone_vn($raw) {
        if ($raw === null) return null;
        $s = (string)$raw;

        $digits = preg_replace('/\D+/', '', $s);
        if ($digits === '' || $digits === null) return null;

        if (strlen($digits) >= 11 && substr($digits, 0, 2) === '84') {
            $digits = '0' . substr($digits, 2);
        }
        if (strlen($digits) === 12 && substr($digits, 0, 3) === '084') {
            $digits = '0' . substr($digits, 3);
        }

        if (preg_match('/^0(3|5|7|8|9)\d{8}$/', $digits)) {
            return $digits;
        }

        return null;
    }
}
