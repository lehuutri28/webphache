# A.4 — Wrap 3 save functions để push CRM

**KHÔNG apply trước 12/5/2026** (chờ pilot HCM 6/5 soak ổn). File này là instruction để worker apply khi tới ngày.

## Nguyên tắc

- Theo memory rule `feedback_no_delete_archive_only`: KHÔNG xoá code cũ
- Theo `feedback_hybrid_deploy_strategy`: CODE THÊM KHÔNG SỬA CŨ
- Wrap = thêm vài dòng SAU khi `insert*()` thành công, KHÔNG đụng logic validation/insert hiện tại
- Try/catch wrap toàn bộ — nếu CRM fail vẫn KHÔNG block save DB

## File apply

`/template/func/template.php` (Mat Bao path) = `legacy/source/template/func/template.php` (local)

## Patch 1 — `saveCallToAction()` (line 65-115)

**Trước**:
```php
            insertCallToAction($strFields, $strValues);
            sendMailSMTP('nhocnhn1@gmail.com','Tesst Admin', 'Tin khuyen mai tieu de', 'Tin khuyen mai noi dung');
            $_SESSION['cta_success'] = "Thông tin đăng ký bạn đã được gửi thành công";
            redirect($_SERVER['HTTP_REFERER']);
```

**Sau** (thêm block CRM push trước redirect):
```php
            insertCallToAction($strFields, $strValues);
            sendMailSMTP('nhocnhn1@gmail.com','Tesst Admin', 'Tin khuyen mai tieu de', 'Tin khuyen mai noi dung');

            // === A.4 wrap: push CRM Wecha (fire-and-forget, KHÔNG block) ===
            try {
                require_once(LIBRARY_DIR . 'function' . DIRECTORY_SEPARATOR . 'CrmClient.php');
                $crm = new CrmClient();
                $crm->pushLead([
                    'form_type'   => 'cta',
                    'phone'       => $arrInput['cta_phone'],
                    'name'        => $arrInput['cta_name'],
                    'course_name' => $arrInput['cta_course'],
                    'content'     => $arrInput['cta_purpose'],
                    'raw'         => $arrInput,
                ]);
            } catch (\Throwable $e) {
                error_log('[CRM push cta] ' . $e->getMessage());
            }
            // === /A.4 ===

            $_SESSION['cta_success'] = "Thông tin đăng ký bạn đã được gửi thành công";
            redirect($_SERVER['HTTP_REFERER']);
```

⚠️ Lưu ý: trong saveCallToAction code cũ `$arrInput` đã bị reset thành `array()` ở dòng 103 trước insertCallToAction. **PHẢI** giữ snapshot trước reset:

```php
        // Trước dòng "$arrInput  = array();"
        $snapshot = $arrInput; // snapshot for CRM push

        $arrInput  = array();
        insertCallToAction($strFields, $strValues);
        ...

        // Block CRM push dùng $snapshot thay vì $arrInput
        $crm->pushLead([
            'form_type'   => 'cta',
            'phone'       => $snapshot['cta_phone'],
            ...
        ]);
```

## Patch 2 — `saveAdvisory()` (line 117-176)

Tương tự, thêm sau `insertAdvisory($strFields, $strValues)` (line 168):

```php
        $snapshot = $arrInput; // ngay TRƯỚC dòng $arrInput = array();
        $arrInput  = array();
        insertAdvisory($strFields, $strValues);

        try {
            require_once(LIBRARY_DIR . 'function' . DIRECTORY_SEPARATOR . 'CrmClient.php');
            $crm = new CrmClient();
            $crm->pushLead([
                'form_type'   => 'advisory',
                'phone'       => $snapshot['advisory_number'],
                'name'        => $snapshot['advisory_name'],
                'email'       => $snapshot['advisory_email'],
                'course_name' => $snapshot['advisory_demand'],
                'content'     => $snapshot['advisory_content'],
                'raw'         => $snapshot,
            ]);
        } catch (\Throwable $e) {
            error_log('[CRM push advisory] ' . $e->getMessage());
        }

        $_SESSION['success'] = "Thông tin đăng ký bạn đã được gửi thành công";
        redirect($_SERVER['HTTP_REFERER']);
```

## Patch 3 — `saveSign()` (line 177-242)

Tương tự, thêm sau `insertSign($strFields, $strValues)` (line 233):

```php
            $snapshot = $arrInput;
            $arrInput  = array();
            insertSign($strFields, $strValues);

            try {
                require_once(LIBRARY_DIR . 'function' . DIRECTORY_SEPARATOR . 'CrmClient.php');
                $crm = new CrmClient();
                $crm->pushLead([
                    'form_type'   => 'sign',
                    'phone'       => $snapshot['sign_number'],
                    'name'        => $snapshot['sign_name'],
                    'email'       => $snapshot['sign_email'],
                    'address'     => $snapshot['sign_home'],
                    'course_name' => $snapshot['sign_day'],
                    'raw'         => $snapshot,
                ]);
            } catch (\Throwable $e) {
                error_log('[CRM push sign] ' . $e->getMessage());
            }
```

## Validation A.2 (apply ĐỒNG THỜI patch 1-3)

Trước mỗi `if (empty($error))` (block "not error") — chèn validate_lead check:

```php
require_once(LIBRARY_DIR . 'function' . DIRECTORY_SEPARATOR . 'validate_lead.php');
$v = validate_lead($arrInput, [
    'phone_field' => 'advisory_number',  // hoặc 'cta_phone' / 'sign_number'
    'name_field'  => 'advisory_name',
    'email_field' => 'advisory_email',
    'content_field' => 'advisory_content',
]);
if (!$v['ok']) {
    $error = array_merge($error, $v['errors']);
}
```

## Backup trước apply

```bash
# Local — backup file gốc trước khi sửa
cp legacy/source/template/func/template.php legacy/source/template/func/template.php.bak-2026-05-12
git add legacy/source/template/func/template.php.bak-2026-05-12
git commit -m "backup: template.php pre-A4 wrap"
```

## Deploy qua FTP cPanel

1. Upload file mới `library/function/CrmClient.php`, `normalize_phone.php`, `validate_lead.php`, `CrmRetryWorker.php` lên Mat Bao
2. Upload `template/api/customer-lookup.php`, `courses-list.php`
3. Upload `config/.env` (Letri tự gõ JWT, KHÔNG paste git)
4. Cuối cùng mới upload `template/func/template.php` đã patch
5. Test ngay với SĐT thật `0987654321`
