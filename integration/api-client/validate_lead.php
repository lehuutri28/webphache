<?php
/**
 * validate_lead — chặn rác trước khi save DB phache
 *
 * Deploy target: /library/function/validate_lead.php (Mat Bao)
 * Compat: PHP 5.6+
 *
 * Letri chốt 2/5/2026: validation chặn SUBMIT khi rác (return error),
 * KHÔNG chặn BLUR (auto-fill form vẫn trigger được).
 */

require_once __DIR__ . '/normalize_phone.php';

if (!function_exists('validate_lead')) {
    /**
     * @param array $input Map field → value (raw từ $_POST)
     * @param array $opts ['phone_field','name_field','email_field','content_field']
     * @return array ['ok'=>bool, 'errors'?=>array, 'normalized'?=>array]
     */
    function validate_lead($input, $opts) {
        $errors = array();
        $normalized = array();

        $phoneField   = isset($opts['phone_field'])   ? $opts['phone_field']   : 'phone';
        $nameField    = isset($opts['name_field'])    ? $opts['name_field']    : 'name';
        $emailField   = isset($opts['email_field'])   ? $opts['email_field']   : null;
        $contentField = isset($opts['content_field']) ? $opts['content_field'] : null;

        // 1) Phone — bắt buộc, chuẩn VN
        $phoneRaw = isset($input[$phoneField]) ? trim((string)$input[$phoneField]) : '';
        if ($phoneRaw === '') {
            $errors[$phoneField] = 'Chưa nhập số điện thoại.';
        } else {
            $phone = normalize_phone_vn($phoneRaw);
            if ($phone === null) {
                $errors[$phoneField] = 'Số điện thoại không đúng chuẩn (10 số, đầu 03/05/07/08/09).';
            } else {
                $normalized[$phoneField] = $phone;
            }
        }

        // 2) Name — min 3 ký tự, max 60, ít nhất 2 từ, không toàn số/lặp
        $nameRaw = isset($input[$nameField]) ? trim((string)$input[$nameField]) : '';
        if ($nameRaw === '') {
            $errors[$nameField] = 'Chưa nhập họ tên.';
        } else {
            $name = preg_replace('/\s+/', ' ', $nameRaw);
            $len = mb_strlen($name, 'UTF-8');
            if ($len < 3 || $len > 60) {
                $errors[$nameField] = 'Họ tên phải từ 3 đến 60 ký tự.';
            } elseif (preg_match('/^\d+$/', $name)) {
                $errors[$nameField] = 'Họ tên không được toàn số.';
            } elseif (preg_match('/^(.)\1{4,}$/u', $name)) {
                $errors[$nameField] = 'Họ tên có vẻ là rác (ký tự lặp).';
            } elseif (substr_count($name, ' ') < 1) {
                $errors[$nameField] = 'Họ tên cần ít nhất 2 từ (họ + tên).';
            } else {
                $normalized[$nameField] = $name;
            }
        }

        // 3) Email — optional, nếu có thì phải chuẩn
        if ($emailField !== null) {
            $emailRaw = isset($input[$emailField]) ? trim((string)$input[$emailField]) : '';
            if ($emailRaw !== '') {
                if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
                    $errors[$emailField] = 'Email không đúng định dạng.';
                } else {
                    $normalized[$emailField] = strtolower($emailRaw);
                }
            }
        }

        // 4) Content/demand — chặn URL spam + quá dài
        if ($contentField !== null) {
            $contentRaw = isset($input[$contentField]) ? trim((string)$input[$contentField]) : '';
            if ($contentRaw !== '') {
                if (preg_match('/https?:\/\//i', $contentRaw)) {
                    $errors[$contentField] = 'Nội dung không được chứa URL.';
                } elseif (mb_strlen($contentRaw, 'UTF-8') > 2000) {
                    $errors[$contentField] = 'Nội dung quá dài (>2000 ký tự).';
                } else {
                    $normalized[$contentField] = $contentRaw;
                }
            }
        }

        if (!empty($errors)) {
            return array('ok' => false, 'errors' => $errors);
        }

        return array('ok' => true, 'normalized' => $normalized);
    }
}
