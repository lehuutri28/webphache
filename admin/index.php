<?php


$admin = getAdmin(); // Get Admin information if logged in, otherwise return FALSE



if ($admin === FALSE) { // if not login yet

    

    if (isset($_POST['hd_login'])) {

        $login = checkLogin();

        

        if ($login === TRUE) redirect($_SERVER['HTTP_REFERER']);

    }

    

    if (isset($_POST['hd_forgetpass'])) {

        $success = forgetPass();

    }

    

    if (isset($_GET['p']) && $_GET['p'] == 'forgetpass') {

        require (DOCROOT . 'admin' . DIRECTORY_SEPARATOR . 'forgetpass' . EXT);

    } else {        

        require (DOCROOT . 'admin' . DIRECTORY_SEPARATOR . 'login' . EXT);

    }

    

    exit;

}



// Call function of template if exists
//
// 🔴 CƠ CHẾ NGUY HIỂM CÓ SẴN: bản cũ là
//        if (isset($_POST['template_function']) && function_exists($_POST['template_function']))
//            $dataSend = $_POST['template_function']();
//    ⇒ gọi BẤT KỲ hàm nào có tên gửi qua POST, và chạy TRƯỚC mọi chốt chặn quyền
//    bên dưới. Nhân viên "Chăm khách" chỉ cần gửi template_function=updateAdmin là
//    đổi được mật khẩu admin ⇒ vô hiệu hoá TOÀN BỘ phân quyền.
//    Nay: chỉ cho gọi hàm trong DANH SÁCH TRẮNG, và phải có quyền đúng trang.

if (isset($_POST['template_function']) && !empty($_POST['template_function'])) {

    $fn = (string) $_POST['template_function'];

    // Danh sách lấy từ chính mã nguồn các trang admin (grep template_function),
    // KHÔNG bịa: 10 hàm này là toàn bộ hàm đang được gửi qua template_function.
    // Thiếu 1 cái là nút Lưu của trang đó chết ⇒ đã đối chiếu đủ.
    $allowedFns = array(
        'saveNews'          => 'news',
        'savePage'          => 'page',
        'saveGallery'       => 'gallery',
        'saveTrainee'       => 'trainee',
        'saveSupportOnline' => 'so',
        'saveAdvisory1'     => 'advisory',
        'saveSign1'         => 'sign',
        'updateAdmin'       => 'home',   // đổi mật khẩu quản trị
        'updateInfo'        => 'home',   // lưu thông tin website
        'updateSetting'     => 'home',   // lưu cài đặt
        'saveApiKey'        => 'api_keys', // C12: Lưu/sửa API Key
        'deleteApiKey'      => 'api_keys', // C12: Xóa API Key
        'toggleApiKey'      => 'api_keys', // C12: Bật/Tắt API Key
    );

    if (!isset($allowedFns[$fn])) {
        if (function_exists('c12_audit')) {
            c12_audit('fn.denied', '', '', substr($fn, 0, 60),
                      array('ly_do' => 'hàm không nằm trong danh sách cho phép'), false);
        }
        $_SESSION['error_msg'] = 'Thao tác không hợp lệ.';
        redirect(ADMIN_URL);
    }

    if (function_exists('c12_can_page') && !c12_can_page($allowedFns[$fn])) {
        if (function_exists('c12_audit')) {
            c12_audit('fn.denied', '', '', $fn,
                      array('can_quyen' => $allowedFns[$fn], 'vai_tro' => c12_current_role()), false);
        }
        $_SESSION['error_msg'] = 'Bạn không có quyền làm việc này.';
        redirect(ADMIN_URL);
    }

    if (function_exists($fn)) {

        // #20 Nhật ký Thêm/Sửa — gắn TẠI ĐÂY vì đây là chỗ DUY NHẤT mọi nút Lưu đi qua.
        //
        // 🔴 PHẢI ĐĂNG KÝ TRƯỚC KHI GỌI $fn(), CHẠY LÚC SHUTDOWN:
        //    cả 10 hàm Lưu đều kết thúc bằng redirect() = header()+exit ⇒ mọi dòng
        //    đặt SAU $fn() là CODE CHẾT, không bao giờ chạy (bản đầu em viết sau
        //    $fn() nên nhật ký chỉ ghi khi Lưu THẤT BẠI — ghi ngược hoàn toàn).
        //    register_shutdown_function vẫn chạy sau exit() ⇒ bắt được mọi lượt Lưu.
        if (function_exists('c12_audit')) {
            // 🔴 TUYỆT ĐỐI KHÔNG đổ cả $_POST vào nhật ký: form đổi mật khẩu có
            //    txt_pass/txt_new_pass, form khách có dữ liệu cá nhân.
            //    Chỉ lấy field NHẬN DẠNG, và có chọn lọc theo trang.
            $id = '';
            foreach (array('hd_id', 'hd_news_id', 'hd_page_id', 'hd_user_id') as $k) {
                if (!empty($_POST[$k])) { $id = substr((string) $_POST[$k], 0, 64); break; }
            }

            // 🔴 Trang KHÁCH HÀNG (đăng ký học / tư vấn): field txt_name là TÊN KHÁCH.
            //    Ghi tên khách vào nhật ký = giữ lại dữ liệu cá nhân ở chỗ không ai
            //    dọn ⇒ khách yêu cầu xoá thì vẫn còn (NĐ 13/2023). Chỉ ghi mã bản ghi
            //    là đủ truy trách nhiệm. (Cùng luật với c12_trash_purge.)
            $trangKhachHang = array('sign', 'advisory', 'callToAction', 'order');
            $label = '';
            if (!in_array($allowedFns[$fn], $trangKhachHang, true)) {
                foreach (array('txt_title', 'txt_name', 'txt_news_title', 'txt_page_name') as $k) {
                    if (!empty($_POST[$k])) { $label = substr((string) $_POST[$k], 0, 200); break; }
                }
            }

            $c12_action = $allowedFns[$fn] . ($id === '' ? '.create' : '.update');

            register_shutdown_function('c12_audit_save_shutdown', $c12_action, $id, $label, $fn);
        }

        $dataSend = $fn();
    }

}



/* #21 TÀI KHOẢN BỊ KHOÁ GIỮA CHỪNG ⇒ ĐÁ RA NGAY.
 * Vai trò đọc lại từ DB mỗi request (c12_current_role) ⇒ Letri bỏ tick "Đang làm việc"
 * là người đó mất quyền ở lần bấm kế tiếp, KHÔNG cần chờ hết phiên. Không có khối này
 * thì "khoá tài khoản" chỉ là chữ trên màn hình, người nghỉ việc vẫn làm tiếp. */
if (function_exists('c12_session_revoked') && c12_session_revoked()) {
    $_SESSION = array();
    @session_unset();
    @session_destroy();
    @session_start();
    @session_regenerate_id(true);
    $_SESSION['error_msg'] = 'Tài khoản của bạn đã bị khoá. Liên hệ quản lý.';
    redirect(ADMIN_URL);
}

/* #21 CHẶN VÀO TRANG KHÔNG CÓ QUYỀN — phải đứng TRƯỚC khi nạp init của trang,
 * nếu không init đã chạy (đọc/ghi DB) rồi mới chặn thì chặn vô nghĩa.
 * Tài khoản CŨ (chưa có vai trò) ⇒ c12_can_page() trả true ⇒ chạy y như trước. */
if (function_exists('c12_guard_page')) {

    $_c12_p = isset($_uriP) ? $_uriP : '';

    if ($_c12_p === '' && !c12_can_page('home')) {
        // Vào URL admin gốc mà không được xem Cài đặt ⇒ đưa thẳng về trang của
        // vai trò đó, KHÔNG báo lỗi. Đây là việc bình thường, không phải vi phạm.
        $template = c12_role_home();

    } elseif (!c12_guard_page($_c12_p)) {
        // Cố vào đúng 1 trang không phận sự ⇒ mới là chuyện đáng báo.
        $_SESSION['error_msg'] = 'Bạn không có quyền vào mục này. Liên hệ quản lý nếu cần.';
        $template = c12_role_home();
    }
}

$templateInit = ADMIN_INIT_DIR . $template . EXT;

if (file_exists($templateInit) && is_file($templateInit)) {

    require ($templateInit);

}



?>

<!DOCTYPE html>

<html>

<head>

    <title>Asia CMS - Admin Panel</title>

    

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <meta name="robots" content="noindex, nofollow" />

    

    <link type="images/png" rel="shortcut icon" href="<?php echo ADMIN_IMG?>logo_icon.png"/>

    

    <!-- Bootstrap -->

    <link href="<?php echo ADMIN_BOOTSTRAP?>css/bootstrap.min.css" rel="stylesheet"/>

    <link href="<?php echo ADMIN_BOOTSTRAP?>css/bootstrap-theme.min.css" rel="stylesheet"/>

    <link href="<?php echo ADMIN_STYLE?>global.css?v=20260724a" rel="stylesheet" />

    <?php

        $pageCssDir = ADMIN_ROOT . $template . '.css.php';

        if (is_file($pageCssDir) && file_exists($pageCssDir)) {

            require_once $pageCssDir;

        } // end if exists php page contain css then insert it

    ?>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

    <![endif]-->

    <script type="text/javascript">

    <?php

        require_once ADMIN_JQUERY_DIR . 'jquery-1.10.2.min.js';

        require_once ADMIN_BOOTSTRAP_DIR . 'js' . DIRECTORY_SEPARATOR . 'bootstrap.min.js';

        require_once ADMIN_JQUERY_DIR . 'jquery.blockUI.js';

    ?>

        var BASE_URL  = '<?php echo BASE_URL?>';

        var ADMIN_URL = '<?php echo ADMIN_URL?>';

        var AJAX_URL  = '<?php echo AJAX_URL?>';

        

        $(document).ready(function(){

            $(".uploadImg").change(function(event){

                var uploadImg = $(this);

                var input = $(event.currentTarget);

                var file = input[0].files[0];

                var reader = new FileReader();

                reader.onload = function(e){

                    image_base64 = e.target.result;

                    if ($(uploadImg).next('.preloadImgUp')) $(uploadImg).next('.preloadImgUp').remove();

                    $(uploadImg).after('<img src="' + image_base64 + '" class="preloadImgUp" style="max-width:50px"/>');

                };

                reader.readAsDataURL(file);

            });

        });

        function growl(string,type,width)

        {

            $.bootstrapGrowl(string,{type:type,offset:{from:'top',amount:50},align:'right',width:width});

        }

        function formatPrice(s,gia)

        {   

            var strTmp = gia;        

            var formatedPrice = '';      

            

            while (strTmp.length > 3) {            

                formatedPrice = '.' + strTmp.slice(-3) + formatedPrice;

                strTmp = strTmp.substr(0, strTmp.length-3);

            }

            

            if (strTmp != '') formatedPrice = strTmp + formatedPrice;



            $(s).attr('data-original-title', formatedPrice);

            

            if (gia != '' && gia != 0) $(s).tooltip('show');

            else $(s).tooltip('hide');

        }

        

        function blockPage()

        {

            $.blockUI({ message: '<div id="page_loading"><img src="<?php echo ADMIN_IMG?>mask_loading.gif" />Loading...</div>' });

        }

        function unblockPage() {$.unblockUI();}

    </script>

    <script src="<?php echo ADMIN_JQUERY?>jquery.bootstrap-growl.min.js" type="text/javascript"></script>



    <?php

        $pageJsDir = ADMIN_ROOT . $template . '.js.php';

        if (is_file($pageJsDir) && file_exists($pageJsDir)) {

            require_once $pageJsDir;

        } // end if exists php page contain js then insert it

    ?>

</head>

<body>

<nav class="navbar navbar-default navbar-fixed-top" role="banner" id="nav_top">

    <div class="navbar-header">

        <a class="navbar-brand" href="<?php echo BASE_URL?>" title="Xem Website" target="_blank">

            <img src="<?php echo BASE_URL?>hoc/assets/logo-passionlink.png?v=2" alt="Passion Link" class="brand-logo-circle" /><span class="brand-text"><b>Passion Link</b><em class="brand-slogan">Học pha chế từ đam mê</em></span></a>

        </a>

        <button data-target="#menu_header" data-toggle="collapse" type="button" class="navbar-toggle collapsed">

            <span class="sr-only">Toggle Menu Header</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

        </button>

    </div>



    <nav id="menu_header" class="collapse navbar-collapse">

        <ul class="nav navbar-nav navbar-right" role="navigation">

            <?php if (c12_can_page('sign')) { ?><li><a href="<?php echo ADMIN_URL?>&p=sign" title="Quản lý Thông Tin KH đăng kí nhận tin">Đăng ký học</a></li><?php } ?>

            <?php if (c12_can_page('advisory')) { ?><li><a href="<?php echo ADMIN_URL?>&p=advisory" title="Quản lý Thông Tin Tư Vấn KH">Tư Vấn</a></li><?php } ?>

            <?php if (c12_can_page('news')) { ?><li><a href="<?php echo ADMIN_URL?>&p=news" title="Quản lý Tin tức">Tin tức</a></li><?php } ?>

            <?php if (c12_can_page('gallery')) { ?><li><a href="<?php echo ADMIN_URL?>&p=gallery" title="Thư viện Ảnh">Thư viện Ảnh</a></li><?php } ?>

            <?php if (c12_can_page('page')) { ?><li><a href="<?php echo ADMIN_URL?>&p=page" title="Quản lý Trang">Trang</a></li><?php } ?>

            <?php if (c12_can_page('so')) { ?><li><a href="<?php echo ADMIN_URL?>&p=so" title="Hỗ trợ trực tuyến">Hỗ trợ</a></li><?php } ?>

            <li><a href="<?php echo BASE_URL?>hoc/admin/dashboard.php" title="Quản lý học online (mở giao diện học online)">🎓 Học online</a></li>

            <?php if (c12_can_page('api_keys')) { ?><li><a href="<?php echo ADMIN_URL?>&p=api_keys" title="Quản lý API Key & Tích hợp Automation">API Keys</a></li><?php } ?>

            <?php if (c12_can_page('home')) { ?><li><a href="<?php echo ADMIN_URL?>" title="Cài đặt">Cài đặt</a></li><?php } ?>

            <li><a href="<?php echo AJAX_URL?>&p=out" title="Thoát">Thoát</a></li>

        </ul>

    </nav>

</nav>



<div id="main">

    <div id="left_content">

        <ul id="menu" class="nav navbar_left">

            <?php if (c12_can_page('trainee')) { ?><li><a href="<?php echo ADMIN_URL?>&p=trainee"><span class="glyphicon glyphicon-user"></span> Học viên đánh giá</a></li><?php } ?>

            <?php if (c12_can_page('sign')) { ?><li><a href="<?php echo ADMIN_URL?>&p=sign"><span class="glyphicon glyphicon-briefcase"></span> Đăng ký học</a></li><?php } ?>

            <?php if (c12_can_page('advisory')) { ?><li><a href="<?php echo ADMIN_URL?>&p=advisory"><span class="glyphicon glyphicon-comment"></span> Tư Vấn</a></li><?php } ?>

            <?php if (c12_can_page('news')) { ?><li><a href="<?php echo ADMIN_URL?>&p=news"><span class="glyphicon glyphicon-file"></span> Tin tức</a></li><?php } ?>

            <?php if (c12_can_page('callToAction')) { ?><li><a href="<?php echo ADMIN_URL?>&p=callToAction"><span class="glyphicon glyphicon-file"></span> Call To Action</a></li><?php } ?>

            <?php if (c12_can_page('gallery')) { ?><li><a href="<?php echo ADMIN_URL?>&p=gallery"><span class="glyphicon glyphicon-picture"></span> Thư viện ảnh</a></li><?php } ?>

            <?php if (c12_can_page('page')) { ?><li><a href="<?php echo ADMIN_URL?>&p=page"><span class="glyphicon glyphicon-book"></span> Quản lý Trang</a></li><?php } ?>

            <?php if (c12_can_page('so')) { ?><li><a href="<?php echo ADMIN_URL?>&p=so"><span class="glyphicon glyphicon-earphone"></span> Hỗ trợ trực tuyến</a></li><?php } ?>

            <?php /* Wave D — hệ học online (/hoc/). KHÔNG bọc c12_can_page('hoc') vì 'hoc'
                     chưa đăng ký trong phân quyền Wave A ⇒ bọc vào sẽ ẩn với MỌI người.
                     An toàn vẫn đủ: trang đích tự kiểm quyền (hoc_admin_require → 403). */ ?>
            <?php /* icon facetime-video: CMS chay Bootstrap v3.0.3, icon "education" (ban cu
                     dung) chi co tu v3.3 nen hien rong. Letri bao 23/7. */ ?>
            <li><a href="<?php echo BASE_URL?>hoc/admin/dashboard.php"><span class="glyphicon glyphicon-facetime-video"></span> Học online</a></li>


            <?php if (c12_can_page('trash')) { ?><li><a href="<?php echo ADMIN_URL?>&p=trash"><span class="glyphicon glyphicon-trash"></span> Thùng rác</a></li><?php } ?>

            <?php if (c12_can_page('audit')) { ?><li><a href="<?php echo ADMIN_URL?>&p=audit"><span class="glyphicon glyphicon-list-alt"></span> Nhật ký</a></li><?php } ?>

            <?php if (c12_can_page('api_keys')) { ?><li class="<?php echo (isset($template) && $template === 'api_keys') ? 'active' : '' ?>"><a href="<?php echo ADMIN_URL?>&p=api_keys"><span class="glyphicon glyphicon-lock"></span> Quản lý API Key</a></li><?php } ?>

            <?php if (c12_can_page('users')) { ?><li><a href="<?php echo ADMIN_URL?>&p=users"><span class="glyphicon glyphicon-user"></span> Người dùng</a></li><?php } ?>

            <?php if (c12_can_page('home')) { ?><li><a href="<?php echo ADMIN_URL?>"><span class="glyphicon glyphicon-cog"></span> Cài đặt</a></li><?php } ?>

            <li><a href="<?php echo AJAX_URL?>&p=out"><span class="glyphicon glyphicon-log-out"></span> Thoát</a></li>

        </ul>

    </div>

    <div id="right_content">

        <div class="row">

            <div class="col-lg-12">

                <?php
                /* #21 HIỆN BÁO LỖI — TẬP TRUNG 1 CHỖ cho MỌI trang.
                 * Trước đây 16 chỗ ĐẶT $_SESSION['error_msg'] rồi redirect, nhưng chỉ
                 * 4/13 trang có in ra ⇒ câu báo TÀNG HÌNH, lại còn đọng lại trong phiên
                 * rồi bất ngờ nhảy ra ở trang khác sau đó (home.php in key 'error' —
                 * KHÁC hẳn key 'error_msg'). NV bị chặn mà không biết vì sao.
                 * In ở đây thì mọi trang admin đều hiện + dọn ngay. */
                if (isset($_SESSION['error_msg'])) { ?>
                    <div class="alert alert-danger">
                        <b><span class="glyphicon glyphicon-warning-sign"></span>
                        <?php echo htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8') ?></b>
                    </div>
                <?php unset($_SESSION['error_msg']); } ?>

                <?php if (isset($template)) require (DOCROOT . 'admin' . DIRECTORY_SEPARATOR . $template . EXT)?>

            </div>

        </div>

    </div>

</div>

</body>

</html>

<?php

$obMySQLi->close(); // Closes opened database connection

?>