<?php

/*function checkLogin()
{
    $user = isset($_GET['user']) ? $_GET['user'] : '';
    $pass = isset($_GET['pass']) ? $_GET['pass'] : '';
    
    if ($user == $GLOBALS['shopInfo']['admin_user'] && $GLOBALS['shopInfo']['admin_pass'] == md5($pass)) {
        $_SESSION['ss_admin'] = $GLOBALS['shopInfo']['admin_user'];
        
        die(1);
    }
    
    echo 'Tên đăng nhập hay mật khẩu không đúng.';
}*/

function logout()
{
    // 🔴 Trước đây chỉ unset('ss_admin') ⇒ c12_uid của người TRƯỚC còn sót trong
    //    phiên. Máy dùng chung: Bích đăng nhập (uid=3) → Thoát → Letri đăng nhập
    //    bằng tài khoản CŨ trên chính máy đó → c12_uid vẫn = 3 → Letri vào khoá
    //    Bích thì bị báo "Không thể tự khoá chính mình" ⇒ KHÔNG đuổi được đúng
    //    người cần đuổi, mà câu báo lỗi thì vô nghĩa nên không ai đoán ra cách thoát.
    //    Dọn sạch + đổi id phiên (vá luôn session fixation).
    $_SESSION = array();
    @session_unset();
    @session_destroy();
    @session_start();
    @session_regenerate_id(true);

    redirect(ADMIN_URL);
}

function removePage()
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) exit('Trang không hợp lệ.');

    $listChild = getPage('', '', $id);

    if (!empty($listChild)) exit('Không thể xóa Trang cha.');

    // 🔴 GIỮ CHỐT CHẶN CỦA CMS: deletePage() cũ từ chối xoá TRANG ĐẶC BIỆT
    // (trang hệ thống: chủ, giới thiệu…). c12_trash_move() không biết luật này
    // ⇒ phải tự kiểm ở đây, nếu không NV xoá nhầm là VỠ WEB.
    $page = getPage($id);
    if (empty($page) || !isset($page['page_type'])) exit('Không tìm thấy trang.');
    if (isPageSpecial($page['page_type'])) exit('Không thể xóa trang hệ thống.');

    // #5 Thùng rác (giữ child-guard + special-guard ở trên)
    if (c12_trash_move('page', 'page_id', $id, 'page_name')) {
        die(1);
    }

    echo 'Xóa trang không thành công.';
}

function removeAdvisory()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    // #5 Thùng rác: chuyển vào _trash thay vì xóa vĩnh viễn (khôi phục được)
    if (c12_trash_move('form_advisory', 'advisory_id', $id, 'advisory_name')) {
        die(1);
    }

    echo 'Xóa không thành công.';
}

function removeSign()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (c12_trash_move('form_sign', 'sign_id', $id, 'sign_name')) {
        die(1);
    }

    echo 'Xóa không thành công.';
}

function removeNews()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (c12_trash_move('news', 'news_id', $id, 'news_title')) {
        die(1);
    }

    echo 'Xóa tin không thành công.';
}


function removeTrainee()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (c12_trash_move('trainee_evaluate', 'trainee_id', $id, 'trainee_name')) {
        die(1);
    }

    echo 'Xóa đánh giá không thành công.';
}


function removeCate()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    $listChild = getCate('', $id);
    
    if (!empty($listChild)) exit('Không thể xóa danh mục cha.');
    
    if (deleteCate($id)) {
        die(1);
    }
    
    echo 'Xóa danh mục sản phẩm không thành công.';
}

function changeOrderProduct()
{
    $tr_ids = isset($_GET['tr']) ? $_GET['tr'] : array();
    $orderFirstItem = '';
    if (isset($_GET['itemF'])) {
        $parts = explode('_', $_GET['itemF']); // tránh Notice "only variables by reference"
        $orderFirstItem = end($parts);
    }
    if (is_array($tr_ids) && $orderFirstItem !== '') {
        $count = intval($orderFirstItem);
        foreach ($tr_ids as $tr_id)
        {
            updateProduct((0 + $tr_id), '`product_order`=' . $count); // ép số chống SQLi qua tr[]
            $count++;
        }

    }

}
function removeProduct()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (deleteProduct($id)) {
        die(1);
    }
    
    echo 'Xóa sản phẩm không thành công.';
}

function removeProductInfo()
{
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    
    if (deleteProductInfo($code)) {
        die(1);
    }
    
    echo 'Xóa thông tin sản phẩm không thành công.';
}

function removeProductImg()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (deleteProductImg($id)) {
        die(1);
    }
    
    echo 'Xóa hình ảnh của sản phẩm không thành công.';
}

function removeGallery()
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) exit('Thư viện ảnh không hợp lệ.');

    // #5 Thùng rác. KHÁC deleteGallery() cũ: KHÔNG xoá ảnh con + KHÔNG @unlink file
    // ⇒ khôi phục là về nguyên vẹn cả bộ ảnh. Ảnh con chỉ bị dọn khi NV bấm
    // "Xóa vĩnh viễn" trong Thùng rác (c12_purge_assets lo việc đó).
    if (c12_trash_move('gallery', 'gallery_id', $id, 'gallery_name')) {
        die(1);
    }

    echo 'Xóa thư viện ảnh không thành công.';
}

function removeGalleryImg()
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) exit('Hình ảnh không hợp lệ.');

    // #5 Thùng rác: giữ file trên đĩa để khôi phục được; purge mới @unlink.
    if (c12_trash_move('gallery_imgs', 'gallery_imgs_id', $id, 'image_title')) {
        die(1);
    }

    echo 'Xóa hình ảnh của thư viện ảnh không thành công.';
}

function removeOrder()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    // #5 Thùng rác: chỉ chuyển HEADER đơn vào _trash, GIỮ NGUYÊN chi tiết đơn
    // (shop_order_detail) để khôi phục đầy đủ. Không xóa vĩnh viễn.
    if (c12_trash_move('shop_order', 'order_id', $id, 'order_name')) {
        die(1);
    }

    echo 'Xóa đơn hàng không thành công.';
}

function removeSupportOnline()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (c12_trash_move('support_online', 'so_id', $id, 'so_name')) {
        exit(1);
    }

    echo 'Xóa ho tro truc tuyen không thành công.';
}

function changePageStatus()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($id)) die(ERROR_PARAM);
    
    $page = getPage($id);
    $newStatus = abs($page['page_status'] - 1);
    
    updatePage($id, "`page_status`=$newStatus");
    
    echo $newStatus;
}

function changePageMenuTop()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($id)) die(ERROR_PARAM);
    
    $page = getPage($id);
    $newStatus = abs($page['menu_top'] - 1);
    
    updatePage($id, "`menu_top`=$newStatus");
    
    echo $newStatus;
}

function changeProductHot()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($id)) die(ERROR_PARAM);
    
    $product = getProduct($id);
    $newStatus = abs($product['product_hot'] - 1);
    
    updateProduct($id, "`product_hot`=$newStatus");
    
    echo $newStatus;
}

function changeOrderStatus()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($id)) die(ERROR_PARAM);
    
    $order = getOrder($id);
    $newStatus = abs($order['order_status'] - 1);
    
    updateOrder($id, "`order_status`=$newStatus");
    
    echo $newStatus;
}

function changeSupportOnlineStatus()
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($id)) exit(ERROR_PARAM);
    
    $so = getSupportOnline($id);
    $newStatus = abs($so['so_status'] - 1);
    
    updateSupportOnline($id, "`so_status`=$newStatus");
    
    echo $newStatus;
}

function viewImageDir()
{
    include_once(ADMIN_ROOT . 'view_image_dir' . EXT);
}

function uploadGallery()
{
    $gid = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($gid)) exit('Tham số Thư viện ảnh không tồn tại.');
    
    require_once(CLASS_DIR . 'upload/class.upload' . EXT);
    $fileUploadHandle = new upload($_FILES['Filedata'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
        if (isset($GLOBALS['cmsInfo']['gallery_upload_size_limit'])) {
            $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['gallery_upload_size_limit'];
        } // if isset gallery upload size limit            
        $fileUploadHandle->process(GALLERY_DIR);
        
        if (!$fileUploadHandle->processed) { 
            echo $fileUploadHandle->error;
        } else {
            insertGalleryImgs($gid, $fileUploadHandle->file_dst_name);
            exit(1);
        }
    }
}

function deleteImageFile()
{
    // 🔴 LỖ HỔNG CŨ (có từ trước, vá tại đây vì đang deploy chính file này):
    //    trước là `$imgDir = IMAGE_DIR . $_GET['img']; unlink($imgDir);` — nối
    //    thẳng tham số từ URL. Admin bị lừa bấm 1 link dạng
    //    index.php?t=ajax&p=dif&img=../../config/db.php  ⇒ XOÁ file lõi của web,
    //    mất vĩnh viễn (không qua Thùng rác). Nay dùng đúng helper an toàn:
    //    basename() cắt mọi đường dẫn + c12_unlink_safe chặn '..', '/', '\', byte NUL,
    //    và chỉ xoá trong đúng thư mục ảnh.
    //    (Chặn giả mạo đã đặt tập trung ở getTemplateAdminAjax — route 'dif' có guard.)
    if (!isset($_GET['img']) || $_GET['img'] === '') {
        echo ERROR_PARAM;
        return;
    }

    $name = basename(str_replace('\\', '/', (string)$_GET['img']));

    if ($name !== '' && c12_unlink_safe(IMAGE_DIR, $name)) {
        // Xoá file ảnh là KHÔNG CỨU ĐƯỢC (không qua Thùng rác) ⇒ phải có nhật ký.
        c12_audit('file.del', '', '', $name, array('yeu_cau_goc' => substr((string) $_GET['img'], 0, 200)));
        echo 1;
        exit;
    }

    c12_audit('file.del', '', '', $name, array('yeu_cau_goc' => substr((string) $_GET['img'], 0, 200)), false);
    echo 'Xóa file không thành công';
}

function deleteBackgroundImage()
{
    updateCMSInfo('cms_background_image', '');
    
    if (isset($GLOBALS['cmsInfo']['cms_background_image']) && !empty($GLOBALS['cmsInfo']['cms_background_image'])){
        $bgImageDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_background_image'];
        
        if (is_file($bgImageDir) && file_exists($bgImageDir))
            if (unlink($bgImageDir)) exit(1);
    }
    
    echo 'Xóa hình nền không thành công.';
}

function deleteHeaderFile()
{
    if (isset($GLOBALS['cmsInfo']['cms_header']) && !empty($GLOBALS['cmsInfo']['cms_header'])){
        $headerFileDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_header'];
        
        if (is_file($headerFileDir) && file_exists($headerFileDir))
            if (unlink($headerFileDir)) {
                updateCMSInfo('cms_header', '');
                exit(1);    
            }
    }
    
    echo 'Xóa Banner header không thành công.';
}

function deleteFaviconFile()
{
    if (isset($GLOBALS['cmsInfo']['cms_favicon']) && !empty($GLOBALS['cmsInfo']['cms_favicon'])){
        $fileDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_favicon'];
        
        if (is_file($fileDir) && file_exists($fileDir))
            if (unlink($fileDir)) {
                updateCMSInfo('cms_favicon', '');
                exit(1);
            }
    }
    
    echo 'Xóa icon của Website không thành công.';
}

function deleteHeaderAdv()
{
    if (isset($GLOBALS['cmsInfo']['cms_header_adv']) && !empty($GLOBALS['cmsInfo']['cms_header_adv'])){
        $fileDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_header_adv'];
        
        if (is_file($fileDir) && file_exists($fileDir))
            if (unlink($fileDir)) {
                updateCMSInfo('cms_header_adv', '');
                exit(1);
            }
    }
    
    echo 'Xóa quảng cáo của Website không thành công.';
}

?>