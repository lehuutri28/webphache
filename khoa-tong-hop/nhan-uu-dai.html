<?php
/**
 * Trang Đích Riêng Biệt: Đăng Ký Nhận Ưu Đãi & Bộ Quà Tặng 25 Triệu — Passion Link
 * URL: https://phache.com.vn/nhan-uu-dai/
 * Tối ưu CRO 100%: Tải siêu tốc, Form trên màn hình đầu tiên, Tương thích Mobile/Tablet/Desktop.
 * Chuẩn Tracking Kép: Google Analytics 4 (G-5QT1MTZHXT) & Google Ads (AW-16775247010).
 */

// Khởi động session nếu cần
if (session_id() === '' && !headers_sent()) {
    @session_start();
}

// Xử lý gửi Form (POST)
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
          || (isset($_POST['ajax']) && $_POST['ajax'] === '1');
$submitSuccess = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Chống Spam Bot: Honeypot ẩn
    if (!empty($_POST['b_sec_token']) || !empty($_POST['dk_website'])) {
        if ($isAjax) {
            echo json_encode(array('success' => true, 'message' => 'Đăng ký nhận ưu đãi thành công!'));
            exit;
        }
        header('Location: /nhan-uu-dai/?success=1');
        exit;
    }

    $name    = isset($_POST['ff_name'])    ? trim(strip_tags($_POST['ff_name'])) : '';
    $phone   = isset($_POST['ff_phone'])   ? trim(strip_tags($_POST['ff_phone'])) : '';
    $package = isset($_POST['ff_package']) ? trim(strip_tags($_POST['ff_package'])) : 'advanced';
    $branch  = isset($_POST['ff_branch'])  ? trim(strip_tags($_POST['ff_branch'])) : 'hcm';
    $month   = isset($_POST['ff_month'])   ? trim(strip_tags($_POST['ff_month'])) : '';
    $slot    = isset($_POST['ff_slot'])    ? trim(strip_tags($_POST['ff_slot'])) : 'flexible';

    $digitsOnly = preg_replace('/\D/', '', $phone);

    if (empty($name)) {
        $errorMessage = 'Vui lòng nhập Họ và tên.';
    } elseif (empty($phone)) {
        $errorMessage = 'Vui lòng nhập Số điện thoại.';
    } elseif (strlen($digitsOnly) < 9 || strlen($digitsOnly) > 13) {
        $errorMessage = 'Số điện thoại không hợp lệ (cần đủ 10 chữ số).';
    } else {
        // Ghi log dự phòng
        $logDir = dirname(__DIR__) . '/data';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logLine = sprintf("[%s] Name: %s | Phone: %s | Package: %s | Branch: %s | Month: %s | Slot: %s\n",
            date('Y-m-d H:i:s'), $name, $phone, $package, $branch, $month, $slot);
        @file_put_contents($logDir . '/leads_nhanuudai_' . date('Y-m') . '.log', $logLine, FILE_APPEND);

        $submitSuccess = true;
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        if ($submitSuccess) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Đăng ký nhận ưu đãi thành công! Chuyên viên Passion Link sẽ liên hệ bạn trong 15 phút.'
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
        header('Location: /nhan-uu-dai/?success=1');
        exit;
    }
}

$isSuccessView = (isset($_GET['success']) && $_GET['success'] === '1');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title>Đăng Ký Nhận Ưu Đãi 40% & Bộ Quà 25 Triệu | Học Viện Pha Chế Passion Link</title>
  <meta name="description" content="Đăng ký nhận ngay ưu đãi 40% học phí và trọn bộ quà tặng 25.000.000đ (App POS + HRM + Tài liệu CEO 4.0) khóa học pha chế mở quán tại Passion Link.">
  <meta name="keywords" content="đăng ký học pha chế, nhận ưu đãi pha chế, học pha chế trà sữa, học pha chế cà phê, học pha chế tổng hợp mở quán, Passion Link">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://phache.com.vn/nhan-uu-dai/">
  <link rel="icon" href="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" type="image/png">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://phache.com.vn/nhan-uu-dai/">
  <meta property="og:title" content="Đăng Ký Nhận Ưu Đãi 40% & Bộ Quà 25 Triệu | Passion Link">
  <meta property="og:description" content="Ưu đãi giới hạn khóa học pha chế mở quán thực chiến cùng Chuyên gia Lê Hữu Trí. Giữ chỗ chỉ 1 triệu.">
  <meta property="og:image" content="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png">

  <!-- Google Font Quicksand -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- GOOGLE TAG CHUẨN KÉP GA4 & GOOGLE ADS -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-5QT1MTZHXT"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-5QT1MTZHXT', { 'send_page_view': true });
    gtag('config', 'AW-16775247010');
  </script>

  <!-- Schema.org Course Offer -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Course",
    "name": "Khóa Học Pha Chế Tổng Hợp Cao Cấp Mở Quán",
    "description": "Khoá học pha chế thực chiến mở quán cà phê trà sữa hàng đầu Việt Nam. 17 năm — 3000+ chủ quán đã chọn.",
    "provider": {
      "@type": "EducationalOrganization",
      "name": "Passion Link",
      "url": "https://phache.com.vn",
      "logo": "https://phache.com.vn/khoa-tong-hop/logo-passionlink.png",
      "telephone": "0977300098"
    },
    "offers": {
      "@type": "Offer",
      "price": "6000000",
      "priceCurrency": "VND",
      "availability": "https://schema.org/InStock",
      "url": "https://phache.com.vn/nhan-uu-dai/"
    }
  }
  </script>

  <style>
    :root {
      --bg-green-deep: #0f2e14;
      --bg-green-main: #143d1a;
      --bg-green-card: rgba(25, 59, 29, 0.78);
      --green-brand: #1FA84B;
      --green-bright: #2ecc71;
      --gold-accent: #FFD54F;
      --gold-warm: #FFA726;
      --orange-lead: #FF6B35;
      --red-lead: #E53935;
      --text-white: #ffffff;
      --text-muted: rgba(255, 255, 255, 0.78);
      --border-glass: rgba(255, 255, 255, 0.16);
      --border-green: rgba(46, 204, 113, 0.35);
      --font-main: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: var(--font-main);
    }

    body {
      background: radial-gradient(circle at 50% 20%, #1e5225 0%, #0d2611 60%, #061509 100%);
      color: var(--text-white);
      line-height: 1.6;
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
      padding-bottom: 72px;
    }

    /* HEADER */
    .lead-header {
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
      border-bottom: 1px solid var(--border-glass);
    }
    .lead-brand {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: var(--text-white);
    }
    .lead-logo {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: #ffffff;
      padding: 4px;
      object-fit: contain;
      box-shadow: 0 4px 14px rgba(0,0,0,0.3);
    }
    .lead-brand-name {
      font-size: 19px;
      font-weight: 800;
      letter-spacing: -0.02em;
      color: #ffffff;
      line-height: 1.2;
    }
    .lead-brand-tag {
      font-size: 11.5px;
      color: var(--gold-accent);
      font-weight: 600;
    }
    .lead-header-hotline {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid var(--border-glass);
      color: #ffffff;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 50px;
      font-size: 13.5px;
      font-weight: 700;
      transition: all 0.2s ease;
    }
    .lead-header-hotline:hover {
      background: rgba(255, 255, 255, 0.22);
      transform: translateY(-1px);
    }

    /* MAIN CONTAINER */
    .lead-container {
      max-width: 1160px;
      margin: 28px auto 0;
      padding: 0 20px;
    }

    /* HEADLINE SECTION */
    .lead-hero-intro {
      text-align: center;
      margin-bottom: 28px;
    }
    .lead-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(135deg, #FFE082, #FFA726);
      color: #1F2419;
      font-size: 12.5px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      padding: 6px 18px;
      border-radius: 50px;
      margin-bottom: 12px;
      box-shadow: 0 4px 14px rgba(255, 167, 38, 0.35);
    }
    .lead-h1 {
      font-size: clamp(24px, 4vw, 36px);
      font-weight: 800;
      line-height: 1.25;
      margin-bottom: 10px;
      letter-spacing: -0.02em;
      color: #ffffff;
    }
    .lead-h1 span.hl {
      color: var(--gold-accent);
    }
    .lead-sub {
      font-size: clamp(14px, 2vw, 16px);
      color: var(--text-muted);
      max-width: 760px;
      margin: 0 auto;
      font-weight: 500;
    }

    /* 2-COLUMN GRID (FORM + SUMMARY CARD) */
    .lead-grid {
      display: grid;
      grid-template-columns: 1.35fr 0.95fr;
      gap: 28px;
      align-items: start;
    }

    /* LEFT: FORM CARD */
    .lead-card-form {
      background: var(--bg-green-card);
      border: 1.5px solid var(--border-green);
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }
    .form-group-custom {
      margin-bottom: 16px;
    }
    .form-group-custom label {
      display: block;
      font-size: 13.5px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #ffffff;
    }
    .form-group-custom input,
    .form-group-custom select {
      width: 100%;
      height: 48px;
      padding: 0 16px;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      background: rgba(18, 48, 22, 0.85);
      color: #ffffff;
      font-size: 14.5px;
      font-weight: 600;
      outline: none;
      transition: all 0.2s ease;
      box-sizing: border-box;
    }
    .form-group-custom select option {
      background: #164016;
      color: #ffffff;
      padding: 10px;
    }
    .form-group-custom input:focus,
    .form-group-custom select:focus {
      border-color: var(--green-bright);
      box-shadow: 0 0 12px rgba(46, 204, 113, 0.4);
      background: rgba(22, 60, 28, 0.95);
    }
    .form-hint {
      font-size: 11.5px;
      color: rgba(255, 255, 255, 0.65);
      margin-top: 4px;
    }

    /* PACKAGE SUMMARY BOX INSIDE FORM */
    .lead-pkg-summary {
      background: rgba(10, 32, 13, 0.7);
      border: 1px dashed rgba(255, 213, 79, 0.5);
      border-radius: 12px;
      padding: 14px 18px;
      margin-bottom: 20px;
    }
    .lead-pkg-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 6px;
      font-size: 13.5px;
    }
    .lead-pkg-row strong {
      color: var(--gold-accent);
      font-size: 15px;
    }
    .lead-pkg-bonus {
      font-size: 12.5px;
      color: #a8e6cf;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 6px;
      margin-top: 6px;
    }

    /* CTA SUBMIT BUTTON */
    .lead-submit-btn {
      width: 100%;
      height: 54px;
      background: linear-gradient(135deg, #FF6B35 0%, #E53935 100%);
      color: #ffffff;
      border: none;
      border-radius: 12px;
      font-size: 15.5px;
      font-weight: 800;
      letter-spacing: 0.02em;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(229, 57, 53, 0.45);
      transition: all 0.2s ease;
      animation: leadPulse 2s infinite ease-in-out;
    }
    .lead-submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(229, 57, 53, 0.65);
    }
    .lead-submit-btn:active {
      transform: scale(0.98);
    }
    @keyframes leadPulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 14px rgba(229, 57, 53, 0.4);
      }
      50% {
        transform: scale(1.02);
        box-shadow: 0 8px 26px rgba(229, 57, 53, 0.7);
      }
    }
    .lead-form-security {
      text-align: center;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 12px;
    }

    /* RIGHT: OFFER & FOMO CARD */
    .lead-card-summary {
      background: rgba(30, 70, 36, 0.6);
      border: 1.5px solid rgba(255, 213, 79, 0.4);
      border-radius: 20px;
      padding: 26px 24px;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
      position: sticky;
      top: 24px;
    }
    .card-price-header {
      display: flex;
      align-items: baseline;
      gap: 12px;
      margin-bottom: 8px;
    }
    .card-price-current {
      font-size: 32px;
      font-weight: 800;
      color: var(--gold-accent);
      line-height: 1;
    }
    .card-price-old {
      font-size: 16px;
      color: rgba(255, 255, 255, 0.55);
      text-decoration: line-through;
    }
    .card-badge-discount {
      background: #E53935;
      color: #ffffff;
      font-size: 12px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 6px;
    }
    .card-slot-warning {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #FFE082;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 16px;
    }

    /* COUNTDOWN */
    .card-countdown {
      background: rgba(255, 255, 255, 0.94);
      border-radius: 12px;
      padding: 12px 14px;
      color: #1F2419;
      text-align: center;
      margin-bottom: 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    }
    .countdown-title {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #b71c1c;
      margin-bottom: 6px;
    }
    .countdown-boxes {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 6px;
    }
    .cd-box {
      background: #f4f6f0;
      border-radius: 6px;
      padding: 4px 6px;
      min-width: 44px;
    }
    .cd-val {
      font-size: 18px;
      font-weight: 800;
      color: #1b3d1f;
      line-height: 1.1;
    }
    .cd-lbl {
      font-size: 9.5px;
      color: #666;
      font-weight: 600;
      text-transform: uppercase;
    }
    .cd-colon {
      font-weight: 800;
      font-size: 16px;
      color: #b71c1c;
    }

    .card-deposit-info {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 12.5px;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 16px;
      border-left: 3px solid var(--gold-accent);
    }

    /* QUICK ACTIONS */
    .btn-quick-deposit {
      width: 100%;
      height: 44px;
      background: var(--green-brand);
      color: #ffffff;
      border: none;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-bottom: 10px;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn-quick-deposit:hover {
      background: #178a3d;
    }
    .btn-quick-zalo {
      width: 100%;
      height: 44px;
      background: #0068FF;
      color: #ffffff;
      border: none;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      text-decoration: none;
      margin-bottom: 18px;
      transition: all 0.2s ease;
    }
    .btn-quick-zalo:hover {
      background: #0052cc;
    }

    /* TRUST BULLETS */
    .card-trust-list {
      list-style: none;
      border-top: 1px solid rgba(255, 255, 255, 0.12);
      padding-top: 14px;
    }
    .card-trust-list li {
      font-size: 12.5px;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .trust-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--green-bright);
      flex-shrink: 0;
    }

    /* BONUS GIFTS ROW */
    .lead-bonus-section {
      margin-top: 40px;
      background: rgba(18, 45, 22, 0.7);
      border: 1px solid var(--border-green);
      border-radius: 16px;
      padding: 24px;
      backdrop-filter: blur(12px);
    }
    .lead-bonus-title {
      font-size: 18px;
      font-weight: 800;
      color: var(--gold-accent);
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .bonus-cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 16px;
    }
    .bonus-gift-card {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 16px;
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }
    .bonus-icon {
      font-size: 28px;
      line-height: 1;
    }
    .bonus-info-title {
      font-size: 14px;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 2px;
    }
    .bonus-info-price {
      font-size: 12px;
      color: var(--gold-accent);
      font-weight: 800;
      margin-bottom: 4px;
    }
    .bonus-info-desc {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.65);
      line-height: 1.35;
    }

    /* SUCCESS MODAL POPUP */
    .lead-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.82);
      backdrop-filter: blur(8px);
      z-index: 999999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .lead-modal-card {
      background: #ffffff;
      color: #1F2419;
      max-width: 480px;
      width: 100%;
      border-radius: 20px;
      padding: 32px 24px;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
      animation: modalPop 0.3s ease-out;
    }
    @keyframes modalPop {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .lead-modal-icon {
      font-size: 54px;
      margin-bottom: 12px;
      line-height: 1;
    }
    .lead-modal-title {
      font-size: 22px;
      font-weight: 800;
      color: #1b3d1f;
      margin-bottom: 10px;
    }
    .lead-modal-desc {
      font-size: 14.5px;
      color: #4A5742;
      margin-bottom: 22px;
      line-height: 1.55;
    }
    .lead-modal-btns {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .lead-modal-btn-zalo {
      height: 48px;
      background: #0068FF;
      color: #ffffff;
      border-radius: 12px;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 14.5px;
    }
    .lead-modal-btn-home {
      height: 44px;
      background: #eef2eb;
      color: #2e5c2e;
      border-radius: 12px;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 13.5px;
    }

    /* FOOTER */
    .lead-footer {
      max-width: 1160px;
      margin: 40px auto 20px;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.55);
      border-top: 1px solid var(--border-glass);
    }
    .lead-footer a {
      color: var(--gold-accent);
      text-decoration: none;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .lead-grid {
        grid-template-columns: 1fr;
      }
      .lead-card-summary {
        position: static;
        order: -1; /* Hiện tóm tắt ưu đãi phía trên trên mobile */
        margin-bottom: 12px;
      }
    }
    @media (max-width: 480px) {
      .lead-card-form {
        padding: 20px 16px;
      }
      .lead-card-summary {
        padding: 20px 16px;
      }
      .card-price-current {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="lead-header">
    <a href="https://phache.com.vn/" class="lead-brand" title="Trang chủ Passion Link">
      <img src="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" alt="Passion Link Logo" class="lead-logo" width="48" height="48">
      <div>
        <div class="lead-brand-name">Passion Link</div>
        <div class="lead-brand-tag">Học Viện Đào Tạo Pha Chế Mở Quán Hàng Đầu</div>
      </div>
    </a>
    <a href="tel:0977300098" class="lead-header-hotline" title="Gọi Hotline tư vấn ngay" onclick="if(typeof plTrackConversion==='function'){plTrackConversion('click_call_hotline','header_hotline',50000);}">
      <span aria-hidden="true">📞</span>
      <span>0977.300.098</span>
    </a>
  </header>

  <!-- MAIN WRAPPER -->
  <main class="lead-container">
    <div class="lead-hero-intro">
      <div class="lead-eyebrow">🔥 DUY NHẤT HÔM NAY · GIỚI HẠN 10 SUẤT HỌC BỔNG</div>
      <h1 class="lead-h1">Đăng Ký Nhận <span class="hl">Ưu Đãi 40%</span> & Trọn Bộ Quà <span class="hl">25.000.000₫</span></h1>
      <p class="lead-sub">Cam kết đào tạo thực chiến 90% cùng Giảng viên 1 kèm 1 · Tặng ngay phần mềm POS 10Tr, HRM 10Tr & Tài liệu CEO 4.0 5Tr khi cọc 1 triệu giữ chỗ.</p>
    </div>

    <div class="lead-grid">
      <!-- FORM CARD CHÍNH -->
      <section class="lead-card-form" aria-label="Khung đăng ký tư vấn nhận ưu đãi">
        <form id="leadCaptureForm" onsubmit="return handleLeadSubmit(event);">
          <!-- Honeypot chống spam bot -->
          <input type="text" name="dk_website" style="display:none;" tabindex="-1" autocomplete="off">

          <div class="form-group-custom">
            <label for="ff_name">Họ và tên của bạn *</label>
            <input type="text" id="ff_name" name="ff_name" placeholder="Ví dụ: Nguyễn Văn A" required autofocus>
          </div>

          <div class="form-group-custom">
            <label for="ff_phone">Số điện thoại / Zalo *</label>
            <input type="tel" id="ff_phone" name="ff_phone" placeholder="Ví dụ: 0901234567 hoặc +84..." pattern="^(0\d{9}|\+\d{10,15})$" inputmode="tel" maxlength="16" autocomplete="tel" required>
            <div class="form-hint">📞 Nhập đủ 10 số để nhận lịch học & mã kích hoạt ưu đãi qua Zalo.</div>
          </div>

          <div class="form-group-custom">
            <label for="ff_package">Khoá học bạn đang quan tâm</label>
            <select id="ff_package" name="ff_package" onchange="updateSelectedPackage()">
              <option value="advanced" selected>Tổng Hợp Cao Cấp — 6M · ~92 món · 4 ngày (Ưu đãi 40%)</option>
              <option value="pro">Tổng Hợp Chuyên Nghiệp — 8.88M · 100+ món · 6 ngày (Bán chạy nhất)</option>
              <option value="premium">Tổng Hợp Thương Hiệu — 25M · Coaching 1-1 CEO</option>
              <option value="trasua">Khóa Học Trà Sữa Chuẩn Vị Đài Loan & Topping Độc Quyền</option>
              <option value="barista">Khóa Học Barista Cà Phê Máy Chuyên Nghiệp Mở Quán</option>
              <option value="traicay">Khóa Học Trà Trái Cây Hiện Đại & Trà Sữa Mùa Hè</option>
              <option value="consult">Tôi cần chuyên gia Passion Link tư vấn chọn khóa</option>
            </select>
          </div>

          <div class="form-group-custom">
            <label for="ff_branch">Hình thức & Chi nhánh học</label>
            <select id="ff_branch" name="ff_branch">
              <option value="hcm" selected>📍 TP.HCM: 07 Nguyễn Đức Thuận, P.13, Q.Tân Bình</option>
              <option value="hn">📍 Hà Nội: 102 Ngõ 194 Giải Phóng, P.Phương Liệt</option>
              <option value="ct">📍 Cần Thơ: 65 Nguyễn Đệ, P.An Hòa, Q.Ninh Kiều</option>
              <option value="dn">📍 Đà Nẵng: 104 Lý Thái Tông, P.Thanh Khê Tây</option>
              <option value="online">💻 Học Online 1-1 trực tiếp qua Zoom với Giảng viên</option>
            </select>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <div class="form-group-custom">
              <label for="ff_month">Tháng học mong muốn *</label>
              <select id="ff_month" name="ff_month" required>
                <option value="2026-10" selected>Tháng 10/2026</option>
                <option value="2026-11">Tháng 11/2026</option>
                <option value="2026-12">Tháng 12/2026</option>
                <option value="2027-01">Tháng 01/2027</option>
                <option value="2027-02">Tháng 02/2027</option>
              </select>
            </div>

            <div class="form-group-custom">
              <label for="ff_slot">Ca học mong muốn *</label>
              <select id="ff_slot" name="ff_slot" required>
                <option value="flexible" selected>⏰ Linh hoạt (Gợi ý)</option>
                <option value="morning">🌅 Sáng (8h-11h)</option>
                <option value="afternoon">🌞 Chiều (13h30-17h)</option>
                <option value="evening">🌙 Tối (18h-21h)</option>
              </select>
            </div>
          </div>

          <!-- SUMMARY BOX -->
          <div class="lead-pkg-summary" id="pkgSummaryBox">
            <div class="lead-pkg-row">
              <span>Học phí ưu đãi hôm nay:</span>
              <strong id="sumPrice">6.000.000 VNĐ</strong>
            </div>
            <div class="lead-pkg-row" style="color:rgba(255,255,255,0.7);font-size:12.5px;">
              <span>Giá gốc niêm yết:</span>
              <del id="sumOldPrice">10.000.000 VNĐ</del>
            </div>
            <div class="lead-pkg-bonus">
              🎁 <strong>Tặng trọn bộ quà 25.000.000đ:</strong> App POS (10tr) + App HRM (10tr) + Tài liệu CEO (5tr).
            </div>
          </div>

          <button type="submit" id="submitLeadBtn" class="lead-submit-btn">
            <span>🎁 NHẬN TƯ VẤN + BỘ QUÀ TẶNG 25 TRIỆU NGAY →</span>
          </button>

          <div class="lead-form-security">
            🔒 Cam kết bảo mật thông tin · Không spam · Chuyên viên gọi lại trong 15 phút.
          </div>
        </form>
      </section>

      <!-- RIGHT: SUMMARY & FOMO CARD (Y HỆT MẪU ẢNH NGƯỜI DÙNG GỬI) -->
      <aside class="lead-card-summary">
        <div class="card-price-header">
          <div class="card-price-current" id="asidePrice">6.000.000đ</div>
          <div class="card-price-old" id="asideOldPrice">10.000.000đ</div>
          <div class="card-badge-discount" id="asideDiscount">-40%</div>
        </div>

        <div class="card-slot-warning">
          <span style="font-size:16px;">🔥</span>
          <span>Lịch linh động · Cọc 1 triệu giữ chỗ</span>
        </div>

        <!-- COUNTDOWN BOX -->
        <div class="card-countdown">
          <div class="countdown-title">⏳ Ưu đãi kết thúc sau</div>
          <div class="countdown-boxes">
            <div class="cd-box"><div class="cd-val" id="cdDays">02</div><div class="cd-lbl">Ngày</div></div>
            <div class="cd-colon">:</div>
            <div class="cd-box"><div class="cd-val" id="cdHours">14</div><div class="cd-lbl">Giờ</div></div>
            <div class="cd-colon">:</div>
            <div class="cd-box"><div class="cd-val" id="cdMins">35</div><div class="cd-lbl">Phút</div></div>
            <div class="cd-colon">:</div>
            <div class="cd-box"><div class="cd-val" id="cdSecs">20</div><div class="cd-lbl">Giây</div></div>
          </div>
        </div>

        <div class="card-deposit-info">
          💡 <strong>Cọc 1 triệu giữ chỗ</strong> học cùng chuyên gia — tặng bộ quà đặc biệt 25.000.000đ.
        </div>

        <button type="button" class="btn-quick-deposit" onclick="focusToNameInput()">
          <span>🎓 Đăng ký — Cọc 1 Triệu Giữ Chỗ</span>
        </button>

        <a href="https://zalo.me/0977300098" target="_blank" rel="noopener noreferrer" class="btn-quick-zalo" onclick="if(typeof plTrackConversion==='function'){plTrackConversion('click_chat_zalo','aside_card',50000);}">
          <span aria-hidden="true">💬</span>
          <span>Chat Zalo Tư Vấn Ngay</span>
        </a>

        <!-- TRUST LIST -->
        <ul class="card-trust-list">
          <li><span class="trust-dot"></span> Hỗ trợ suốt đời sau tốt nghiệp</li>
          <li><span class="trust-dot"></span> Chứng chỉ hoàn thành được cấp</li>
          <li><span class="trust-dot"></span> Tặng quà khi đăng ký không điều kiện</li>
          <li><span class="trust-dot"></span> 17 năm · 3000+ quán thành công</li>
        </ul>
      </aside>
    </div>

    <!-- CHI TIẾT BỘ QUÀ TẶNG 25 TRIỆU -->
    <section class="lead-bonus-section" aria-label="Chi tiết bộ quà tặng 25 triệu">
      <div class="lead-bonus-title">
        <span>🎁</span>
        <span>Bộ 3 Quà Tặng Độc Quyền Trị Giá 25.000.000₫ Dành Riêng Cho Bạn:</span>
      </div>
      <div class="bonus-cards-grid">
        <div class="bonus-gift-card">
          <div class="bonus-icon">📊</div>
          <div>
            <div class="bonus-info-title">Ứng dụng Quản Lý Bán Hàng POS</div>
            <div class="bonus-info-price">Trị giá: 10.000.000₫</div>
            <div class="bonus-info-desc">Order món, in bill tự động, kiểm soát dòng tiền và doanh thu thời gian thực trên điện thoại.</div>
          </div>
        </div>

        <div class="bonus-gift-card">
          <div class="bonus-icon">👥</div>
          <div>
            <div class="bonus-info-title">Ứng dụng Quản Lý Nhân Sự HRM</div>
            <div class="bonus-info-price">Trị giá: 10.000.000₫</div>
            <div class="bonus-info-desc">Chấm công GPS, xếp ca linh hoạt, tính lương và đánh giá KPI nhân viên quầy bar chuyên nghiệp.</div>
          </div>
        </div>

        <div class="bonus-gift-card">
          <div class="bonus-icon">📚</div>
          <div>
            <div class="bonus-info-title">Bộ Tài Liệu CEO Vận Hành 4.0</div>
            <div class="bonus-info-price">Trị giá: 5.000.000₫</div>
            <div class="bonus-info-desc">Cẩm nang quản trị menu, tối ưu chi phí cost 30%, chiến lược marketing kéo khách ngày khai trương.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="lead-footer">
      <p>© 2026 <strong>Học Viện Đào Tạo Pha Chế Passion Link</strong> — Đơn vị chủ quản: CÔNG TY TNHH VUA AN TOÀN.</p>
      <p style="margin-top:4px;">Hotline: <a href="tel:0977300098">0977.300.098</a> · Email: <a href="mailto:phache.com.vn@gmail.com">phache.com.vn@gmail.com</a></p>
      <p style="margin-top:6px;"><a href="https://phache.com.vn/">Về Trang Chủ phache.com.vn</a> · <a href="https://phache.com.vn/he-thong-chi-nhanh.html">Hệ Thống Chi Nhánh Toàn Quốc</a></p>
    </footer>
  </main>

  <!-- SUCCESS POPUP MODAL -->
  <div id="successModal" class="lead-modal-overlay" style="display:none;" role="dialog" aria-modal="true">
    <div class="lead-modal-card">
      <div class="lead-modal-icon">🎉</div>
      <h3 class="lead-modal-title">Đăng Ký Thành Công!</h3>
      <p class="lead-modal-desc">
        Cảm ơn bạn <strong id="successName">bạn</strong> đã đăng ký nhận ưu đãi.<br>
        Chuyên viên đào tạo Passion Link sẽ liên hệ với bạn trong vòng <strong>15 phút</strong> qua số điện thoại <strong id="successPhone">...</strong> để hướng dẫn lịch học và gửi tặng bộ quà 25.000.000đ.
      </p>
      <div class="lead-modal-btns">
        <a href="https://zalo.me/0977300098" target="_blank" rel="noopener noreferrer" class="lead-modal-btn-zalo">
          💬 Nhắn Zalo Xác Nhận Nhanh Ngay
        </a>
        <a href="https://phache.com.vn/" class="lead-modal-btn-home">
          🏠 Quay Về Trang Chủ
        </a>
      </div>
    </div>
  </div>

  <script>
    // Định dạng tiền tệ VND
    function formatVND(num) {
      return num.toLocaleString('vi-VN') + ' VNĐ';
    }

    // Dữ liệu các gói khóa học
    const PACKAGE_INFO = {
      'advanced': { price: 6000000, old: 10000000, discount: '-40%', title: 'Tổng Hợp Cao Cấp' },
      'pro': { price: 8880000, old: 14800000, discount: '-40%', title: 'Tổng Hợp Chuyên Nghiệp' },
      'premium': { price: 25000000, old: 55000000, discount: '-55%', title: 'Tổng Hợp Thương Hiệu' },
      'trasua': { price: 4500000, old: 7000000, discount: '-35%', title: 'Trà Sữa Đài Loan' },
      'barista': { price: 5500000, old: 8500000, discount: '-35%', title: 'Barista Cà Phê Máy' },
      'traicay': { price: 4000000, old: 6500000, discount: '-38%', title: 'Trà Trái Cây Hiện Đại' },
      'consult': { price: 6000000, old: 10000000, discount: '-40%', title: 'Tư Vấn Chọn Khóa' }
    };

    function updateSelectedPackage() {
      const pkgKey = document.getElementById('ff_package').value;
      const data = PACKAGE_INFO[pkgKey] || PACKAGE_INFO['advanced'];
      
      document.getElementById('sumPrice').textContent = formatVND(data.price);
      document.getElementById('sumOldPrice').textContent = formatVND(data.old);
      
      document.getElementById('asidePrice').textContent = data.price.toLocaleString('vi-VN') + 'đ';
      document.getElementById('asideOldPrice').textContent = data.old.toLocaleString('vi-VN') + 'đ';
      document.getElementById('asideDiscount').textContent = data.discount;
    }

    function focusToNameInput() {
      const inp = document.getElementById('ff_name');
      if (inp) {
        inp.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => inp.focus(), 300);
      }
    }

    // COUNTDOWN TIMER CHUẨN FOMO
    (function initCountdown() {
      // Đặt mốc kết thúc vào cuối ngày mai
      let endTime = localStorage.getItem('pl_offer_end_time');
      if (!endTime || Date.now() > parseInt(endTime)) {
        endTime = Date.now() + (2 * 24 * 3600 + 14 * 3600 + 35 * 60) * 1000;
        localStorage.setItem('pl_offer_end_time', endTime);
      } else {
        endTime = parseInt(endTime);
      }

      function updateCd() {
        const diff = Math.max(0, endTime - Date.now());
        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const m = Math.floor((diff / 1000 / 60) % 60);
        const s = Math.floor((diff / 1000) % 60);

        document.getElementById('cdDays').textContent = String(d).padStart(2, '0');
        document.getElementById('cdHours').textContent = String(h).padStart(2, '0');
        document.getElementById('cdMins').textContent = String(m).padStart(2, '0');
        document.getElementById('cdSecs').textContent = String(s).padStart(2, '0');
      }

      updateCd();
      setInterval(updateCd, 1000);
    })();

    // HÀM TRACKING CONVERSION CHUẨN KÉP GA4 & GOOGLE ADS
    function plTrackConversion(eventName, locationName, estimatedValue) {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        event: eventName,
        event_category: 'conversion',
        conversion_location: locationName || 'lead_capture_page',
        value: estimatedValue || 0,
        currency: 'VND'
      });
      if (typeof gtag === 'function') {
        gtag('event', eventName, {
          event_category: 'conversion',
          event_label: locationName || 'lead_capture_page',
          value: estimatedValue || 0,
          currency: 'VND'
        });
        if (eventName === 'generate_lead') {
          gtag('event', 'conversion', {
            'send_to': 'AW-16775247010/7qOlCOeDroodEKLph78-',
            'value': estimatedValue || 500000,
            'currency': 'VND'
          });
        }
      }
    }

    // XỬ LÝ SUBMIT FORM
    function handleLeadSubmit(e) {
      if (e && e.preventDefault) e.preventDefault();
      
      const name = document.getElementById('ff_name').value.trim();
      const phone = document.getElementById('ff_phone').value.trim();
      const selPkg = document.getElementById('ff_package');
      const pkgTitle = selPkg.options[selPkg.selectedIndex].text;
      const branchSel = document.getElementById('ff_branch');
      const branch = branchSel.options[branchSel.selectedIndex].text;
      const month = document.getElementById('ff_month').value;
      const slot = document.getElementById('ff_slot').value;

      if (!name) {
        alert('Vui lòng nhập Họ và tên.');
        document.getElementById('ff_name').focus();
        return false;
      }
      if (!phone || phone.length < 9) {
        alert('Vui lòng nhập Số điện thoại hợp lệ.');
        document.getElementById('ff_phone').focus();
        return false;
      }

      const btn = document.getElementById('submitLeadBtn');
      btn.disabled = true;
      btn.textContent = '⏳ Đang gửi thông tin đăng ký...';

      // 1. Kích hoạt Tracking Conversion Kép (500.000đ)
      plTrackConversion('generate_lead', 'nhan_uu_dai_page', 500000);

      // 2. Gửi đồng thời về CMS Admin qua POST API saveSign & saveCallToAction
      const BASE = 'https://phache.com.vn/';
      const d1 = new URLSearchParams();
      d1.append('template_function', 'saveSign');
      d1.append('dk_name', name);
      d1.append('dk_email', '');
      d1.append('dk_number', phone);
      d1.append('dk_home', branch);
      d1.append('dk_class', pkgTitle + ' · Tháng ' + month + ' (' + slot + ') · Ưu đãi 40%');
      fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d1.toString() }).catch(function(){});

      const d2 = new URLSearchParams();
      d2.append('template_function', 'saveCallToAction');
      d2.append('cta_name', name);
      d2.append('cta_phone', phone);
      d2.append('cta_course', pkgTitle);
      d2.append('cta_purpose', 'Nhận Ưu Đãi 40% & Quà 25Tr · ' + branch);
      fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d2.toString() }).catch(function(){});

      // 3. Gửi ERP Proxy (nếu có)
      try {
        fetch('/api/enrollment-proxy.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            fullName: name,
            phone: phone,
            courseCode: selPkg.value,
            scheduleMonth: month,
            scheduleSlot: slot,
            branch: branchSel.value
          })
        }).catch(function(){});
      } catch(err){}

      // 4. Lưu localStorage
      try {
        const leads = JSON.parse(localStorage.getItem('pl_leads_saved') || '[]');
        leads.push({ name, phone, course: pkgTitle, ts: new Date().toISOString() });
        localStorage.setItem('pl_leads_saved', JSON.stringify(leads.slice(-30)));
      } catch(e){}

      // 5. Hiện Popup chúc mừng
      setTimeout(function() {
        document.getElementById('successName').textContent = name;
        document.getElementById('successPhone').textContent = phone;
        document.getElementById('successModal').style.display = 'flex';
      }, 500);

      return false;
    }
  </script>
</body>
</html>
