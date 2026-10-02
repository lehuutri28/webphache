<?php
/**
 * Trang Đăng Ký Tư Vấn Khóa Học Pha Chế — Passion Link
 * URL: https://phache.com.vn/dang-ky
 * Mục tiêu: Nhận lead Google Ads & đo lường conversion "Click Đăng ký - Website"
 * Giao diện tối ưu: Mobile · iPad / Tablet · Desktop PC / Laptop
 * Tương thích: PHP 5.6+ / LiteSpeed / MySQL
 */

// Khởi động session nếu chưa có
if (session_id() === '' && !headers_sent()) {
    @session_start();
}

// Xử lý gửi form (POST)
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
          || (isset($_POST['ajax']) && $_POST['ajax'] === '1');
$submitSuccess = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Chống Spam Bot: Honeypot ẩn (người thật không bao giờ điền)
    if (!empty($_POST['b_sec_token']) || !empty($_POST['dk_website'])) {
        if ($isAjax) {
            echo json_encode(array('success' => true, 'message' => 'Đăng ký thành công!'));
            exit;
        }
        header('Location: /dang-ky/?success=1');
        exit;
    }

    // 3. Tiếp nhận và làm sạch dữ liệu (PHP 5.6 tương thích)
    $name    = isset($_POST['dk_name'])    ? trim(strip_tags($_POST['dk_name'])) : '';
    $phone   = isset($_POST['dk_phone'])   ? trim(strip_tags($_POST['dk_phone'])) : '';
    $email   = isset($_POST['dk_email'])   ? trim(strip_tags($_POST['dk_email'])) : '';
    $branch  = isset($_POST['dk_branch'])  ? trim(strip_tags($_POST['dk_branch'])) : 'TP.HCM';
    $course  = isset($_POST['dk_course'])  ? trim(strip_tags($_POST['dk_course'])) : 'Pha chế tổng hợp';
    $purpose = isset($_POST['dk_purpose']) ? trim(strip_tags($_POST['dk_purpose'])) : 'Học mở quán';
    $note    = isset($_POST['dk_note'])    ? trim(strip_tags($_POST['dk_note'])) : '';

    // 4. Validate bắt buộc
    $digitsOnly = preg_replace('/\D/', '', $phone);

    if (empty($name)) {
        $errorMessage = 'Vui lòng nhập Họ và tên.';
    } elseif (empty($phone)) {
        $errorMessage = 'Vui lòng nhập Số điện thoại.';
    } elseif (strlen($digitsOnly) < 9 || strlen($digitsOnly) > 13) {
        $errorMessage = 'Số điện thoại không hợp lệ (cần 10 chữ số).';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Địa chỉ Email không đúng định dạng.';
    } else {
        // 5. Lưu vào Database (tái sử dụng kết nối CMS qua config/db.php)
        $dbConnected = false;
        $dbConfigFile = dirname(__DIR__) . '/config/db.php';
        if (file_exists($dbConfigFile)) {
            @include_once $dbConfigFile;
            if (isset($obMySQLi) && $obMySQLi instanceof mysqli && !$obMySQLi->connect_errno) {
                $dbConnected = true;
            }
        }

        // Nếu chưa có kết nối, thử khởi tạo trực tiếp
        if (!$dbConnected) {
            $testDb = @new mysqli('localhost', 'pha5bd2c_un', 'qF123kcGa1@!A', 'pha5bd2c_db');
            if (!$testDb->connect_errno) {
                $testDb->set_charset('utf8');
                $obMySQLi = $testDb;
                $dbConnected = true;
            }
        }

        if ($dbConnected) {
            @$obMySQLi->set_charset('utf8');

            // Chuẩn bị dữ liệu an toàn tránh SQL Injection
            $safeName    = $obMySQLi->real_escape_string($name);
            $safePhone   = $obMySQLi->real_escape_string($phone);
            $safeEmail   = $obMySQLi->real_escape_string($email);
            $safeBranch  = $obMySQLi->real_escape_string($branch);
            $safeCourse  = $obMySQLi->real_escape_string($course);
            $safePurpose = $obMySQLi->real_escape_string($purpose);
            $safeNote    = $obMySQLi->real_escape_string($note);
            $now         = date('Y-m-d H:i:s');

            // 5a. Lưu vào bảng call_to_action (hiển thị tại Admin CMS mục Call To Action / Gọi hành động)
            $ctaCourseDesc = $safeCourse . ' · ' . $safeBranch;
            if (!empty($safeNote)) {
                $ctaCourseDesc .= ' (Ghi chú: ' . $safeNote . ')';
            }
            $sqlCta = "INSERT INTO `call_to_action` (`cta_name`, `cta_phone`, `cta_course`, `cta_purpose`, `cta_create`, `cta_status`) 
                       VALUES ('{$safeName}', '{$safePhone}', '{$ctaCourseDesc}', '{$safePurpose}', '{$now}', 0)";
            $obMySQLi->query($sqlCta);

            // 5b. Lưu vào bảng form_sign (hiển thị tại Admin CMS mục Đăng ký học)
            $signEmailVal = !empty($safeEmail) ? $safeEmail : 'dangky-web@phache.com.vn';
            $signCourseDay = $safeCourse . ' · ' . $safePurpose;
            if (!empty($safeNote)) {
                $signCourseDay .= ' · ' . $safeNote;
            }
            if (function_exists('mb_substr')) {
                $signDaySafe = mb_substr($signCourseDay, 0, 50, 'UTF-8');
            } else {
                $signDaySafe = substr($signCourseDay, 0, 50);
            }
            $safeSignDay = $obMySQLi->real_escape_string($signDaySafe);
            $sqlSign = "INSERT INTO `form_sign` (`sign_name`, `sign_email`, `sign_number`, `sign_birthday`, `sign_home`, `sign_day`, `viewed`, `sign_status`, `sign_created`) 
                        VALUES ('{$safeName}', '{$signEmailVal}', '{$safePhone}', '{$now}', '{$safeBranch}', '{$safeSignDay}', 0, 0, '{$now}')";
            $obMySQLi->query($sqlSign);

            // 5c. Lưu vào bảng form_advisory (hiển thị tại Admin CMS mục Tư vấn khách hàng)
            $advisoryContent = "Khóa học: {$safeCourse} | Chi nhánh: {$safeBranch} | Nhu cầu: {$safePurpose}";
            if (!empty($safeNote)) {
                $advisoryContent .= " | Ghi chú: {$safeNote}";
            }
            $safeAdvContent = $obMySQLi->real_escape_string($advisoryContent);
            $sqlAdv = "INSERT INTO `form_advisory` (`advisory_name`, `advisory_email`, `advisory_number`, `advisory_demand`, `advisory_content`, `viewed`, `advisory_status`, `advisory_created`) 
                       VALUES ('{$safeName}', '{$signEmailVal}', '{$safePhone}', '{$safePurpose}', '{$safeAdvContent}', 0, 0, '{$now}')";
            $obMySQLi->query($sqlAdv);
        }

        // Ghi log dự phòng cục bộ
        $logDir = dirname(__DIR__) . '/data';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logLine = sprintf("[%s] Name: %s | Phone: %s | Email: %s | Branch: %s | Course: %s | Purpose: %s | Note: %s\n",
            date('Y-m-d H:i:s'), $name, $phone, $email, $branch, $course, $purpose, $note);
        @file_put_contents($logDir . '/leads_dangky_' . date('Y-m') . '.log', $logLine, FILE_APPEND);

        $submitSuccess = true;
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        if ($submitSuccess) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Đăng ký tư vấn thành công! Passion Link sẽ liên hệ bạn sớm nhất.'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => $errorMessage
            ));
        }
        exit;
    }

    if ($submitSuccess) {
        header('Location: /dang-ky/?success=1');
        exit;
    }
}

// Kiểm tra query parameter ?success=1
$isSuccessView = (isset($_GET['success']) && $_GET['success'] === '1');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Google tag (gtag.js) - Google Ads & GA4 Chuẩn Kép -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16775247010"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      // 1. Cấu hình Thẻ Google Ads (Có bật Chuyển đổi nâng cao & Tự động liên kết)
      gtag('config', 'AW-16775247010', {
        'allow_enhanced_conversions': true,
        'send_page_view': true
      });

      // 2. Cấu hình Google Analytics 4 đồng bộ
      gtag('config', 'G-5QT1MTZHXT', {
        'send_page_view': true
      });
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-K9XSTVD');</script>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Đăng ký tư vấn khóa học pha chế | Passion Link</title>
    <meta name="description" content="Đăng ký tư vấn khóa học pha chế trà sữa, cà phê, tổng hợp mở quán tại Passion Link. 17 năm kinh nghiệm, 4 chi nhánh toàn quốc.">
    <meta name="keywords" content="đăng ký học pha chế, học pha chế mở quán, khóa học pha chế trà sữa, học barista cà phê, Passion Link">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://phache.com.vn/dang-ky">
    <link rel="icon" href="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" type="image/png">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://phache.com.vn/dang-ky">
    <meta property="og:title" content="Đăng ký tư vấn khóa học pha chế | Passion Link">
    <meta property="og:description" content="Đăng ký tư vấn khóa học pha chế trà sữa, cà phê, tổng hợp mở quán tại Passion Link. 17 năm kinh nghiệm, 4 chi nhánh toàn quốc.">
    <meta property="og:image" content="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png">

    <!-- Google Font Quicksand đồng bộ -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Schema.org Course -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "Khóa Học Pha Chế Đồ Uống Mở Quán Chuyên Nghiệp",
      "description": "Đào tạo kỹ năng pha chế thực chiến trà sữa, cà phê barista, trà trái cây, đồ uống hiện đại mở quán cùng Passion Link.",
      "provider": {
        "@type": "EducationalOrganization",
        "name": "Passion Link",
        "url": "https://phache.com.vn/",
        "logo": "https://phache.com.vn/khoa-tong-hop/logo-passionlink.png",
        "telephone": "0333 033 444",
        "sameAs": "https://zalo.me/3708608045322625044"
      }
    }
    </script>

    <style>
        :root {
            --green: #1FA84B;
            --green-dark: #1F3F1F;
            --green-deep: #164016;
            --green-mid: #2E5C2E;
            --gold: #D4A373;
            --gold-light: #FFD54F;
            --gold-warm: #FFE082;
            --text-main: #1F2419;
            --text-muted: #4A5742;
            --bg-gradient: linear-gradient(135deg, #f0f7eb 0%, #e8f4df 35%, #f5f9f0 70%, #eef5e6 100%);
            --font-main: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: var(--font-main);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg-gradient);
            background-attachment: fixed;
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER NAV */
        .header-nav {
            padding: 14px 24px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(31, 168, 75, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: contain;
            background: #fff;
            padding: 3px;
            box-shadow: 0 4px 14px rgba(31, 168, 75, 0.18);
        }

        .brand-text h1 {
            font-size: 19px;
            font-weight: 800;
            color: var(--green-dark);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .brand-text p {
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .header-contact {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hotline-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            background: linear-gradient(135deg, #1FA84B, #2E8B3A);
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(31, 168, 75, 0.28);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hotline-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(31, 168, 75, 0.38);
        }

        /* MAIN CONTAINER (DESKTOP & TABLET TWO-COLUMN) */
        .main-wrapper {
            flex: 1;
            max-width: 1240px;
            width: 100%;
            margin: 32px auto 60px;
            padding: 0 24px;
        }

        .split-layout {
            display: grid;
            grid-template-columns: 1.05fr 1.2fr;
            gap: 44px;
            align-items: start;
        }

        /* CỘT TRÁI: THUYẾT PHỤC & THÔNG TIN UY TÍN */
        .info-col {
            padding-top: 10px;
        }

        .eyebrow-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 18px;
            background: linear-gradient(135deg, #FFE082, #FFA726);
            color: #1F2419;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-radius: 50px;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(255, 167, 38, 0.25);
        }

        .page-headline {
            font-size: clamp(28px, 3.2vw, 40px);
            font-weight: 800;
            color: var(--green-dark);
            line-height: 1.22;
            margin-bottom: 14px;
            letter-spacing: -0.015em;
        }

        .page-subhead {
            font-size: 16.5px;
            color: var(--text-muted);
            margin-bottom: 24px;
            font-weight: 500;
            line-height: 1.6;
        }

        /* Thẻ chỉ số uy tín (Grid 3 thẻ) */
        .trust-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .trust-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(31, 168, 75, 0.20);
            border-radius: 16px;
            padding: 14px 12px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(31, 168, 75, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .trust-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(31, 168, 75, 0.16);
        }

        .trust-card .icon {
            font-size: 24px;
            margin-bottom: 4px;
            display: block;
        }

        .trust-card strong {
            display: block;
            font-size: 17px;
            font-weight: 800;
            color: var(--green);
            line-height: 1.2;
        }

        .trust-card span {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-muted);
            line-height: 1.3;
            display: block;
            margin-top: 2px;
        }

        /* Danh sách ưu đãi & quyền lợi học viên */
        .benefits-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.82) 0%, rgba(244, 250, 242, 0.86) 100%);
            border: 1.5px solid rgba(31, 168, 75, 0.22);
            border-radius: 20px;
            padding: 22px 20px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(31, 168, 75, 0.06);
        }

        .benefits-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .benefits-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-main);
            line-height: 1.5;
        }

        .benefit-icon {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(31, 168, 75, 0.15);
            color: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            margin-top: 1px;
        }

        .benefit-item strong {
            color: var(--green-dark);
        }

        /* Chi nhánh liên hệ nhanh */
        .branch-quick-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .branch-pill {
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(31, 168, 75, 0.18);
            border-radius: 12px;
            padding: 11px 13px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(31, 168, 75, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .branch-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(31, 168, 75, 0.10);
        }

        .branch-pill strong {
            color: var(--green-dark);
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .branch-pill span {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.45;
            margin-bottom: 5px;
        }

        .branch-hotline {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--green);
            margin-top: auto;
        }

        .branch-hotline a {
            color: var(--green);
            text-decoration: none;
            font-weight: 700;
        }

        .branch-hotline a:hover {
            color: var(--green-dark);
            text-decoration: underline;
        }

        /* CỘT PHẢI: FORM ĐĂNG KÝ CRYSTAL GLASS */
        .form-col {
            position: relative;
        }

        .glass-card {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.96) 0%, rgba(248, 252, 246, 0.92) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 2px solid rgba(31, 168, 75, 0.28);
            border-radius: 28px;
            padding: 36px 32px;
            box-shadow: 0 24px 64px rgba(31, 168, 75, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        .form-header {
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1.5px dashed rgba(31, 168, 75, 0.22);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .form-title {
            font-size: 21px;
            font-weight: 800;
            color: var(--green-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-req-hint {
            font-size: 12.5px;
            color: #C62828;
            font-weight: 600;
        }

        .form-row {
            margin-bottom: 18px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 6px;
        }

        .form-label span.req {
            color: #E53935;
            font-weight: 800;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            color: #1F2419;
            background: rgba(255, 255, 255, 0.96);
            border: 1.5px solid rgba(31, 168, 75, 0.28);
            border-radius: 12px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus, .form-textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3.5px rgba(31, 168, 75, 0.16);
            background: #fff;
        }

        .form-input::placeholder, .form-textarea::placeholder {
            color: #8C9985;
            font-size: 14px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* OPTION CHIPS GRID TỐI ƯU IPAD & DESKTOP */
        .chips-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 8px;
            margin-top: 4px;
        }

        .chips-course-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .chips-branch-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .chips-purpose-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .chip-option {
            cursor: pointer;
            display: block;
        }

        .chip-option input[type="radio"] {
            display: none;
        }

        .chip-box {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 9px 10px;
            background: rgba(255, 255, 255, 0.90);
            border: 1.5px solid rgba(31, 168, 75, 0.25);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.2s ease;
            user-select: none;
            text-align: center;
            min-height: 42px;
        }

        .chip-option:hover .chip-box {
            border-color: var(--green);
            background: #F4FBF4;
        }

        .chip-option input[type="radio"]:checked + .chip-box {
            background: linear-gradient(135deg, rgba(31, 168, 75, 0.14), rgba(46, 140, 58, 0.22));
            border-color: var(--green);
            color: var(--green-dark);
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(31, 168, 75, 0.15);
        }

        .alert-box {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: #FFEBEE;
            border: 1px solid #EF9A9A;
            color: #C62828;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1FA84B 0%, #298934 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 16.5px;
            font-weight: 800;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(31, 168, 75, 0.38);
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(31, 168, 75, 0.46);
            filter: brightness(1.05);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .form-privacy-note {
            text-align: center;
            font-size: 12.5px;
            color: #6D7B65;
            margin-top: 14px;
            line-height: 1.45;
        }

        /* SUCCESS CARD VIEW */
        .success-card {
            text-align: center;
            padding: 40px 24px;
        }

        .success-icon {
            font-size: 64px;
            line-height: 1;
            margin-bottom: 18px;
            animation: bounceIn 0.6s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 14px;
        }

        .success-desc {
            font-size: 16px;
            color: var(--text-muted);
            max-width: 520px;
            margin: 0 auto 26px;
            line-height: 1.6;
        }

        .success-cta-row {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 13px 26px;
            background: #fff;
            border: 1.5px solid var(--green);
            color: var(--green);
            font-weight: 700;
            font-size: 14.5px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-home:hover {
            background: var(--green);
            color: #fff;
        }

        .btn-zalo-direct {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 13px 26px;
            background: linear-gradient(135deg, #0068FF, #0084FF);
            color: #fff;
            font-weight: 700;
            font-size: 14.5px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 104, 255, 0.35);
        }

        .btn-zalo-direct:hover {
            filter: brightness(1.1);
        }

        /* POPUP MODAL THÀNH CÔNG */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 25, 10, 0.72);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100000;
            padding: 20px;
            animation: fadeInModal 0.25s ease-out;
        }

        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-dialog {
            max-width: 560px;
            width: 100%;
            margin: auto;
        }

        .glass-modal {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(246, 252, 244, 0.96) 100%);
            border: 2px solid rgba(31, 168, 75, 0.40);
            border-radius: 26px;
            padding: 40px 32px 34px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
            text-align: center;
            animation: zoomInModal 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.25);
        }

        @keyframes zoomInModal {
            from { transform: scale(0.85); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close-btn {
            position: absolute;
            top: 16px;
            right: 18px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.06);
            border: none;
            font-size: 22px;
            color: #4A5742;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: background 0.2s, color 0.2s;
        }

        .modal-close-btn:hover {
            background: rgba(229, 57, 53, 0.12);
            color: #E53935;
        }

        .modal-icon {
            font-size: 64px;
            line-height: 1;
            margin-bottom: 16px;
            display: block;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .modal-body-text {
            font-size: 16px;
            line-height: 1.65;
            color: #2D3A27;
            margin-bottom: 26px;
            font-weight: 600;
        }

        .modal-body-text strong {
            color: #0068FF;
            background: rgba(0, 104, 255, 0.10);
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 800;
        }

        .modal-btn-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-modal-zalo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 15px 20px;
            background: linear-gradient(135deg, #0068FF, #0084FF);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0, 104, 255, 0.35);
            transition: transform 0.2s, filter 0.2s;
        }

        .btn-modal-zalo:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
        }

        .btn-modal-dismiss {
            padding: 11px 20px;
            background: rgba(0, 0, 0, 0.05);
            border: 1.5px solid rgba(0, 0, 0, 0.10);
            border-radius: 12px;
            color: #5A6B52;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-modal-dismiss:hover {
            background: rgba(0, 0, 0, 0.10);
            color: #1F2419;
        }

        /* FOOTER */
        footer {
            background: rgba(31, 63, 31, 0.96);
            color: rgba(255, 255, 255, 0.85);
            padding: 36px 24px 28px;
            text-align: center;
            font-size: 13.5px;
            line-height: 1.7;
            margin-top: auto;
        }

        footer strong {
            color: var(--gold-light);
        }

        footer a {
            color: #7FFFA0;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .footer-branches {
            max-width: 1000px;
            margin: 16px auto 18px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 13px;
            color: rgba(255, 255, 255, 0.80);
            text-align: left;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        /* FLOATING ZALO */
        .floating-zalo {
            position: fixed;
            bottom: 28px;
            right: 24px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #0068FF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0, 104, 255, 0.45);
            z-index: 999;
            transition: transform 0.25s;
            text-decoration: none;
        }

        .floating-zalo:hover {
            transform: scale(1.1);
        }

        .floating-zalo img, .floating-zalo svg {
            width: 32px;
            height: 32px;
        }

        /* ============================================================
           RESPONSIVE: TỐI ƯU CHO IPAD / TABLET (768px – 1024px)
           ============================================================ */
        @media (min-width: 768px) and (max-width: 1024px) {
            .main-wrapper {
                padding: 0 28px;
                margin: 24px auto 48px;
            }
            .split-layout {
                grid-template-columns: 1fr;
                gap: 32px;
            }
            .info-col {
                text-align: center;
            }
            .page-headline {
                font-size: 32px;
            }
            .page-subhead {
                margin: 0 auto 20px;
                max-width: 640px;
            }
            .trust-grid {
                max-width: 640px;
                margin: 0 auto 22px;
            }
            .benefits-card {
                max-width: 640px;
                margin: 0 auto 22px;
                text-align: left;
            }
            .branch-quick-list {
                max-width: 640px;
                margin: 0 auto 12px;
                text-align: left;
            }
            .glass-card {
                padding: 32px 28px;
            }
            .footer-branches {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ============================================================
           RESPONSIVE: TỐI ƯU CHO MOBILE (< 768px)
           ============================================================ */
        @media (max-width: 767px) {
            .main-wrapper {
                margin: 16px auto 36px;
                padding: 0 16px;
            }
            .split-layout {
                grid-template-columns: 1fr;
                gap: 22px;
            }
            .info-col {
                text-align: center;
                padding-top: 0;
            }
            .page-headline {
                font-size: 24px;
            }
            .page-subhead {
                font-size: 14.5px;
                margin-bottom: 16px;
            }
            .trust-grid {
                grid-template-columns: 1fr;
                gap: 8px;
                margin-bottom: 18px;
            }
            .trust-card {
                padding: 10px 14px;
                display: flex;
                align-items: center;
                gap: 12px;
                text-align: left;
            }
            .trust-card .icon {
                margin-bottom: 0;
                font-size: 22px;
            }
            .trust-card strong {
                font-size: 15px;
            }
            .benefits-card {
                padding: 16px 14px;
                margin-bottom: 18px;
            }
            .benefit-item {
                font-size: 12.5px;
            }
            .branch-quick-list {
                display: none; /* Thu gọn trên mobile để form nổi bật ngay màn đầu */
            }
            .glass-card {
                padding: 24px 16px;
                border-radius: 22px;
            }
            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .chips-course-grid, .chips-purpose-grid {
                grid-template-columns: 1fr 1fr;
            }
            .chips-branch-grid {
                grid-template-columns: 1fr 1fr;
            }
            .chip-box {
                font-size: 12.5px;
                padding: 8px 6px;
            }
            .btn-submit {
                font-size: 15px;
                padding: 14px;
            }
            .header-contact .hotline-btn {
                padding: 7px 12px;
                font-size: 12px;
            }
            .brand-text h1 {
                font-size: 16px;
            }
            .footer-branches {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K9XSTVD"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Header / Brand Nav -->
    <header class="header-nav">
        <div class="header-container">
            <a href="https://phache.com.vn/" class="brand-logo" title="Trang chủ Passion Link">
                <img src="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" alt="Logo Passion Link">
                <div class="brand-text">
                    <h1>PASSION LINK</h1>
                    <p>Đào Tạo Pha Chế Chuyên Nghiệp Mở Quán</p>
                </div>
            </a>
            <div class="header-contact">
                <a href="tel:0333033444" class="hotline-btn" title="Gọi tư vấn trực tiếp">
                    📞 <span class="hotline-num">0333.033.444</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-wrapper">
        <div class="split-layout">
            
            <!-- CỘT TRÁI: GIỚI THIỆU & THÔNG TIN UY TÍN (NỔI BẬT TRÊN PC & IPAD) -->
            <section class="info-col">
                <div class="eyebrow-pill">⭐ Khóa Học Pha Chế Mở Quán Thực Chiến</div>
                <h2 class="page-headline">Đăng ký tư vấn khóa học pha chế</h2>
                <p class="page-subhead">
                    Passion Link sẽ liên hệ tư vấn khóa học phù hợp theo nhu cầu và ngân sách của bạn. Đồng hành từ công thức chuẩn vị đến chiến lược kinh doanh mở quán thành công.
                </p>

                <!-- 3 Thẻ chỉ số uy tín -->
                <div class="trust-grid">
                    <div class="trust-card">
                        <span class="icon">🏆</span>
                        <div>
                            <strong>17 Năm</strong>
                            <span>Kinh nghiệm đào tạo (từ 2009)</span>
                        </div>
                    </div>
                    <div class="trust-card">
                        <span class="icon">👥</span>
                        <div>
                            <strong>3.000+</strong>
                            <span>Chủ quán thành công toàn quốc</span>
                        </div>
                    </div>
                    <div class="trust-card">
                        <span class="icon">📍</span>
                        <div>
                            <strong>4 Chi Nhánh</strong>
                            <span>TP.HCM · Hà Nội · ĐN · Cần Thơ</span>
                        </div>
                    </div>
                </div>

                <!-- Thẻ Quyền Lợi Học Viên -->
                <div class="benefits-card">
                    <div class="benefits-title">
                        <span>✨</span>
                        <span>Đặc quyền khi học tại Passion Link</span>
                    </div>
                    <ul class="benefits-list">
                        <li class="benefit-item">
                            <span class="benefit-icon">✓</span>
                            <span><strong>Thực hành 90%</strong>: Làm chủ công thức ngay trên máy móc và thiết bị hiện đại chuẩn quán.</span>
                        </li>
                        <li class="benefit-item">
                            <span class="benefit-icon">✓</span>
                            <span><strong>Bộ công thức chuẩn vị</strong>: Trà sữa Đài Loan, Trà trái cây hiện đại, Cà phê pha máy &amp; truyền thống, Đá xay, Bingsu.</span>
                        </li>
                        <li class="benefit-item">
                            <span class="benefit-icon">✓</span>
                            <span><strong>Tư vấn mở quán chuyên sâu</strong>: Hướng dẫn tính cost giá vốn, định giá menu và bí quyết vận hành sinh lời.</span>
                        </li>
                        <li class="benefit-item">
                            <span class="benefit-icon">✓</span>
                            <span><strong>Cập nhật menu trọn đời</strong>: Học viên được cập nhật các món hot trend mới hoàn toàn miễn phí.</span>
                        </li>
                    </ul>
                </div>

                <!-- 4 Chi nhánh toàn quốc -->
                <div class="branch-quick-list">
                    <div class="branch-pill">
                        <strong>📍 Hà Nội</strong>
                        <span>102 Ngõ 194 Giải Phóng (Sát 192 Giải Phóng), P.Phương Liệt, TP.Hà Nội</span>
                        <div class="branch-hotline">📞 <a href="tel:0909800676">0909.800.676</a></div>
                    </div>
                    <div class="branch-pill">
                        <strong>📍 Đà Nẵng</strong>
                        <span>104 Lý Thái Tông, P.Thanh Khê, TP. Đà Nẵng</span>
                        <div class="branch-hotline">📞 <a href="tel:0908006557">0908 006 557</a></div>
                    </div>
                    <div class="branch-pill">
                        <strong>📍 Hồ Chí Minh</strong>
                        <span>07 Nguyễn Đức Thuận, P.Tân Bình, TP.HCM</span>
                        <div class="branch-hotline">📞 <a href="tel:0333033444">0333.033.444</a></div>
                    </div>
                    <div class="branch-pill">
                        <strong>📍 Cần Thơ</strong>
                        <span>65 Nguyễn Đệ, P.Cái Khế, TP. Cần Thơ</span>
                        <div class="branch-hotline">📞 <a href="tel:0795905508">079 590 5508</a></div>
                    </div>
                </div>
            </section>

            <!-- CỘT PHẢI: FORM ĐĂNG KÝ NỔI BẬT -->
            <section class="form-col">
                <div class="glass-card">
                    <!-- Màn hình cảm ơn khi đã submit thành công -->
                    <div id="success-panel" class="success-card" style="<?php echo $isSuccessView ? 'display:block;' : 'display:none;'; ?>">
                        <div class="success-icon">🎉</div>
                        <h3 class="success-title">Đăng ký tư vấn thành công!</h3>
                        <p class="success-desc" style="font-weight: 600; color: #2D3A27; line-height: 1.65;">
                            Anh/Chị đã đăng ký thành công! Cám ơn Anh/Chị đã đăng ký tư vấn tại Passion Link! Chúng em sẽ tư vấn sớm nhất cho Anh/Chị nếu anh chị cần gấp vui lòng kết bạn zalo số: <strong style="color:#0068FF; background:rgba(0,104,255,0.1); padding:2px 8px; border-radius:6px;">0333033444</strong> và gửi tin nhắn cho em nhé!
                        </p>
                        <div class="success-cta-row">
                            <a href="https://zalo.me/0333033444" target="_blank" rel="noopener" class="btn-zalo-direct">
                                💬 Nhắn tin / Kết bạn Zalo ngay
                            </a>
                            <a href="https://phache.com.vn/" class="btn-home">
                                🏠 Về trang chủ
                            </a>
                        </div>
                    </div>

                    <!-- Form điền thông tin -->
                    <div id="form-panel" style="<?php echo $isSuccessView ? 'display:none;' : 'display:block;'; ?>">
                        <div class="form-header">
                            <div class="form-title">
                                <span>📋</span>
                                <span>Thông tin đăng ký tư vấn</span>
                            </div>
                            <div class="form-req-hint">(*) Trường bắt buộc</div>
                        </div>

                        <div id="client-alert" class="alert-box alert-error" style="display:none;"></div>
                        <?php if (!empty($errorMessage)): ?>
                            <div class="alert-box alert-error">⚠️ <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>

                        <form id="dangky-form" method="post" action="/dang-ky/" novalidate>
                            <!-- Chống Spam Bot ẩn (không bị autofill can thiệp) -->
                            <input type="text" name="b_sec_token" value="" tabindex="-1" autocomplete="new-password" aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;opacity:0;pointer-events:none;">

                            <!-- Họ tên & Số điện thoại (2 cột trên Desktop & iPad) -->
                            <div class="form-grid-2">
                                <div class="form-row">
                                    <label class="form-label" for="dk_name">Họ và tên <span class="req">(*)</span></label>
                                    <input type="text" id="dk_name" name="dk_name" class="form-input" placeholder="Ví dụ: Nguyễn Văn An" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="dk_phone">Số điện thoại <span class="req">(*)</span></label>
                                    <input type="tel" id="dk_phone" name="dk_phone" class="form-input" placeholder="Ví dụ: 0901234567" required maxlength="15">
                                </div>
                            </div>

                            <!-- Email (không bắt buộc) -->
                            <div class="form-row">
                                <label class="form-label" for="dk_email">Email <span style="font-weight:normal;color:#7A8772;">(không bắt buộc)</span></label>
                                <input type="email" id="dk_email" name="dk_email" class="form-input" placeholder="example@gmail.com">
                            </div>

                            <!-- Khu vực / Chi nhánh quan tâm -->
                            <div class="form-row">
                                <label class="form-label">Khu vực / Chi nhánh quan tâm</label>
                                <div class="chips-container chips-branch-grid">
                                    <label class="chip-option">
                                        <input type="radio" name="dk_branch" value="TP.HCM" checked>
                                        <span class="chip-box">TP.HCM</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_branch" value="Hà Nội">
                                        <span class="chip-box">Hà Nội</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_branch" value="Đà Nẵng">
                                        <span class="chip-box">Đà Nẵng</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_branch" value="Cần Thơ">
                                        <span class="chip-box">Cần Thơ</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Khóa học quan tâm -->
                            <div class="form-row">
                                <label class="form-label">Khóa học bạn đang quan tâm</label>
                                <div class="chips-container chips-course-grid">
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Pha chế tổng hợp" checked>
                                        <span class="chip-box">Pha chế tổng hợp</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Trà sữa">
                                        <span class="chip-box">Trà sữa mở quán</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Cà phê">
                                        <span class="chip-box">Cà phê - Barista</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Trà trái cây">
                                        <span class="chip-box">Trà trái cây hiện đại</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Làm kem">
                                        <span class="chip-box">Làm kem &amp; Bingsu</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_course" value="Khác">
                                        <span class="chip-box">Khóa học khác</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Nhu cầu học -->
                            <div class="form-row">
                                <label class="form-label">Nhu cầu học của bạn</label>
                                <div class="chips-container chips-purpose-grid">
                                    <label class="chip-option">
                                        <input type="radio" name="dk_purpose" value="Học mở quán" checked>
                                        <span class="chip-box">Học mở quán kinh doanh</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_purpose" value="Học đi làm">
                                        <span class="chip-box">Học đi làm nghề</span>
                                    </label>
                                    <label class="chip-option">
                                        <input type="radio" name="dk_purpose" value="Học vì đam mê">
                                        <span class="chip-box">Học vì đam mê</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Ghi chú thêm (không bắt buộc) -->
                            <div class="form-row">
                                <label class="form-label" for="dk_note">Ghi chú thêm <span style="font-weight:normal;color:#7A8772;">(không bắt buộc)</span></label>
                                <textarea id="dk_note" name="dk_note" class="form-textarea" placeholder="Ví dụ: Cần tư vấn mở quán trà sữa vốn 50 triệu, học ca tối hoặc cuối tuần..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="btn-submit" class="btn-submit">
                                <span id="btn-text">🚀 GỬI ĐĂNG KÝ TƯ VẤN NGAY</span>
                            </button>

                            <p class="form-privacy-note">
                                🔒 Thông tin của bạn được bảo mật tuyệt đối. Passion Link cam kết hỗ trợ tận tâm.
                            </p>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- POPUP MODAL THÀNH CÔNG -->
    <div id="successModal" class="modal-overlay" style="<?php echo $isSuccessView ? 'display:flex;' : 'display:none;'; ?>" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-dialog">
            <div class="glass-modal">
                <button type="button" class="modal-close-btn" onclick="closeSuccessModal()" aria-label="Đóng">&times;</button>
                <div class="modal-icon">🎉</div>
                <h3 id="modalTitle" class="modal-title">Đăng ký thành công!</h3>
                <p class="modal-body-text">
                    Anh/Chị đã đăng ký thành công! Cám ơn Anh/Chị đã đăng ký tư vấn tại Passion Link! Chúng em sẽ tư vấn sớm nhất cho Anh/Chị nếu anh chị cần gấp vui lòng kết bạn zalo số: <strong>0333033444</strong> và gửi tin nhắn cho em nhé!
                </p>
                <div class="modal-btn-row">
                    <a href="https://zalo.me/0333033444" target="_blank" rel="noopener" class="btn-modal-zalo">
                        💬 Nhắn tin / Kết bạn Zalo ngay
                    </a>
                    <button type="button" class="btn-modal-dismiss" onclick="closeSuccessModal()">
                        Đóng thông báo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2009–2026 <strong>Passion Link</strong> — Trung tâm đào tạo pha chế hàng đầu Việt Nam.</p>
        <div class="footer-branches">
            <div>
                📍 <strong>Hà Nội:</strong><br>
                102 Ngõ 194 Giải Phóng (Sát 192 Giải Phóng), P.Phương Liệt, TP.Hà Nội<br>
                Hotline: <a href="tel:0909800676">0909.800.676</a>
            </div>
            <div>
                📍 <strong>Đà Nẵng:</strong><br>
                104 Lý Thái Tông, P.Thanh Khê, TP. Đà Nẵng<br>
                Hotline: <a href="tel:0908006557">0908 006 557</a>
            </div>
            <div>
                📍 <strong>Hồ Chí Minh:</strong><br>
                07 Nguyễn Đức Thuận, P.Tân Bình, TP.HCM<br>
                Hotline: <a href="tel:0333033444">0333.033.444</a>
            </div>
            <div>
                📍 <strong>Cần Thơ:</strong><br>
                65 Nguyễn Đệ, P.Cái Khế, TP. Cần Thơ<br>
                Hotline: <a href="tel:0795905508">079 590 5508</a>
            </div>
        </div>
        <p>Hotline tư vấn: <a href="tel:0333033444">0333.033.444</a> (HCM) · <a href="tel:0909800676">0909.800.676</a> (HN) · <a href="tel:0908006557">0908 006 557</a> (ĐN) · <a href="tel:0795905508">079 590 5508</a> (CT) &nbsp;|&nbsp; Website: <a href="https://phache.com.vn">phache.com.vn</a></p>
    </footer>

    <!-- Zalo Floating Icon -->
    <a href="https://zalo.me/3708608045322625044" target="_blank" rel="noopener" class="floating-zalo" title="Chat Zalo tư vấn">
        <svg width="32" height="32" viewBox="0 0 48 48" fill="none">
            <path fill="#fff" d="M24 4C12.95 4 4 12.51 4 23.01c0 5.48 2.45 10.42 6.43 13.98l-1.63 6.01a1 1 0 001.25 1.22l6.73-2.61c2.26.83 4.69 1.3 7.22 1.3 11.05 0 20-8.51 20-19.01S35.05 4 24 4z"/>
            <path fill="#0068FF" d="M14 26l7-9h-7v-3h11v3l-7 9h7v3H14v-3zm13-12h4v15h-4V14zm7 0h4v15h-4V14z"/>
        </svg>
    </a>

    <!-- JavaScript Xử lý Form & Google Ads Tracking -->
    <script>
    function openSuccessModal() {
        var modal = document.getElementById('successModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSuccessModal() {
        var modal = document.getElementById('successModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Đóng popup khi click ngoài hộp thoại hoặc nhấn ESC
    window.addEventListener('click', function(e) {
        var modal = document.getElementById('successModal');
        if (e.target === modal) {
            closeSuccessModal();
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeSuccessModal();
        }
    });

    (function() {
        var form = document.getElementById('dangky-form');
        var formPanel = document.getElementById('form-panel');
        var successPanel = document.getElementById('success-panel');
        var alertBox = document.getElementById('client-alert');
        var btnSubmit = document.getElementById('btn-submit');
        var btnText = document.getElementById('btn-text');

        // Hàm bắn Tracking Google Ads Conversion & dataLayer
        function triggerLeadTracking() {
            try {
                // 1. dataLayer Event
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    event: 'lead_form_submit',
                    form_name: 'dang_ky_khoa_hoc',
                    page_path: '/dang-ky'
                });

                // 2. Google tag Conversion
                if (typeof gtag === 'function') {
                    gtag('event', 'conversion', {
                        'send_to': 'AW-16775247010/7qOlCOeDroodEKLph78-',
                        'value': 500000.0,
                        'currency': 'VND'
                    });
                }
            } catch (err) {
                console.error('Tracking trigger error:', err);
            }
        }

        // Nếu truy cập trực tiếp có ?success=1 từ redirect GET
        <?php if ($isSuccessView): ?>
        openSuccessModal();
        triggerLeadTracking();
        <?php endif; ?>

        function showAlert(msg) {
            alertBox.textContent = '⚠️ ' + msg;
            alertBox.style.display = 'block';
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function hideAlert() {
            alertBox.style.display = 'none';
            alertBox.textContent = '';
        }

        // Xử lý submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            hideAlert();

            var nameInput = document.getElementById('dk_name');
            var phoneInput = document.getElementById('dk_phone');
            var emailInput = document.getElementById('dk_email');

            var name = (nameInput ? nameInput.value : '').trim();
            var phone = (phoneInput ? phoneInput.value : '').trim();
            var email = (emailInput ? emailInput.value : '').trim();

            // 1. Validation cơ bản: Không gửi event nếu lỗi
            if (!name) {
                showAlert('Vui lòng nhập Họ và tên.');
                if (nameInput) nameInput.focus();
                return false;
            }

            if (!phone) {
                showAlert('Vui lòng nhập Số điện thoại để chuyên viên liên hệ tư vấn.');
                if (phoneInput) phoneInput.focus();
                return false;
            }

            var cleanDigits = phone.replace(/\D/g, '');
            // Kiểm tra số điện thoại Việt Nam (10 số, bắt đầu 0) hoặc quốc tế (9-13 số)
            if (cleanDigits.length < 9 || cleanDigits.length > 13) {
                showAlert('Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 số (VD: 0901234567).');
                if (phoneInput) phoneInput.focus();
                return false;
            }

            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showAlert('Địa chỉ email không đúng định dạng.');
                if (emailInput) emailInput.focus();
                return false;
            }

            // 2. Loading State
            btnSubmit.disabled = true;
            btnText.textContent = '⏳ Đang gửi thông tin...';

            // 3. Chuẩn bị dữ liệu gửi AJAX
            var formData = new FormData(form);
            formData.append('ajax', '1');

            var postUrl = '/dang-ky/';

            function onLeadSuccess() {
                // Cập nhật trạng thái giao diện sang Thank You Card
                if (formPanel) formPanel.style.display = 'none';
                if (successPanel) successPanel.style.display = 'block';

                // HIỂN THỊ POPUP MODAL THÀNH CÔNG
                openSuccessModal();

                // Cập nhật URL trình duyệt sang /dang-ky/?success=1 (không reload trang)
                if (window.history && window.history.pushState) {
                    window.history.pushState({ success: 1 }, '', '/dang-ky/?success=1');
                }

                // BẮN SỰ KIỆN CHUYỂN ĐỔI GOOGLE ADS (Chỉ sau khi thành công thật)
                triggerLeadTracking();

                // Cuộn nhẹ lên đầu card
                if (successPanel) {
                    successPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            function sendViaXHR() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', postUrl, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            try {
                                var data = JSON.parse(xhr.responseText);
                                if (data && data.success) {
                                    onLeadSuccess();
                                    return;
                                } else {
                                    btnSubmit.disabled = false;
                                    btnText.textContent = '🚀 GỬI ĐĂNG KÝ TƯ VẤN NGAY';
                                    showAlert((data && data.message) ? data.message : 'Có lỗi xảy ra, vui lòng thử lại.');
                                    return;
                                }
                            } catch (parseErr) {
                                console.warn('JSON parse error in XHR, doing native submit');
                            }
                        }
                        fallbackNativeSubmit();
                    }
                };
                xhr.onerror = function() {
                    fallbackNativeSubmit();
                };
                xhr.send(formData);
            }

            function fallbackNativeSubmit() {
                btnSubmit.disabled = false;
                btnText.textContent = '🚀 GỬI ĐĂNG KÝ TƯ VẤN NGAY';
                form.action = '/dang-ky/';
                form.submit();
            }

            // Gửi dữ liệu qua fetch (ưu tiên) hoặc XHR
            if (window.fetch) {
                fetch(postUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(function(res) {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status);
                    }
                    return res.json();
                })
                .then(function(data) {
                    if (data && data.success) {
                        onLeadSuccess();
                    } else {
                        btnSubmit.disabled = false;
                        btnText.textContent = '🚀 GỬI ĐĂNG KÝ TƯ VẤN NGAY';
                        showAlert((data && data.message) ? data.message : 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                })
                .catch(function(err) {
                    console.warn('Fetch failed, trying XHR fallback:', err);
                    sendViaXHR();
                });
            } else {
                sendViaXHR();
            }
        });
    })();
    </script>
</body>
</html>
