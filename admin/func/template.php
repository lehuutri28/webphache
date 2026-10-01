<?php
/**
 * @author asmking
 * @copyright 2011
 */
 
function getTemplateAdmin($p)
{   
    $arrPage = array
    (
        'page'     => 'page',
        'cate'     => 'category',
        'news'     => 'news',
        'gallery'  => 'gallery',
        'product'  => 'product',
        'pri'      => 'product_info',
        'order'    => 'order',
        'so'       => 'support_online',
        'advisory' => 'advisory',
        'sign'     => 'sign',
        'trainee'  => 'trainee_evaluate',
        'callToAction' => 'callToAction',
        // 🔴 THIẾU 2 DÒNG NÀY LÀ MENU BẤM VÀO RA TRANG CÀI ĐẶT:
        //    getTemplateAdmin() trả 'home' cho mọi p lạ ⇒ trang mới có file,
        //    có link trong menu, nhưng KHÔNG BAO GIỜ hiện ra.
        'trash'    => 'trash',            // #5 Thùng rác
        'audit'    => 'audit',            // #20 Nhật ký thao tác
        'users'    => 'users',            // #21 Người dùng
        'export'   => 'export',           // #11 Xuất Excel (quyền kiểm theo &type= trong init/export.php)
        'api_keys' => 'api_keys'          // C12: Quản lý API Key & Tích hợp Automation
    );

    if (array_key_exists($p, $arrPage)) {
        return $arrPage[$p];
    }

    return 'home';
}

function getTemplateAdminAjax($p)
{
    $arrPage = array
    (
        'out'    => 'logout',
        'rp'     => 'removePage',
        'rn'     => 'removeNews',
        'rtr'    => 'removeTrainee',
        'rc'     => 'removeCate',
        'rpr'    => 'removeProduct',
        'rpri'   => 'removeProductInfo',
        'rpi'    => 'removeProductImg',
        'rg'     => 'removeGallery',
        'rgi'    => 'removeGalleryImg',
        'ro'     => 'removeOrder',
        'rso'    => 'removeSupportOnline',
        'cps'    => 'changePageStatus',
        'cpmt'   => 'changePageMenuTop',
        'cos'    => 'changeOrderStatus',
        'cop'    => 'changeOrderProduct',
        'csos'   => 'changeSupportOnlineStatus',                
        'vid'    => 'viewImageDir',        
        'dif'    => 'deleteImageFile',
        'dbi'    => 'deleteBackgroundImage',
        'dhf'    => 'deleteHeaderFile',
        'dff'    => 'deleteFaviconFile',
        'gcapt'  => 'getCaptcha',
        'ui'     => 'uploadImage',
        'cph'    => 'changeProductHot',
        'ra'     => 'removeAdvisory',
        'rs'     => 'removeSign',
        'tthumb' => 'timThumb',
        'dhadv'  => 'deleteHeaderAdv'
    );

    if (array_key_exists($p, $arrPage)) {
        // #9 CHẶN GIẢ MẠO — đặt TẬP TRUNG ở đây thay vì rải vào 24 handler (rải = sót).
        // Miễn trừ, có lý do rõ ràng cho từng cái:
        //   vid/gcapt/tthumb : chỉ ĐỌC (xem thư mục ảnh, captcha, tạo thumbnail).
        //                      tthumb còn bị gọi qua <img src> ⇒ không bao giờ có header XHR.
        //   out              : đăng xuất — bị lừa đăng xuất chỉ gây phiền, chặn thì hỏng nút Thoát.
        //   ui               : upload qua Uploadify (FLASH, không phải jQuery) ⇒ không có header
        //                      XHR; chặn là hỏng upload ảnh. (Đợt C bỏ Flash thì siết lại.)
        $noGuard = array('vid', 'gcapt', 'tthumb', 'out', 'ui');

        if (!in_array($p, $noGuard, true)) {
            c12_csrf_guard_ajax();

            // #21 Phân quyền: dùng CHUNG luật với trang (route 'rn' = quyền trang 'news')
            // ⇒ không thể có cảnh giấu nút trên giao diện nhưng gọi thẳng URL vẫn xoá được.
            if (!c12_can_page(c12_ajax_route_page($p))) {
                c12_audit('ajax.denied', '', '', 'route=' . substr((string) $p, 0, 20),
                          array('vai_tro' => c12_current_role()), false);
                header('HTTP/1.1 403 Forbidden');
                echo 'Bạn không có quyền làm việc này. Liên hệ quản lý nếu cần.';
                exit;
            }
        }

        return $arrPage[$p];
    }

    return FALSE;
}

// Support Online manager
function saveAdvisory1()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        'advisory_name'  => $_POST['txt_name'],
        'advisory_email' => $_POST['txt_email'],
        'advisory_number' => $_POST['txt_number'],
        'advisory_demand' => $_POST['txt_demand'],
        'advisory_content' => $_POST['txt_content'],   
    );
    
    // Data Validation 
    if (empty($arrInput['advisory_name'])) {
        $error['advisory_name'] = 'Chưa nhập tên.';
    }
    
    if (empty($arrInput['advisory_email'])) {
        $error['advisory_email'] = 'Chưa nhập Email.';
    }
    
    if (empty($arrInput['advisory_number'])) {
        $error['advisory_number'] = 'Chưa nhập điện thoại.';
    }
    
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['advisory_name']  = stripQuotes(strip_tags($arrInput['advisory_name']));
        $arrInput['advisory_email'] = stripQuotes(strip_tags($arrInput['advisory_email']));
        $arrInput['advisory_number'] = stripQuotes(strip_tags($arrInput['advisory_number']));
        $arrInput['advisory_demand'] = stripQuotes(strip_tags($arrInput['advisory_demand']));
        $arrInput['advisory_content'] = stripQuotes(strip_tags($arrInput['advisory_content']));
        
        if (empty($id)) { // add new page
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertAdvisory($strFields, $strValues);
        } else { // edit page
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateAdvisory($id, $str);
        }        
        
        $_SESSION['success'] = 'Thông Tin Khách Hàng Đã Được Lưu Thành Công.';
        redirect(ADMIN_URL.'&p=advisory');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function saveSign1()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        'sign_name'  => $_POST['txt_name'],
        'sign_email' => $_POST['txt_email'],
        'sign_number' => $_POST['txt_number'],
        // FIX C12 15/7: trước đây bỏ 3 ô này → mất dữ liệu âm thầm
        'sign_birthday' => isset($_POST['txt_birthday']) ? $_POST['txt_birthday'] : '',
        'sign_home'     => isset($_POST['txt_home'])     ? $_POST['txt_home']     : '',
        'sign_day'      => isset($_POST['txt_day'])      ? $_POST['txt_day']      : '',
    );

    // Data Validation
    if (empty($arrInput['sign_name'])) {
        $error['sign_name'] = 'Chưa nhập tên.';
    }
    
    if (empty($arrInput['sign_email'])) {
        $error['sign_email'] = 'Chưa nhập Email.';
    }
    
    if (empty($arrInput['sign_number'])) {
        $error['sign_number'] = 'Chưa nhập điện thoại.';
    }
    
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['sign_name']  = stripQuotes(strip_tags($arrInput['sign_name']));
        $arrInput['sign_email'] = stripQuotes(strip_tags($arrInput['sign_email']));
        $arrInput['sign_number'] = stripQuotes(strip_tags($arrInput['sign_number']));
        $arrInput['sign_birthday'] = stripQuotes(strip_tags($arrInput['sign_birthday']));
        $arrInput['sign_home'] = stripQuotes(strip_tags($arrInput['sign_home']));
        $arrInput['sign_day'] = stripQuotes(strip_tags($arrInput['sign_day']));

        if (empty($id)) { // add new page
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertSign($strFields, $strValues);
        } else { // edit page
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateSign($id, $str);
        }        
        
        $_SESSION['success'] = 'Thông Tin Khách Hàng Đã Được Lưu Thành Công.';
        redirect(ADMIN_URL.'&p=sign');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}
 
// Support Online manager
function saveSupportOnline()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        'so_name'  => $_POST['txt_name'],
        'so_yahoo' => $_POST['txt_yahoo'],
        'so_skype' => $_POST['txt_skype'],
        'so_phone' => $_POST['txt_phone'],
        'so_order' => $_POST['txt_order']    
    );
    
    // Data Validation 
    if (empty($arrInput['so_name'])) {
        $error['so_name'] = 'Chưa nhập tên.';
    }
    
    if (empty($arrInput['so_yahoo'])) {
        $error['so_yahoo'] = 'Chưa nhập tài khoản Yahoo Messenger.';
    }
    
    if (empty($arrInput['so_phone'])) {
        $error['so_phone'] = 'Chưa nhập điện thoại.';
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['so_name']  = stripQuotes(strip_tags($arrInput['so_name']));
        $arrInput['so_yahoo'] = stripQuotes(strip_tags($arrInput['so_yahoo']));
        $arrInput['so_skype'] = stripQuotes(strip_tags($arrInput['so_skype']));
        $arrInput['so_phone'] = stripQuotes(strip_tags($arrInput['so_phone']));
        $arrInput['so_order'] = stripQuotes(strip_tags($arrInput['so_order']));
        
        if (empty($id)) { // add new page
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertSupportOnline($strFields, $strValues);
        } else { // edit page
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateSupportOnline($id, $str);
        }        
        
        $_SESSION['success'] = 'Thông tin hỗ trợ đã được lưu thành công.';
        redirect(ADMIN_URL.'&p=so');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// Page manager
function savePage()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        //'page_type'        => $_POST['sel_type'],
        'page_permalink'   => $_POST['txt_permalink'],
        'page_order'       => $_POST['txt_order'],
        'page_name'        => $_POST['txt_name'],       
        'page_content'     => $_POST['txta_content'],
        'page_title'       => $_POST['txta_title'],       
        'page_description' => $_POST['txta_description'], 
        'page_keyword'     => $_POST['txta_keyword']    
    );
    
    // Data Validation 
    if (empty($arrInput['page_name'])) {
        $error['page_name'] = 'Chưa nhập tên trang.';
    }
    
    if (empty($arrInput['page_permalink'])) {
        $arrInput['page_permalink'] = $arrInput['page_name'];
    }
    
    $arrInput['page_permalink'] = formatUrlSeo($arrInput['page_permalink'],'');
    if (checkPermalinkExists($arrInput['page_permalink'], $id)) {
        $error['page_permalink'] = 'Đường dẫn tĩnh này đã tồn tại.';
    }
    
    if (!empty($_POST['sel_type'])) {
        $arrInput['page_type'] = $_POST['sel_type'];
        
        if ($arrInput['page_type'] == 'elink') {
            if (empty($_POST['txt_permalink'])) {
                $error['page_permalink'] = 'Trang loại elink phải có URL liên kết ngoại.';
            } elseif (filter_var($_POST['txt_permalink'], FILTER_VALIDATE_URL) === FALSE) {
                $error['page_permalink'] = 'URL Liên kết không hợp lệ.';
            } else {
                $arrInput['page_permalink'] = $_POST['txt_permalink'];
            }
        }
    }
    
    if (isset($_POST['sel_parent'])){
        if ($_POST['sel_parent'] > 0) {
            $_POST['sel_parent'] = intval($_POST['sel_parent']);
            
            $page = getPage($_POST['sel_parent']);
            
            $arrInput['page_level']  = $page['page_level'] + 1;
            $arrInput['page_parent'] = $_POST['sel_parent']; 
        } else {
            $arrInput['page_level']  = 1;
            $arrInput['page_parent'] = 0;
        }
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['page_name']        = stripQuotes(strip_tags($arrInput['page_name']));
        $arrInput['page_content']     = quotesEncode($arrInput['page_content']);
        $arrInput['page_title']       = stripQuotes(strip_tags($arrInput['page_title']));
        $arrInput['page_description'] = stripQuotes(strip_tags($arrInput['page_description']));
        $arrInput['page_keyword']     = stripQuotes(strip_tags($arrInput['page_keyword']));
        
        if (empty($id)) { // add new page
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertPage($strFields, $strValues);
        } else { // edit page
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updatePage($id, $str);
        }        
        
        $_SESSION['success'] = 'Lưu trang thành công.';
        redirect(ADMIN_URL.'&p=page');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function saveTrainee()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        'trainee_name'        => $_POST['txt_name'],
        'news_id'             => $_POST['sel_course'],
        'trainee_content'     => $_POST['txta_content'],       
    );
    
    // Data Validation
    if (empty($arrInput['news_id'])) {
        $error['news_id'] = 'Chưa chọn khóa học';
    }
    
    if (empty($arrInput['trainee_name'])) {
        $error['trainee_name'] = 'Chưa nhập tiêu đề tin.';
    }
    
    if (empty($arrInput['trainee_content'])) {
        $error['trainee_content'] = 'Chưa nhập nội dung.';
        
    }
   
    require_once(CLASS_DIR . 'upload/class.upload' . EXT);
    $fileUploadHandle = new upload($_FILES['file_img'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
        if (!empty($GLOBALS['cmsInfo']['news_img_size_limit'])) {
            $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['news_img_size_limit'];
        }            
        $fileUploadHandle->process(NEWS_DIR);
        
        if (!$fileUploadHandle->processed) { 
            $error['trainee_image'] = $fileUploadHandle->error;
        } else {
            $arrInput['trainee_image'] = $fileUploadHandle->file_dst_name;
        }
        
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['trainee_name']      = stripQuotes(strip_tags($arrInput['trainee_name']));
        $arrInput['trainee_content']   = quotesEncode($arrInput['trainee_content']);
        $arrInput['news_id']           = intval($arrInput['news_id']);
        
        
        if (empty($id)) { // add new trainee
        
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete news info for new add session
            
            insertTrainee($strFields, $strValues);
           
            
        } else { // edit trainee
        
            if (isset($arrInput['trainee_image']) && !empty($arrInput['trainee_image'])) { // if exist new image
                $news = getNews($id);
                if (file_exists(NEWS_DIR . $news['trainee_image']) && is_file(NEWS_DIR . $news['trainee_image'])) {
                    @unlink(NEWS_DIR . $news['trainee_image']); 
                }
            }
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateTrainee($id, $str);
        }
        
        $_SESSION['success'] = 'Lưu đánh giá học viên thành công.';
        redirect(ADMIN_URL.'&p=trainee');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// News manager
function saveNews()
{        
    $error = array();
    $id   = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(
        'page_id'           => $_POST['sel_page'],
        'news_title'        => $_POST['txt_title'],
        'news_order'        => $_POST['txt_order'],     
        'news_content'      => $_POST['txta_content'],       
        'news_description'  => $_POST['txt_description'], 
        'news_keyword'      => $_POST['txt_keyword'],
        'news_videos'       => $_POST['txt_videos']   
    );

    // Data Validation
    if (empty($arrInput['page_id'])) {
        $error['page_id'] = 'Chưa chọn Trang tin';    
    }
    
    if (empty($arrInput['news_title'])) {
        $error['news_title'] = 'Chưa nhập tiêu đề tin.';
    }
    
    if (empty($arrInput['news_content'])) {
        $error['news_content'] = 'Chưa nhập nội dung tin.';
        
    }
   
    require_once(CLASS_DIR . 'upload/class.upload' . EXT);
  
    $fileUploadHandle = new upload($_FILES['file_img'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
        if (!empty($GLOBALS['cmsInfo']['news_img_size_limit'])) {
            $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['news_img_size_limit'];
        }            
        $fileUploadHandle->process(NEWS_DIR);
        
        if (!$fileUploadHandle->processed) { 
            $error['news_image'] = $fileUploadHandle->error;
        } else {
            $arrInput['news_image'] = $fileUploadHandle->file_dst_name;
        }
        
    }
     
    $fileUploadHandle = new upload($_FILES['file_img_title'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
        if (!empty($GLOBALS['cmsInfo']['news_img_size_limit'])) {
            $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['news_img_size_limit'];
        }            
        $fileUploadHandle->process(NEWS_DIR);
        
        if (!$fileUploadHandle->processed) { 
            $error['news_image_title'] = $fileUploadHandle->error;
        } else {
            $arrInput['news_image_title'] = $fileUploadHandle->file_dst_name;
        }
        
    }


// print_r($_FILES['file_img_slide']);

// die();

    if (!empty($_FILES['file_img_slide'])) {
        $news_img_slide_all = array();
        $countfiles = count($_FILES['file_img_slide']['name']);
        // FIX C12 15/7 #3: chỉ nhận ẢNH THẬT + đổi tên an toàn (chống upload mã độc/ghi đè/path traversal)
        $slide_allow_ext = array('jpg','jpeg','png','gif','webp');
        for($i=0;$i<$countfiles;$i++){
            $news_img_slide_list = array();
            $origname = isset($_FILES['file_img_slide']['name'][$i]) ? $_FILES['file_img_slide']['name'][$i] : '';
            $tmp      = isset($_FILES['file_img_slide']['tmp_name'][$i]) ? $_FILES['file_img_slide']['tmp_name'][$i] : '';
            $uerr     = isset($_FILES['file_img_slide']['error'][$i]) ? $_FILES['file_img_slide']['error'][$i] : UPLOAD_ERR_NO_FILE;
            $filenametitle = isset($_POST['title_img_slide'][$i]) ? $_POST['title_img_slide'][$i] : '';
            if ($origname !== '' && $uerr === UPLOAD_ERR_OK && is_uploaded_file($tmp)) {
                $ext = strtolower(pathinfo($origname, PATHINFO_EXTENSION));
                $imgcheck = @getimagesize($tmp); // FALSE nếu KHÔNG phải ảnh thật
                if (in_array($ext, $slide_allow_ext, true) && $imgcheck !== false) {
                    $safename = 'slide_' . date('YmdHis') . '_' . $i . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
                    if (move_uploaded_file($tmp, NEWS_DIR . $safename)) {
                        $news_img_slide_list['image'] = $safename;
                        $news_img_slide_list['title'] = $filenametitle;
                    }
                }
            }
            $news_img_slide_all[] = $news_img_slide_list;
        }
        
        $check_array = array_filter($news_img_slide_all);

            if(!empty($check_array)){
                $arrInput['news_img_slide'] = json_encode($news_img_slide_all,JSON_UNESCAPED_UNICODE);
            }else{
                $arrInput['news_img_slide'] = array();
            }
         
    }

    if (empty($error)) { // not error
        // Data Filter
        $arrInput['news_title']        = quotesEncode(strip_tags($arrInput['news_title']));
        $arrInput['news_content']      = quotesEncode($arrInput['news_content']);
        $arrInput['news_order']        = is_numeric($arrInput['news_order']) ? $arrInput['news_order'] : 1;
        $arrInput['news_description']  = stripQuotes(strip_tags($arrInput['news_description']));
        $arrInput['news_keyword']      = stripQuotes(strip_tags($arrInput['news_keyword']));
        $arrInput['news_videos']      = $arrInput['news_videos'];
        
       
        if (empty($id)) { // add new news
        
            $arrInput['news_date_created'] = time();
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete news info for new add session
            
             $listsign = getSign();
            
            $mailSubject = $_POST['txt_title'];
            $mailContent = "{$_POST['txta_content']}";
           
            // foreach ($listsign as $ls)  
            // {
        
            //     sendMailSMTP(array($ls['sign_email'],$GLOBALS['cmsInfo']['smtp_email_account']),$ls['sign_name'], $mailSubject, $mailContent);
            // }
            
            insertNews($strFields, $strValues);
           
            
        } else { // edit news
            if (isset($arrInput['news_img_slide']) && !empty($arrInput['news_img_slide'])) { // if exist new image
                 $news = getNews($id);
                 $array_slide_new = json_decode($arrInput['news_img_slide'],TRUE);
                 if(!empty($news['news_img_slide'])){
                    $array_slide_old = json_decode($news['news_img_slide'],TRUE);
                }else{
                    $array_slide_old = array();
                }
                 $array_merge_slide = array_merge_recursive($array_slide_new,$array_slide_old);

                 $array_save_slide =  array();
                 foreach ($array_merge_slide as $keyarray_merge_slide => $valuearray_merge_slide) {
                    if ($valuearray_merge_slide['image']) {
                        $array_save_slide[] = $valuearray_merge_slide;
                    }
                 }
                $arrInput['news_img_slide'] =  json_encode($array_save_slide,JSON_UNESCAPED_UNICODE);

            }else{
                $news = getNews($id);
                $arrInput['news_img_slide'] =  $news['news_img_slide'];
            }

            if (isset($arrInput['news_image']) && !empty($arrInput['news_image'])) { // if exist new image
                $news = getNews($id);
                if (file_exists(NEWS_DIR . $news['news_image']) && is_file(NEWS_DIR . $news['news_image'])) {
                    @unlink(NEWS_DIR . $news['news_image']); // delete news old file image
                }
            }
            
             if (isset($arrInput['news_image_title']) && !empty($arrInput['news_image_title'])) { // if exist new image
                $news = getNews($id);
                if (file_exists(NEWS_DIR . $news['news_image_title']) && is_file(NEWS_DIR . $news['news_image_title'])) {
                    @unlink(NEWS_DIR . $news['news_image_title']); // delete news old file image
                }
            }

            $arrInput['news_date_modified'] = time();
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateNews($id, $str);
        }
        
        $_SESSION['success'] = 'Lưu tin tức thành công.';
       
        redirect(ADMIN_URL.'&p=news');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// category manager
function saveCate()
{        
    $error   = array();
    $id      = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';

    $arrInput = array(        
        'category_name'        => $_POST['txt_name'],            
        'category_parent_id'   => isset($_POST['sel_cate']) ? $_POST['sel_cate'] : 0,
        'category_order'       => $_POST['txt_order'],
        'category_content'     => $_POST['txta_content'],
        'category_title'       => $_POST['txt_title'],
        'category_description' => $_POST['txta_description'],
        'category_keyword'     => $_POST['txta_keyword']
    );
    
    if ($arrInput['category_parent_id'] > 0){
       $cate = getCate($arrInput['category_parent_id']);
       $arrInput['category_level'] = $cate['category_level'] + 1;
    } else $arrInput['category_level'] = 1;  
      
    // Data Validation 
    if (empty($arrInput['category_name'])) {
        $error['category_name'] = 'Chưa nhập tên danh mục.';
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['category_name']        = stripQuotes(strip_tags($arrInput['category_name'])); 
        $arrInput['category_order']       = intval($arrInput['category_order']);
        $arrInput['category_content']     = quotesEncode($arrInput['category_content']); 
        $arrInput['category_title']       = stripQuotes(strip_tags($arrInput['category_title']));
        $arrInput['category_description'] = stripQuotes(strip_tags($arrInput['category_description']));
        $arrInput['category_keyword']     = stripQuotes(strip_tags($arrInput['category_keyword']));
        
        if (empty($id)) { // add new cate
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertCate($strFields, $strValues);
           
        } else { // edit cate
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateCate($id, $str);            
        }
        
        $_SESSION['success'] = 'Lưu Danh mục sản phẩm thành công.';
        redirect(ADMIN_URL.'&p=cate');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// Product manager
function saveProduct()
{        
    require_once(CLASS_DIR . 'upload/class.upload' . EXT);
    
    $error = array();
    $id    = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';
    $ID    = $id;
    $listProductInfo = getProductInfo();

    $arrInput = array(
        'category_id'             => isset($_POST['sel_cate'])?$_POST['sel_cate']:'',
        'product_name'            => $_POST['txt_name'],
        'product_code'            => $_POST['txt_code'],
        'product_order'           => $_POST['txt_order'],
        'product_price_value'     => $_POST['txt_price'],
        'product_price_currency'  => $_POST['sel_currency'],
        //'product_price_promotion' => $_POST['txt_price_promotion'],
        'product_content'         => $_POST['txta_content'],
        'product_title'           => $_POST['txta_title'],
        'product_description'     => $_POST['txta_description'], 
        'product_keyword'         => $_POST['txta_keyword']
    );
    
    // Data Validation 
    if (empty($arrInput['product_name'])) {
        $error['product_name'] = 'Chưa nhập tên sản phẩm.';
    }
    
    if (empty($arrInput['category_id'])) {
        $error['category'] = 'Bạn chưa chọn Danh mục sản phẩm';
    }
        
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['product_name']            = stripQuotes(strip_tags($arrInput['product_name']));
        $arrInput['product_code']            = stripQuotes(strip_tags($arrInput['product_code']));
        $arrInput['product_order']           = intval($arrInput['product_order']);
        $arrInput['product_price_value']     = intval($arrInput['product_price_value']);
        //$arrInput['product_price_promotion'] = intval($arrInput['product_price_promotion']);
        $arrInput['product_price_currency']  = stripQuotes(strip_tags($arrInput['product_price_currency']));
        $arrInput['product_content']         = quotesEncode($arrInput['product_content']);
        $arrInput['product_title']           = stripQuotes(strip_tags($arrInput['product_title']));
        $arrInput['product_description']     = stripQuotes(strip_tags($arrInput['product_description']));
        $arrInput['product_keyword']         = stripQuotes(strip_tags($arrInput['product_keyword']));
        
        if (empty($id)) { // add new product
        
            $arrInput['product_date_created'] = time();
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete product info for new add session
            
            insertProduct($strFields, $strValues);
            $ID = $GLOBALS['obMySQLi']->insert_id;
            
            foreach ($listProductInfo as $v) {
                if (isset($_POST["txt_info_{$v['product_info_code']}"]) && !empty($_POST["txt_info_{$v['product_info_code']}"])) {
                    $_POST["txt_info_{$v['product_info_code']}"] = stripQuotes(strip_tags($_POST["txt_info_{$v['product_info_code']}"]));
                    
                    insertProductDetails($ID, $v['product_info_code'], $_POST["txt_info_{$v['product_info_code']}"]);
                }
            }
        } else { // edit product
        
            $arrInput['product_date_modified'] = time();
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateProduct($id, $str);
            
            // Update or add product info
            foreach ($listProductInfo as $v) {
                if (isset($_POST["txt_info_{$v['product_info_code']}"])) {
                    $_POST["txt_info_{$v['product_info_code']}"] = stripQuotes(strip_tags($_POST["txt_info_{$v['product_info_code']}"]));
                    
                    $detail = getProductDetails($id, $v['product_info_code']);
                    $func   = ($detail===FALSE) ? 'insertProductDetails' : 'updateProductDetails';                    
                    
                    $func($id, $v['product_info_code'], $_POST["txt_info_{$v['product_info_code']}"]);
                }
            }
            
            // Update product images
            $listProductImgs = getProductImgs($id);
            foreach ($listProductImgs as $v) {
                $imgID = $v['product_imgs_id'];
                $arrInputImgs = array(
                    'image_title' => isset($_POST["img_title_$imgID"])?$_POST["img_title_$imgID"]:'',
                    'image_alt'   => isset($_POST["img_alt_$imgID"])?$_POST["img_alt_$imgID"]:'',
                    'image_order' => isset($_POST["img_order_$imgID"])?$_POST["img_order_$imgID"]:1
                );
                
                if (isset($_POST['rdo_imgs']) && $_POST['rdo_imgs'] == $imgID) {
                    $arrInputImgs['image_primary'] = 1;
                } else {
                    $arrInputImgs['image_primary'] = 0;
                }
                
                $arrInputImgs['image_title'] = stripQuotes(strip_tags($arrInputImgs['image_title']));
                $arrInputImgs['image_alt']   = stripQuotes(strip_tags($arrInputImgs['image_alt']));
                $arrInputImgs['image_order'] = intval($arrInputImgs['image_order']);
                
                $arr = array();
                reset($arrInputImgs);
                foreach ($arrInputImgs as $key => $val) {
                    $arr[] = "`$key`='$val'";
                }
                $str = implode(',', $arr);
                
                updateProductImgs($imgID, $str);
            }
        }
        
        for ($i=0;$i<$GLOBALS['cmsInfo']['product_img_num_limit'];$i++) {
            
            if (isset($_FILES['file_img_'.($i+1)]) && !empty($_FILES['file_img_'.($i+1)])) {               
                
                $fileUploadHandle = new upload($_FILES['file_img_'.($i+1)], 'vn_VN');
                if ($fileUploadHandle->uploaded) {
                    $fileUploadHandle->file_new_name_body = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
                    $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
                    if (!empty($GLOBALS['cmsInfo']['product_img_size_limit'])) {
                        $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['product_img_size_limit'];    
                    }                                
                    $fileUploadHandle->process(PRODUCT_DIR);
                    
                    if (!$fileUploadHandle->processed) { 
                        $error['file_img_'.($i+1)] = $fileUploadHandle->error;
                    } else {
                        insertProductImgs($ID, $fileUploadHandle->file_dst_name);
                    }
                }
            }
        }
        
        if (empty($error)) { // not error
            $_SESSION['success'] = 'Sản phẩm đã được lưu thành công.';
            redirect(ADMIN_URL.'&p=product');//.(empty($id)?'':"&id=$id"));
        }
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// Page manager
function saveProductInfo()
{        
    $error = array();
    $code  = isset($_POST['hd_code']) ? $_POST['hd_code'] : '';    

    $arrInput = array(
        'product_info_title' => $_POST['txt_title'],
        'product_info_code'  => $_POST['txt_code']
    );
    
    // Data Validation 
    if (empty($arrInput['product_info_title'])) {
        $error['title'] = 'Chưa nhập tiêu đề.';
    }
    
    if (empty($arrInput['product_info_code'])) {
        $arrInput['product_info_code'] = $arrInput['product_info_title'];
    }
    
    $arrInput['product_info_code'] = formatUrlSeo($arrInput['product_info_code'],'');
    if (checkProductInfoCode($arrInput['product_info_code']) && empty($code)) {
        $error['code'] = 'Mã thông tin này đã được sử dụng, vui lòng chọn mã khác';
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['product_info_title'] = stripQuotes(strip_tags($arrInput['product_info_title']));
        
        if (empty($code)) { // add new product info
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete page info for new add session
            
            insertProductInfo($strFields, $strValues);
        } else { // edit product info
        
            $pi = getProductInfo($id);
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateProductInfo($code, $str);
        }
        
        $_SESSION['success'] = 'Thông tin sản phẩm đã được lưu thành công.';
        redirect(ADMIN_URL.'&p=pri');//.(empty($code)?'':"&code=$code"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function saveCheckOut()
{
    // include captcha class
    include_once(CLASS_DIR . 'captcha' . DIRECTORY_SEPARATOR . 'php-captcha.inc' . EXT);
    
    $error   = array();

    $arrInput = array(        
        'order_name'    => $_POST['txt_order_name'],            
        'order_email'   => $_POST['txt_order_email'],
        'order_address' => $_POST['txta_order_address'],
        'order_phone'   => $_POST['txt_order_phone'],
        'ship_name'     => $_POST['txt_ship_name'],
        'ship_address'  => $_POST['txta_ship_address'],
        'ship_date'     => $_POST['txt_ship_date'],
        'order_message' => $_POST['txta_message'],
        'checkout_type' => $_POST['sel_type']
    );
    
    // Data Validation 
    if (empty($arrInput['order_name'])) {
        $error['order_name'] = 'Chưa nhập tên.';
    }
    
    if (empty($arrInput['order_email'])) {
        $error['order_email'] = 'Chưa nhập Email.';
    } elseif (filter_var($arrInput['order_email'], FILTER_VALIDATE_EMAIL) == FALSE) {
        $error['order_email'] = 'Email không hợp lệ.';
    }
    
    if (empty($arrInput['order_phone'])) {
        $error['order_phone'] = 'Chưa nhập điện thoại.';
    }
    
    if (empty($arrInput['ship_date'])) {
        $error['ship_date'] = 'Chưa nhập ngày giao hàng.';
    }
    
    if (empty($_POST['txt_captcha'])) {
        $error['txt_captcha'] = 'Chưa nhập mã xác thực.';
    } elseif (!PhpCaptcha::Validate($_POST['txt_captcha'])) {
        $error['txt_captcha'] = 'Mã xác thực không đúng.';
    }
    
    if (empty($error)) { // not error    
        // Data Filter
        //$arrInput['ship_date'] = strtotime($arrInput['ship_date']);
        $arrInput['order_name']    = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['order_email']   = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['order_address'] = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['order_phone']   = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['ship_name']     = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['ship_address']  = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['ship_date']     = stripQuotes(strip_tags($arrInput['order_name']));
        $arrInput['order_message'] = quotesEncode($arrInput['order_message']);
        
        $lambdaFunc = function($value){ return "'".$value."'"; };        
        $strFields = implode(',', array_keys($arrInput));
        $strValues = implode(',', array_map($lambdaFunc, $arrInput));
        
        insertCheckOut($strFields, $strValues);
        $orderID = $GLOBALS['obMySQLi']->insert_id;
        
        foreach ($_SESSION['ss_cart'] as $v) {
            $arrInputOrderDetail = array(        
                'order_id'      => $orderID,            
                'product_id'    => $v['product_id'],
                'product_price' => $v['price'],
                'number'        => $v['number'],
                'size'          => $v['size']               
            );
            
            $strFields = implode(',', array_keys($arrInputOrderDetail));
            $strValues = implode(',', array_map($lambdaFunc, $arrInputOrderDetail));
            
            insertOrderDetail($strFields, $strValues);
        }
        
        $_SESSION['success'] = 'Đơn đặt hàng đã được lưu vào hệ thống.';
        redirect(BASE_URL . getUrlUri('gio-hang'));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

// Gallery manager
function saveGallery()
{        
    require_once(CLASS_DIR . 'upload' . DIRECTORY_SEPARATOR . 'class.upload' . EXT);
    
    $error = array();
    $id    = isset($_POST['hd_id']) ? $_POST['hd_id'] : '';
    $ID    = $id;

    $arrInput = array(
        'page_id'             => isset($_POST['sel_page']) ? $_POST['sel_page'] : '',
        'gallery_name'        => $_POST['txt_name'],
        'gallery_title'       => $_POST['txta_title'],    
        'gallery_description' => $_POST['txta_description'], 
        'gallery_keyword'     => $_POST['txta_keyword']
    );
    
    // Data Validation 
    if (empty($arrInput['page_id'])) {
        unset($arrInput['page_id']);
    }
    
    if (empty($arrInput['gallery_name'])) {
        $error['gallery_name'] = 'Chưa nhập tên thư viện ảnh.';
    }
    
        
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['gallery_name']        = stripQuotes(strip_tags($arrInput['gallery_name']));
        $arrInput['gallery_title']       = stripQuotes(strip_tags($arrInput['gallery_title']));
        $arrInput['gallery_description'] = stripQuotes(strip_tags($arrInput['gallery_description']));
        $arrInput['gallery_keyword']     = stripQuotes(strip_tags($arrInput['gallery_keyword']));
        
        if (empty($id)) { // add new product
        
            $arrInput['gallery_date_created'] = time();
        
            $lambdaFunc = function($value){ return "'".$value."'"; };        
            $strFields = implode(',', array_keys($arrInput));
            $strValues = implode(',', array_map($lambdaFunc, $arrInput));
            $arrInput  = array(); // Delete product info for new add session
            
            insertGallery($strFields, $strValues);
            $ID = $GLOBALS['obMySQLi']->insert_id;
            
        } else { // edit product
        
            $arrInput['gallery_date_modified'] = time();
        
            $arr = array();
            reset($arrInput);
            foreach ($arrInput as $key => $val) {
                $arr[] = "`$key`='$val'";
            }
            $str = implode(',', $arr);
            
            updateGallery($id, $str);
            
            // Update gallery images
            $listGalleryImgs = getGalleryImgs($id);
            foreach ($listGalleryImgs as $v) {
                $imgID = $v['gallery_imgs_id'];
                $arrInputImgs = array(
                    'image_title' => isset($_POST["img_title_$imgID"])?$_POST["img_title_$imgID"]:'',
                    'image_alt'   => isset($_POST["img_alt_$imgID"])?$_POST["img_alt_$imgID"]:'',
                    'image_link'  => isset($_POST["img_link_$imgID"])?$_POST["img_link_$imgID"]:'',
                    'image_order' => isset($_POST["img_order_$imgID"])?$_POST["img_order_$imgID"]:1
                );
                
                if (isset($_POST['rdo_imgs']) && $_POST['rdo_imgs'] == $imgID) {
                    $arrInputImgs['image_primary'] = 1;
                } else {
                    $arrInputImgs['image_primary'] = 0;
                }
                
                if (!empty($arrInputImgs['image_link']) && filter_var($arrInputImgs['image_link'], FILTER_VALIDATE_URL) === FALSE) {
                    $arrInputImgs['image_link'] = '';
                }
                
                $arr = array();
                reset($arrInputImgs);
                foreach ($arrInputImgs as $key => $val) {
                    $arr[] = "`$key`='$val'";
                }
                $str = implode(',', $arr);
                
                updateGalleryImgs($imgID, $str);
            }
        }
        
        for ($i=0;$i<$GLOBALS['cmsInfo']['gallery_upload_num_limit'];$i++) {
            
            if (isset($_FILES['file_img_'.($i+1)]) && !empty($_FILES['file_img_'.($i+1)])) {               
                
                $fileUploadHandle = new upload($_FILES['file_img_'.($i+1)], 'vn_VN');
                if ($fileUploadHandle->uploaded) {
                    $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
                    $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");
                    if (!empty($GLOBALS['cmsInfo']['gallery_upload_size_limit'])) {
                        $fileUploadHandle->file_max_size = $GLOBALS['cmsInfo']['gallery_upload_size_limit'];    
                    }                                
                    $fileUploadHandle->process(GALLERY_DIR);
                    
                    if (!$fileUploadHandle->processed) { 
                        $error['file_img_'.($i+1)] = $fileUploadHandle->error;
                    } else {
                        insertGalleryImgs($ID, $fileUploadHandle->file_dst_name);
                    }
                }
            }
        }
        
        if (empty($error)) { // upload file image not error
            $_SESSION['success'] = 'Thư viện ảnh đã được lưu thành công.'; 
        } else {
            $_SESSION['error'] = $error;   
        }
                
        redirect(ADMIN_URL.'&p=gallery');//.(empty($id)?'':"&id=$id"));
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function updateAdmin()
{
    $error    = array();
    $arrInput = array(
        'admin_user'  => $_POST['txt_username'],
        'admin_email' => $_POST['txt_email']
    );
    
    if (empty($_POST['txt_pass'])) { // Must input password for change admin information
        $error['txt_pass'] = 'Chưa nhập mật khẩu.';
    } else {
        $stored_ap = $GLOBALS['cmsInfo']['admin_pass']; // FIX C12 15/7 #12: kiểm CẢ MD5 cũ LẪN hash mới
        $curOK_ap = (strlen($stored_ap) === 32 && ctype_xdigit($stored_ap)) ? hash_equals($stored_ap, md5($_POST['txt_pass'])) : (function_exists('password_verify') && password_verify($_POST['txt_pass'], $stored_ap));
        if (!$curOK_ap) {
            $error['txt_pass'] = 'Mật khẩu không đúng.';
        } else { 
        
            if (empty($arrInput['admin_user'])) {
                $error['admin_user'] = 'Chưa nhập tên tài khoản quản trị.';
            }
            
            if (empty($arrInput['admin_email'])) {
                $error['admin_email'] = 'Chưa nhập Email quản trị.';
            } elseif (filter_var($arrInput['admin_email'], FILTER_VALIDATE_EMAIL) == FALSE) {
                $error['admin_email'] = 'Email không hợp lệ.';
            }    
            
            if (!empty($_POST['txt_new_pass'])) {
                if (strlen($_POST['txt_new_pass']) < 6) {
                    $error['txt_cf_new_pass'] = 'Mật khẩu mới quá ngắn, phải có ít nhất là 6 ký tự.';
                } elseif ($_POST['txt_new_pass'] != $_POST['txt_cf_new_pass']) {
                    $error['txt_cf_new_pass'] = 'Nhập lại mật khẩu không đúng.';
                }
            }
        }
    }
    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['admin_user'] = formatUrlSeo($arrInput['admin_user'], '');
        if (!empty($_POST['txt_new_pass'])) { // if change password by input old pass
            $arrInput['admin_pass'] = function_exists('password_hash') ? password_hash($_POST['txt_new_pass'], PASSWORD_DEFAULT) : md5($_POST['txt_new_pass']); // FIX C12 15/7 #12
        }
        
        setupCMSInfo($arrInput); // Update cms information
        
        $_SESSION['success'] = 'Cập nhật thông tin quản trị thành công.';
        redirect(ADMIN_URL);
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function updateInfo()
{
    $error    = array();
    $arrInput = array(
        'cms_title'         => $_POST['txta_title'],
        'cms_header_width'  => $_POST['txt_width'],
        'cms_header_height' => $_POST['txt_height'],
        'cms_description'   => $_POST['txta_description'],
        'cms_keyword'       => $_POST['txta_keyword'],
        'cms_footer'        => $_POST['txta_footer']
    );
    
    require_once(CLASS_DIR . 'upload/class.upload' . EXT);
    $fileUploadHandle = new upload($_FILES['file_header'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png","application/x-shockwave-flash");
        //$fileUploadHandle->file_max_size = '500K';            
        $fileUploadHandle->process(UPLOAD_DIR.'header');
        
        if (!$fileUploadHandle->processed) { 
            $error['file_header'] = $fileUploadHandle->error;
        } else {
            $absHeaderFile = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_header'];
            
            if (file_exists($absHeaderFile) && is_file($absHeaderFile)) { // Delete header old file if exists
                if (unlink($absHeaderFile)) $arrInput['cms_header'] = $fileUploadHandle->file_dst_name;
            } else {
                $arrInput['cms_header'] = $fileUploadHandle->file_dst_name;
            }
        }
    }
    unset($fileUploadHandle);
    $fileUploadHandle = new upload($_FILES['file_favicon'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png","image/vnd.microsoft.icon");
        $fileUploadHandle->file_max_size = '500K';            
        $fileUploadHandle->process(UPLOAD_DIR.'header');
        
        if (!$fileUploadHandle->processed) { 
            $error['file_favicon'] = $fileUploadHandle->error;
        } else {
            $absFaviconFile = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_favicon'];
            
            if (file_exists($absFaviconFile) && is_file($absFaviconFile)) { // Delete header old file if exists
                if (unlink($absFaviconFile)) $arrInput['cms_favicon'] = $fileUploadHandle->file_dst_name;
            } else {
                $arrInput['cms_favicon'] = $fileUploadHandle->file_dst_name;
            }
        }
    }
    unset($fileUploadHandle);
    
    $fileUploadHandle = new upload($_FILES['header_adv'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png","application/x-shockwave-flash");
        $fileUploadHandle->process(UPLOAD_DIR.'header');
        
        if (!$fileUploadHandle->processed) { 
            $error['file_favicon'] = $fileUploadHandle->error;
        } else {
            $absHeaderAdv = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_header_adv'];
            
            if (file_exists($absHeaderAdv) && is_file($absHeaderAdv)) { // Delete header old file if exists
                if (unlink($absHeaderAdv)) $arrInput['cms_header_adv'] = $fileUploadHandle->file_dst_name;
            } else {
                $arrInput['cms_header_adv'] = $fileUploadHandle->file_dst_name;
            }
        }
    }

    if (empty($error)) { // not error
        // Data Filter
        $arrInput['cms_title']         = stripQuotes(strip_tags($arrInput['cms_title']));
        $arrInput['cms_header_width']  = intval($arrInput['cms_header_width']);
        $arrInput['cms_header_height'] = intval($arrInput['cms_header_height']);
        $arrInput['cms_description']   = stripQuotes(strip_tags($arrInput['cms_description']));
        $arrInput['cms_keyword']       = stripQuotes(strip_tags($arrInput['cms_keyword']));
        $arrInput['cms_footer']        = quotesEncode($arrInput['cms_footer']);
        
        setupCMSInfo($arrInput); // Update cms information
        
        $_SESSION['success'] = 'Cập nhật thông tin CMS thành công.';
        redirect(ADMIN_URL.'#tabs_info');
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}

function updateSetting()
{
    $error    = array();
    $arrInput = array(
        'cms_friendly_uri'          => $_POST['rdo_seo'],
        'client_maintenance'        => $_POST['rdo_mt'],
        'product_pagination'        => $_POST['txt_product_pagination'],
        'category_pagination'       => $_POST['txt_category_pagination'],
        'home_pagination'           => $_POST['txt_home_pagination'],
        'cms_background_color'      => $_POST['txt_bg_color'],
        'cms_background_position'   => $_POST['sel_bg_pos'],
        'cms_background_repeat'     => $_POST['sel_bg_repeat'],
        'cms_background_attachment' => $_POST['sel_bg_attachment'],
        'slideshow'                 => $_POST['sel_slideshow'],
        'gallery_slideshow'         => isset($_POST['sel_gallery_slideshow']) ? $_POST['sel_gallery_slideshow'] : '',
        'gallery_left'              => isset($_POST['sel_gallery_left']) ? $_POST['sel_gallery_left'] : '',
        'gallery_right'             => isset($_POST['sel_gallery_right']) ? $_POST['sel_gallery_right'] : '',
        'home_adv_popup'            => isset($_POST['txta_popup']) ? $_POST['txta_popup'] : '',
        'adv_roll_power'            => $_POST['rdo_roll_power'],
        'adv_roll_left_link'        => $_POST['txt_adv_roll_left_link'],
        'adv_roll_right_link'       => $_POST['txt_adv_roll_right_link']
    );
    
    require_once(CLASS_DIR . 'upload' . DIRECTORY_SEPARATOR . 'class.upload' . EXT);
    $fileUploadHandle = new upload($_FILES['file_background'], 'vn_VN');
    if ($fileUploadHandle->uploaded) {
        $fileUploadHandle->file_new_name_body   = formatUrlSeo($fileUploadHandle->file_src_name_body,'');
        $fileUploadHandle->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png");         
        $fileUploadHandle->process(UPLOAD_DIR.'header');
        
        if (!$fileUploadHandle->processed) { 
            $error['file_background'] = $fileUploadHandle->error;
        } else {
            $bgImageDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['cms_background_image'];
            
            if (file_exists($bgImageDir) && is_file($bgImageDir)) { // Delete old file if exists
                if (unlink($bgImageDir)) $arrInput['cms_background_image'] = $fileUploadHandle->file_dst_name;
            } else {
                $arrInput['cms_background_image'] = $fileUploadHandle->file_dst_name;
            }
        }
    }

    // Adv roll left
    $fileUploadHandle2 = new upload($_FILES['file_roll_left'], 'vn_VN');
    if ($fileUploadHandle2->uploaded) {
        $fileUploadHandle2->file_new_name_body   = formatUrlSeo($fileUploadHandle2->file_src_name_body,'');
        $fileUploadHandle2->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png","application/x-shockwave-flash");         
        $fileUploadHandle2->process(UPLOAD_DIR.'image');
        
        if (!$fileUploadHandle2->processed) { 
            $error['file_roll_left'] = $fileUploadHandle2->error;
        } else {
            $bgImageDir = UPLOAD_DIR . 'image' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['adv_roll_left'];
            
            if (file_exists($bgImageDir) && is_file($bgImageDir)) { // Delete old file if exists
                if (unlink($bgImageDir)) $arrInput['adv_roll_left'] = $fileUploadHandle2->file_dst_name;
            } else {
                $arrInput['adv_roll_left'] = $fileUploadHandle2->file_dst_name;
            }
        }
    }

    // Adv roll right    
    $fileUploadHandle3 = new upload($_FILES['file_roll_right'], 'vn_VN');
    if ($fileUploadHandle3->uploaded) {
        $fileUploadHandle3->file_new_name_body   = formatUrlSeo($fileUploadHandle3->file_src_name_body,'');
        $fileUploadHandle3->allowed = array("image/gif","image/jpeg","image/pjpeg","image/png","application/x-shockwave-flash");         
        $fileUploadHandle3->process(UPLOAD_DIR.'image');
        
        if (!$fileUploadHandle3->processed) { 
            $error['file_roll_right'] = $fileUploadHandle3->error;
        } else {
            $bgImageDir = UPLOAD_DIR . 'image' . DIRECTORY_SEPARATOR . $GLOBALS['cmsInfo']['adv_roll_right'];
            
            if (file_exists($bgImageDir) && is_file($bgImageDir)) { // Delete old file if exists
                if (unlink($bgImageDir)) $arrInput['adv_roll_right'] = $fileUploadHandle3->file_dst_name;
            } else {
                $arrInput['adv_roll_right'] = $fileUploadHandle3->file_dst_name;
            }
        }
    }

    
    if (empty($error)) { // not error
        // Data Filter
        $arrInput['cms_friendly_uri']          = intval($arrInput['cms_friendly_uri']);
        $arrInput['client_maintenance']        = intval($arrInput['client_maintenance']);
        $arrInput['product_pagination']        = intval($arrInput['product_pagination']);
        $arrInput['category_pagination']       = intval($arrInput['category_pagination']);
        $arrInput['home_pagination']           = intval($arrInput['home_pagination']);
        $arrInput['cms_background_color']      = stripQuotes(strip_tags($arrInput['cms_background_color']));
        $arrInput['cms_background_position']   = stripQuotes(strip_tags($arrInput['cms_background_position']));
        $arrInput['cms_background_repeat']     = stripQuotes(strip_tags($arrInput['cms_background_repeat']));
        $arrInput['cms_background_attachment'] = stripQuotes(strip_tags($arrInput['cms_background_attachment']));
        $arrInput['slideshow']                 = intval($arrInput['slideshow']);
        $arrInput['gallery_left']              = intval($arrInput['gallery_left']);
        $arrInput['gallery_right']             = intval($arrInput['gallery_right']);
        $arrInput['home_adv_popup']            = quotesEncode($arrInput['home_adv_popup']);
        $arrInput['adv_roll_power']            = intval($arrInput['adv_roll_power']);
        $arrInput['adv_roll_left_link']        = stripQuotes(strip_tags($arrInput['adv_roll_left_link']));
        $arrInput['adv_roll_right_link']       = stripQuotes(strip_tags($arrInput['adv_roll_right_link']));
        
        setupCMSInfo($arrInput); // Update cms information
        
        $_SESSION['success'] = 'Cập nhật thông tin CMS thành công.';
        redirect(ADMIN_URL.'#tabs_setting');
    }
    
    $_SESSION['error'] = $error;        
    return $arrInput;
}


/* ==== C12 WAVE 2 HELPERS (append 2026-07-15) ==== */
/* ============================================================
 * C12 — WAVE 2 SHARED ADMIN HELPERS (phache.com.vn)
 * Đặt trong admin/func/template.php (đã include mọi request admin).
 * PHP 5.6.40 compatible + PHP 8 safe. Không đụng public website.
 * ============================================================ */

/* ---------- #9 CSRF (session token) ---------- */
function c12_csrf_token()
{
    if (empty($_SESSION['c12_csrf'])) {
        if (function_exists('random_bytes')) {
            $b = random_bytes(16);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $b = openssl_random_pseudo_bytes(16);
        } else {
            $b = md5(uniqid((string)mt_rand(), true), true);
        }
        $_SESSION['c12_csrf'] = bin2hex($b);
    }
    return $_SESSION['c12_csrf'];
}

function c12_csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="'
         . htmlspecialchars(c12_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/* Trả token an toàn để nhúng vào URL (xoá/khôi phục qua link) */
function c12_csrf_url()
{
    return 'csrf_token=' . urlencode(c12_csrf_token());
}

/* Kiểm token. Trả TRUE nếu hợp lệ. Đọc cả POST lẫn GET. */
function c12_csrf_valid()
{
    $sess = isset($_SESSION['c12_csrf']) ? $_SESSION['c12_csrf'] : '';
    $sent = '';
    if (isset($_POST['csrf_token'])) $sent = $_POST['csrf_token'];
    elseif (isset($_GET['csrf_token'])) $sent = $_GET['csrf_token'];
    if ($sess === '' || !is_string($sent) || $sent === '') return false;
    if (function_exists('hash_equals')) return hash_equals($sess, $sent);
    // fallback timing-safe cho PHP quá cũ (5.6 đã có hash_equals nên hiếm dùng)
    if (strlen($sess) !== strlen($sent)) return false;
    $r = 0; for ($i = 0, $n = strlen($sess); $i < $n; $i++) $r |= ord($sess[$i]) ^ ord($sent[$i]);
    return $r === 0;
}

/* Chặn cứng khi token sai: dùng ở đầu mọi handler ghi/xoá. */
function c12_csrf_guard()
{
    if (!c12_csrf_valid()) {
        $_SESSION['error_msg'] = 'Phiên làm việc hết hạn hoặc yêu cầu không hợp lệ (CSRF). Vui lòng tải lại trang và thử lại.';
        if (function_exists('redirect') && isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo 'CSRF token không hợp lệ.';
        }
        exit;
    }
}

/* Referer (nếu có) phải CÙNG tên miền với site. Không có Referer ⇒ coi như không đạt. */
function c12_referer_same_host()
{
    if (empty($_SERVER['HTTP_REFERER'])) return false;
    $refHost = @parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    if (empty($refHost)) return false;
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $host = preg_replace('/:\d+$/', '', $host);
    if ($host === '') return false;
    return strcasecmp($refHost, $host) === 0;
}

/* Chặn giả mạo cho AJAX. Khác bản form: KHÔNG redirect (đang trong $.ajax), trả 403 gọn.
 *
 * Chấp nhận 1 trong 2:
 *   (1) token hợp lệ — mạnh nhất; hoặc
 *   (2) là XHR THẬT cùng nguồn: header `X-Requested-With: XMLHttpRequest`.
 *       Trình duyệt KHÔNG cho trang khác tên miền đặt header này (phải qua CORS
 *       preflight, mà site không bật CORS) ⇒ link bị lừa bấm / <img> / form lạ
 *       KHÔNG BAO GIỜ có nó. Đây chính là kịch bản tấn công cần chặn.
 *
 * Vì sao cần (2): mọi file .js của admin đang gọi $.ajax KHÔNG kèm token. Nếu chỉ
 * đòi token thì TOÀN BỘ nút xoá/đổi trạng thái của admin chết cứng ngay khi deploy.
 * jQuery tự gửi X-Requested-With ⇒ (2) chặn đúng kẻ tấn công mà không phá nút nào.
 * Khi các .js đã gắn token (đợt sau) thì (1) sẽ là đường chính. */
function c12_csrf_guard_ajax()
{
    if (c12_csrf_valid()) return;

    $xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($xhr && c12_referer_same_host()) return;

    // Ghi lại lần bị chặn: đây có thể là dấu hiệu admin bị lừa bấm link độc.
    c12_audit(
        'blocked', '', '',
        isset($_GET['p']) ? ('route: ' . substr((string) $_GET['p'], 0, 20)) : '',
        array(
            'referer' => isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 300) : '(không có)',
            'la_xhr'  => $xhr ? 'có' : 'không',
        ),
        false
    );

    header('HTTP/1.1 403 Forbidden');
    echo 'Yêu cầu không hợp lệ (chống giả mạo). Hãy tải lại trang quản trị rồi thử lại.';
    exit;
}

/* ---------- #21 TÀI KHOẢN RIÊNG + PHÂN QUYỀN ---------- */

/* 3 vai trò. Cố ý giữ ÍT và ĐƠN GIẢN: web này 3–5 người dùng, không phải tập đoàn.
   Thêm ma trận quyền chi tiết chỉ làm NV rối và dễ cấu hình sai. */
function c12_roles()
{
    return array(
        'owner'  => 'Chủ / Quản lý — làm được tất cả',
        'editor' => 'Biên tập nội dung — tin tức, trang, ảnh, đánh giá',
        'sales'  => 'Chăm khách — đăng ký học, tư vấn, lead, đơn hàng',
    );
}

/* Vai trò → những trang được vào (giá trị p= trên URL). */
function c12_role_pages($role)
{
    switch ($role) {
        case 'owner':
            // tất cả, kể cả Cài đặt / Nhật ký / Thùng rác / Người dùng / API Key
            return array('home','page','news','gallery','trainee','callToAction',
                         'sign','advisory','order','so','trash','audit','users',
                         'cate','product','pri','export','api_keys');
        case 'editor':
            return array('news','page','gallery','trainee','so','export');
        case 'sales':
            return array('sign','advisory','callToAction','order','export');
    }
    return array();
}

/* Xuất Excel: &type= quyết định xuất bảng nào ⇒ quyền phải theo TYPE, không phải
 * theo trang 'export'. Nếu chỉ kiểm trang 'export' thì Biên tập nội dung gõ
 * ?p=export&type=sign là tải sạch danh sách khách hàng về máy. */
function c12_export_type_page($type)
{
    $map = array(
        'sign'           => 'sign',
        'advisory'       => 'advisory',
        'cta'            => 'callToAction',
        'call_to_action' => 'callToAction',
        'order'          => 'order',
        'news'           => 'news',
        'page'           => 'page',
        'gallery'        => 'gallery',
        'trainee'        => 'trainee',
        'so'             => 'so',
        'audit'          => 'audit',
    );
    // type lạ ⇒ đòi quyền cao nhất (owner) thay vì cho qua
    return isset($map[$type]) ? $map[$type] : 'home';
}

/* Vai trò của người đang đăng nhập — 3 trạng thái:
 *   'owner'|'editor'|'sales' = tài khoản riêng, CÒN hiệu lực
 *   ''                       = tài khoản CŨ dùng chung ⇒ cửa thoát hiểm, cho qua hết
 *   FALSE                    = tài khoản riêng đã bị KHOÁ/XOÁ ⇒ CẤM HẾT + phải đá ra
 *
 * 🔴 BẮT BUỘC ĐỌC LẠI DB MỖI REQUEST, KHÔNG tin $_SESSION['c12_role'].
 *    Nếu tin session: (a) khoá tài khoản người nghỉ việc = KHÔNG đuổi được ai — họ
 *    giữ tab là làm tiếp vô thời hạn; (b) hạ quyền 1 Quản lý = họ vẫn còn quyền
 *    trong phiên → vào trang Người dùng → TỰ ĐẶT LẠI mình thành Quản lý, ghi vĩnh
 *    viễn vào DB. Cả 2 đều phá đúng lời hứa cốt lõi của tính năng này.
 *    Cache tĩnh ⇒ mỗi request chỉ 1 truy vấn. */
function c12_current_role()
{
    // 🔴 Cache PHẢI gắn với đúng tài khoản. Nếu chỉ cache "đã tính rồi" thì khi
    //    danh tính đổi giữa chừng (đăng nhập/đăng xuất trong cùng 1 request) sẽ
    //    trả nhầm vai trò của người TRƯỚC — tệ nhất là trả '' (cửa thoát hiểm)
    //    cho người đã bị khoá ⇒ họ có TOÀN QUYỀN.
    static $cachedUid = null;
    static $cached    = null;

    $uid = isset($_SESSION['c12_uid']) ? (int) $_SESSION['c12_uid'] : 0;

    if ($cached !== null && $cachedUid === $uid) return $cached;
    $cachedUid = $uid;

    // Không có c12_uid ⇒ đang dùng tài khoản CŨ ⇒ giữ nguyên cửa thoát hiểm.
    // (Chưa chạy migration thì c12_uid cũng không thể có ⇒ không lo khoá nhầm cả nhà.)
    if ($uid <= 0) {
        $cached = '';
        return $cached;
    }

    $u = function_exists('c12_user_get') ? c12_user_get($uid) : false;

    if ($u === false || (int) $u['active'] !== 1) {
        $cached = false;                       // bị khoá/xoá ⇒ cấm sạch
        return $cached;
    }

    $cached = $u['role'];                      // LUÔN lấy từ DB, không lấy từ session
    return $cached;
}

/* Người đang đăng nhập có được vào trang $p không? */
function c12_can_page($p)
{
    $role = c12_current_role();

    // 🔴 Bị khoá ⇒ KHÔNG được gì. Phải xử TRƯỚC nhánh '' bên dưới, nếu không
    //    người bị khoá lại rơi vào cửa thoát hiểm và có TOÀN QUYỀN.
    if ($role === false) return false;

    // 🔴 CỬA THOÁT HIỂM: tài khoản cũ (cms_info) chưa có vai trò ⇒ coi như owner.
    //    Không có dòng này thì ngay khi deploy, TẤT CẢ MỌI NGƯỜI bị khoá ra ngoài
    //    (vì chưa ai có tài khoản trong bảng mới). Đây là lỗi không cứu được từ xa.
    if ($role === '') return true;

    $pages = c12_role_pages($role);
    if (empty($pages)) return false;

    return in_array($p === '' ? 'home' : $p, $pages, true);
}

/* Tài khoản đang đăng nhập có vừa bị khoá/xoá không? Dùng để đá ra đăng nhập lại. */
function c12_session_revoked()
{
    return c12_current_role() === false;
}

/* Trang mặc định của từng vai trò khi vào URL admin gốc (không có &p=).
 * Không có hàm này thì editor/sales vào admin là dính báo lỗi "không có quyền"
 * rồi mới bị đá đi — mỗi lần đăng nhập đều thấy, tưởng hệ thống hỏng.
 * (URL gốc map sang 'home' = Cài đặt, mà 2 vai trò này không được vào.) */
function c12_role_home($role = null)
{
    if ($role === null) $role = c12_current_role();

    switch ($role) {
        case 'editor': return 'news';   // Biên tập → Tin tức
        case 'sales':  return 'sign';   // Chăm khách → Đăng ký học
    }

    return 'home';                      // owner + tài khoản cũ → Cài đặt (như trước)
}

/* Chặn vào trang không có quyền. Dùng ở admin/index.php TRƯỚC khi vẽ nội dung. */
function c12_guard_page($p)
{
    if (c12_can_page($p)) return true;

    c12_audit('page.denied', '', '', 'p=' . substr((string) $p, 0, 40),
              array('vai_tro' => c12_current_role()), false);
    return false;
}

/* Ajax route → trang tương ứng, để dùng chung 1 luật phân quyền.
   Route không có trong bảng ⇒ đòi quyền 'home' (chỉ owner) cho chắc. */
function c12_ajax_route_page($route)
{
    $map = array(
        'rp' => 'page', 'cps' => 'page', 'cpmt' => 'page',
        'rn' => 'news', 'rtr' => 'trainee',
        'rg' => 'gallery', 'rgi' => 'gallery',
        'ra' => 'advisory', 'rs' => 'sign',
        'ro' => 'order', 'cos' => 'order', 'cop' => 'order',
        'rso' => 'so', 'csos' => 'so',
        'rc' => 'cate', 'rpr' => 'product', 'rpri' => 'pri', 'rpi' => 'product',
        'cph' => 'product',
        'dif' => 'gallery', 'dbi' => 'home', 'dhf' => 'home', 'dff' => 'home', 'dhadv' => 'home',
    );
    return isset($map[$route]) ? $map[$route] : 'home';
}

/* ---------- #20 NHẬT KÝ THAO TÁC (audit log) ---------- */

/* IP người dùng. REMOTE_ADDR là nguồn TIN CẬY duy nhất (do máy chủ ghi).
 * X-Forwarded-For / CF-Connecting-IP do client gửi ⇒ GIẢ ĐƯỢC ⇒ chỉ ghi kèm
 * vào phần chi tiết để tham khảo, TUYỆT ĐỐI không dùng thay REMOTE_ADDR. */
function c12_client_ip()
{
    return isset($_SERVER['REMOTE_ADDR']) ? substr($_SERVER['REMOTE_ADDR'], 0, 45) : '';
}

/* IP do proxy khai báo (không đáng tin, chỉ để đối chiếu khi điều tra). */
function c12_forwarded_ip()
{
    foreach (array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP') as $h) {
        if (!empty($_SERVER[$h])) return substr($_SERVER[$h], 0, 120);
    }
    return '';
}

/**
 * Ghi 1 dòng nhật ký. KHÔNG BAO GIỜ làm hỏng việc chính:
 * mọi lỗi ghi log đều nuốt im lặng (thiếu bảng, DB bận...) — thà mất 1 dòng log
 * còn hơn chặn nhân viên làm việc.
 *
 * $action : 'news.trash' · 'page.restore' · 'trash.purge' · 'login.fail' ...
 * $table  : bảng bị tác động ('' nếu không có)
 * $id     : khoá chính ('' nếu không có)
 * $label  : tên/tiêu đề để người đọc hiểu ngay (vd tiêu đề tin)
 * $detail : mảng chi tiết (tham số, trước/sau) — sẽ lưu JSON
 * $ok     : true = thành công, false = thất bại/bị chặn
 */
function c12_audit($action, $table = '', $id = '', $label = '', $detail = null, $ok = true)
{
    if (!isset($GLOBALS['obMySQLi']) || !$GLOBALS['obMySQLi']) return false;
    $db = $GLOBALS['obMySQLi'];

    $actor = isset($_SESSION['ss_admin']) && $_SESSION['ss_admin'] !== ''
           ? (string) $_SESSION['ss_admin'] : null;

    $d = is_array($detail) ? $detail : array();
    $fwd = c12_forwarded_ip();
    if ($fwd !== '') $d['ip_proxy_khai_bao'] = $fwd;   // ghi chú: client gửi, có thể giả
    if (!empty($_SERVER['REQUEST_URI'])) $d['duong_dan'] = substr($_SERVER['REQUEST_URI'], 0, 300);

    $json = empty($d) ? '' : json_encode($d, JSON_UNESCAPED_UNICODE);
    if ($json === false) $json = '';   // dữ liệu lạ không encode được ⇒ vẫn ghi dòng log

    $actorSql = ($actor === null) ? 'NULL' : "'" . $db->real_escape_string(substr($actor, 0, 100)) . "'";

    $sql = "INSERT INTO `_audit_log` (actor, actor_ip, action, src_table, src_id, label, detail, ok, created_at) VALUES ("
         . $actorSql . ","
         . "'" . $db->real_escape_string(c12_client_ip()) . "',"
         . "'" . $db->real_escape_string(substr((string) $action, 0, 64)) . "',"
         . "'" . $db->real_escape_string(substr((string) $table, 0, 64)) . "',"
         . "'" . $db->real_escape_string(substr((string) $id, 0, 64)) . "',"
         . "'" . $db->real_escape_string(substr((string) $label, 0, 255)) . "',"
         . "'" . $db->real_escape_string($json) . "',"
         . ($ok ? 1 : 0) . ","
         . time()
         . ")";

    // @ + không kiểm kết quả: log hỏng KHÔNG được phép làm hỏng thao tác của NV
    @$db->query($sql);
    return true;
}

/* Đọc nhật ký có lọc. $f = array(q, actor, action, from, to) — dùng ở init/audit.php */
function c12_audit_list($f = array(), $limit = '')
{
    $db  = $GLOBALS['obMySQLi'];
    $w   = array();

    if (!empty($f['q'])) {
        $kw = c12_like($db, trim($f['q']));
        $w[] = "(label LIKE '%$kw%' OR src_id LIKE '%$kw%' OR actor LIKE '%$kw%' OR action LIKE '%$kw%')";
    }
    if (!empty($f['actor']))  $w[] = "actor='"  . $db->real_escape_string($f['actor'])  . "'";
    if (!empty($f['action'])) $w[] = "action='" . $db->real_escape_string($f['action']) . "'";
    if (!empty($f['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $f['from'])) {
        $w[] = 'created_at >= ' . (int) strtotime($f['from'] . ' 00:00:00');
    }
    if (!empty($f['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $f['to'])) {
        $w[] = 'created_at <= ' . (int) strtotime($f['to'] . ' 23:59:59');
    }

    $sql = "SELECT * FROM `_audit_log` " . ($w ? 'WHERE ' . implode(' AND ', $w) . ' ' : '')
         . 'ORDER BY audit_id DESC ';
    if (!empty($limit) && is_array($limit)) $sql .= 'LIMIT ' . (0 + $limit[0]) . ',' . (0 + $limit[1]);

    $res = $db->query($sql);
    if ($res === false) return array();
    $out = array();
    while ($r = $res->fetch_assoc()) $out[] = $r;
    return $out;
}

function c12_audit_count($f = array())
{
    $db = $GLOBALS['obMySQLi'];
    $w  = array();
    if (!empty($f['q'])) {
        $kw = c12_like($db, trim($f['q']));
        $w[] = "(label LIKE '%$kw%' OR src_id LIKE '%$kw%' OR actor LIKE '%$kw%' OR action LIKE '%$kw%')";
    }
    if (!empty($f['actor']))  $w[] = "actor='"  . $db->real_escape_string($f['actor'])  . "'";
    if (!empty($f['action'])) $w[] = "action='" . $db->real_escape_string($f['action']) . "'";
    if (!empty($f['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $f['from'])) $w[] = 'created_at >= ' . (int) strtotime($f['from'] . ' 00:00:00');
    if (!empty($f['to'])   && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $f['to']))   $w[] = 'created_at <= ' . (int) strtotime($f['to'] . ' 23:59:59');

    $res = $db->query("SELECT count(*) AS c FROM `_audit_log` " . ($w ? 'WHERE ' . implode(' AND ', $w) : ''));
    if ($res === false) return 0;
    $r = $res->fetch_assoc();
    return $r ? (int) $r['c'] : 0;
}

/* Bảng nào chứa DỮ LIỆU CÁ NHÂN của người ngoài (khách/học viên)?
 * 🔴 MỘT luật DÙNG CHUNG cho mọi chỗ ghi nhật ký. Trước đây luật này nằm cục bộ
 *    trong c12_trash_purge ⇒ bịt được `detail` nhưng QUÊN `label`, mà `label`
 *    chính là sign_name/advisory_name = TÊN KHÁCH ⇒ vẫn lọt vào nhật ký.
 * (support_online KHÔNG nằm đây: đó là hotline của chính công ty, đang hiện
 *  công khai trên web — không phải dữ liệu cá nhân của khách.) */
function c12_is_customer_table($table)
{
    return in_array($table, array(
        'form_sign', 'form_advisory', 'call_to_action',
        'shop_order', 'shop_order_detail', 'trainee_evaluate',
    ), true);
}

/* Nhãn an toàn để ghi nhật ký: bảng khách ⇒ KHÔNG ghi tên, chỉ ghi loại.
 * Vẫn truy được trách nhiệm nhờ src_table + src_id. */
function c12_audit_label($table, $label)
{
    if (c12_is_customer_table($table)) return '(dữ liệu cá nhân — không lưu tên)';
    return $label;
}

/* Ghi nhật ký 1 lượt bấm Lưu — CHẠY LÚC SHUTDOWN.
 * 🔴 Vì sao phải ở shutdown: các hàm saveNews/savePage/updateAdmin… kết thúc bằng
 *    redirect() = header()+exit ⇒ mọi lệnh đặt sau lời gọi hàm là CODE CHẾT.
 *    exit() VẪN chạy shutdown function ⇒ đây là chỗ duy nhất bắt được mọi lượt Lưu.
 *    (Bản đầu đặt sau lời gọi hàm ⇒ chỉ ghi khi Lưu THẤT BẠI = ghi ngược.) */
function c12_audit_save_shutdown($action, $id, $label, $fn)
{
    // Kết nối DB có thể đã đóng ở shutdown ⇒ kiểm trước, hỏng thì bỏ qua im lặng
    // (nhật ký KHÔNG bao giờ được phép làm hỏng việc chính).
    if (!isset($GLOBALS['obMySQLi']) || !$GLOBALS['obMySQLi']) return;
    c12_audit($action, '', $id, $label, array('ham' => $fn));
}

/* Bảng nhật ký đã tạo chưa? (chưa chạy migration ⇒ c12_audit nuốt lỗi im lặng
 * ⇒ Letri tưởng đang có nhật ký mà thật ra KHÔNG ghi gì. Phải hiện cảnh báo.) */
function c12_audit_table_exists()
{
    static $ok = null;
    if ($ok !== null) return $ok;
    $res = $GLOBALS['obMySQLi']->query("SELECT 1 FROM `_audit_log` LIMIT 1");
    $ok  = ($res !== false);
    return $ok;
}

/* Danh sách hành động đã từng xảy ra — để đổ vào ô lọc. */
function c12_audit_actions()
{
    $db  = $GLOBALS['obMySQLi'];
    $res = $db->query("SELECT DISTINCT action FROM `_audit_log` ORDER BY action");
    if ($res === false) return array();
    $out = array();
    while ($r = $res->fetch_assoc()) $out[] = $r['action'];
    return $out;
}

/* Đổi mã hành động sang tiếng Việt cho NV đọc. */
function c12_audit_action_label($action)
{
    $map = array(
        'trash'     => 'Chuyển vào Thùng rác',
        'restore'   => 'Khôi phục từ Thùng rác',
        'purge'     => 'XOÁ VĨNH VIỄN',
        'update'    => 'Sửa',
        'create'    => 'Thêm mới',
        'status'    => 'Đổi trạng thái',
        'move'      => 'Chuyển nhóm',
        'file.del'  => 'Xoá file ảnh',
        'blocked'   => 'BỊ CHẶN (nghi giả mạo)',
    );
    $tables = array(
        'news' => 'Tin tức', 'page' => 'Trang', 'form_sign' => 'Đăng ký học',
        'form_advisory' => 'Tư vấn', 'call_to_action' => 'Lead trang chủ',
        'gallery' => 'Thư viện ảnh', 'gallery_imgs' => 'Ảnh trong thư viện',
        'trainee_evaluate' => 'Đánh giá học viên', 'shop_order' => 'Đơn hàng',
        'support_online' => 'Hỗ trợ trực tuyến', 'api_keys' => 'Khóa API',
    );

    if ($action === 'login.ok')   return 'Đăng nhập thành công';
    if ($action === 'login.fail') return 'Đăng nhập SAI mật khẩu';
    if ($action === 'logout')     return 'Đăng xuất';

    $parts = explode('.', $action, 2);
    if (count($parts) === 2) {
        $t = isset($tables[$parts[0]]) ? $tables[$parts[0]] : $parts[0];
        $a = isset($map[$parts[1]]) ? $map[$parts[1]] : $parts[1];
        return $a . ' — ' . $t;
    }
    return isset($map[$action]) ? $map[$action] : $action;
}

/* ---------- #11 Xuất Excel (CSV UTF-8 BOM) ---------- */
/**
 * $filename : tên file .csv
 * $headers  : mảng tiêu đề cột (theo thứ tự)
 * $rows     : mảng dòng; mỗi dòng là mảng giá trị theo đúng thứ tự $headers
 */
function c12_export_csv($filename, $headers, $rows)
{
    while (function_exists('ob_get_level') && ob_get_level() > 0) { @ob_end_clean(); }
    if (!headers_sent()) {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . str_replace('"', '', $filename) . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
    echo "\xEF\xBB\xBF"; // BOM để Excel nhận UTF-8 (giữ dấu tiếng Việt)
    $out = fopen('php://output', 'w');
    fputcsv($out, $headers);
    foreach ($rows as $r) {
        // ép mọi ô về chuỗi, chống Excel formula-injection (=,+,-,@ đầu ô)
        $line = array();
        foreach ($r as $cell) {
            $cell = (string)$cell;
            if ($cell !== '' && strpos("=+-@\t\r", $cell[0]) !== false) $cell = "'" . $cell;
            $line[] = $cell;
        }
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

/* ---------- #5 Thùng rác (soft-delete generic, KHÔNG đụng public site) ---------- */
/**
 * Chuyển 1 dòng sang bảng `_trash` rồi xoá bản gốc.
 * $table   : tên bảng gốc (vd 'form_sign')
 * $pkCol   : cột khoá chính (vd 'sign_id')
 * $id      : giá trị khoá (ép số nếu numeric)
 * $labelCol: cột dùng làm nhãn hiển thị trong thùng rác (vd 'sign_name')
 * Trả TRUE nếu thành công.
 */
function c12_trash_move($table, $pkCol, $id, $labelCol = '')
{
    $db = $GLOBALS['obMySQLi'];
    $id = is_numeric($id) ? (0 + $id) : 0;
    if ($id <= 0) return false;
    $tableSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $pkSafe    = preg_replace('/[^a-zA-Z0-9_]/', '', $pkCol);

    $res = $db->query("SELECT * FROM `$tableSafe` WHERE `$pkSafe`=$id");
    if ($res === false) return false;
    $row = $res->fetch_assoc();
    if (!$row) return false;

    $label = ($labelCol !== '' && isset($row[$labelCol])) ? $row[$labelCol] : '';
    $json  = json_encode($row, JSON_UNESCAPED_UNICODE);
    // 🔴 AN TOÀN DỮ LIỆU: nếu KHÔNG serialize được (byte utf8 hỏng, v.v.) thì
    // DỪNG NGAY — TUYỆT ĐỐI không xoá bản gốc khi chưa có bản sao hợp lệ.
    if ($json === false || $json === null || $json === '') return false;

    $by = isset($_SESSION['ss_admin']) ? $_SESSION['ss_admin'] : 'admin';
    $now = time();

    $jsonE  = $db->real_escape_string($json);
    $labelE = $db->real_escape_string($label);
    $byE    = $db->real_escape_string($by);
    $ins = "INSERT INTO `_trash` (src_table, src_pk, src_id, row_json, label, deleted_at, deleted_by) "
         . "VALUES ('$tableSafe','$pkSafe','$id','$jsonE','$labelE',$now,'$byE')";
    if ($db->query($ins) === false) return false;

    // 🔴 XÁC MINH bản sao đã lưu ĐẦY ĐỦ (chống cột row_json bị cắt cụt do
    // charset/kích thước) TRƯỚC khi xoá gốc. Đọc lại đúng dòng vừa chèn.
    $trashId = isset($db->insert_id) ? (0 + $db->insert_id) : 0;
    $verified = false;
    if ($trashId > 0) {
        $chk = $db->query("SELECT row_json FROM `_trash` WHERE trash_id=$trashId");
        if ($chk !== false) {
            $back = $chk->fetch_assoc();
            if ($back && isset($back['row_json'])) {
                $decoded = json_decode($back['row_json'], true);
                // đủ cột <=> khôi phục được nguyên vẹn
                if (is_array($decoded) && count($decoded) === count($row)) {
                    $verified = true;
                }
            }
        }
    }
    if (!$verified) {
        // bản sao KHÔNG toàn vẹn → xoá bản sao lỗi, GIỮ NGUYÊN bản gốc
        if ($trashId > 0) $db->query("DELETE FROM `_trash` WHERE trash_id=$trashId");
        return false;
    }

    // Chỉ xoá bản gốc khi bản sao đã được xác minh đầy đủ
    $del = $db->query("DELETE FROM `$tableSafe` WHERE `$pkSafe`=$id");
    if ($del === false) {
        // 🔴 Xoá gốc THẤT BẠI ⇒ phải dọn bản sao vừa tạo, nếu không sẽ tồn tại
        //    ĐỒNG THỜI bản ghi sống + mục thùng rác của chính nó. NV thấy mục rác
        //    "thừa" → bấm Xoá vĩnh viễn → xoá mất ảnh của bản ghi đang sống.
        if ($trashId > 0) $db->query("DELETE FROM `_trash` WHERE trash_id=$trashId");
        c12_audit($tableSafe . '.trash', $tableSafe, $id, c12_audit_label($tableSafe, $label), array('loi' => 'xoá bản gốc thất bại'), false);
        return false;
    }

    c12_audit($tableSafe . '.trash', $tableSafe, $id, c12_audit_label($tableSafe, $label), array('trash_id' => $trashId));
    return true;
}

/* Danh sách dòng trong thùng rác (mới nhất trước). $table='' = tất cả. */
function c12_trash_list($table = '', $limit = '')
{
    $db = $GLOBALS['obMySQLi'];
    $sql = "SELECT * FROM `_trash` ";
    if ($table !== '') {
        $t = $db->real_escape_string($table);
        $sql .= "WHERE src_table='$t' ";
    }
    $sql .= "ORDER BY trash_id DESC ";
    if (!empty($limit) && is_array($limit)) $sql .= 'LIMIT ' . (0 + $limit[0]) . ',' . (0 + $limit[1]);
    $res = $db->query($sql);
    if ($res === false) return array();
    $out = array();
    while ($r = $res->fetch_assoc()) $out[] = $r;
    return $out;
}

function c12_trash_count($table = '')
{
    $db = $GLOBALS['obMySQLi'];
    $sql = "SELECT count(*) AS c FROM `_trash` ";
    if ($table !== '') { $t = $db->real_escape_string($table); $sql .= "WHERE src_table='$t' "; }
    $res = $db->query($sql);
    if ($res === false) return 0;
    $r = $res->fetch_assoc();
    return $r ? (int)$r['c'] : 0;
}

/* Khôi phục 1 dòng: chèn lại vào bảng gốc rồi xoá khỏi thùng rác. */
function c12_trash_restore($trashId)
{
    $db = $GLOBALS['obMySQLi'];
    $trashId = 0 + $trashId;
    if ($trashId <= 0) return false;

    $res = $db->query("SELECT * FROM `_trash` WHERE trash_id=$trashId");
    if ($res === false) return false;
    $t = $res->fetch_assoc();
    if (!$t) return false;

    $row = json_decode($t['row_json'], true);
    if (!is_array($row) || empty($row)) return false;

    $tableSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $t['src_table']);
    $cols = array(); $vals = array();
    foreach ($row as $col => $val) {
        $cols[] = '`' . preg_replace('/[^a-zA-Z0-9_]/', '', $col) . '`';
        if ($val === null) { $vals[] = 'NULL'; }
        else {
            if (!is_scalar($val)) $val = json_encode($val); // phòng row_json bị sửa tay lồng mảng
            $vals[] = "'" . $db->real_escape_string((string)$val) . "'";
        }
    }
    $ins = "INSERT INTO `$tableSafe` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ")";
    if ($db->query($ins) === false) {
        c12_audit($tableSafe . '.restore', $tableSafe, $t['src_id'], c12_audit_label($tableSafe, $t['label']), array('loi' => 'chèn lại bản ghi thất bại'), false);
        return false;
    }

    // 🔴 Chỉ báo THÀNH CÔNG khi mục rác thật sự bị gỡ. Nếu DELETE lỗi mà vẫn trả
    //    true, NV sẽ thấy mục rác còn sót dù bản ghi đã sống lại → bấm "Xoá vĩnh
    //    viễn" mục sót đó → xoá mất ảnh của bản ghi ĐANG SỐNG.
    //    (c12_unlink_unused là lớp chặn thứ 2 cho đúng tình huống này.)
    if ($db->query("DELETE FROM `_trash` WHERE trash_id=$trashId") === false) {
        c12_audit($tableSafe . '.restore', $tableSafe, $t['src_id'], c12_audit_label($tableSafe, $t['label']), array('loi' => 'gỡ khỏi thùng rác thất bại — bản ghi ĐÃ sống lại'), false);
        return false;
    }

    c12_audit($tableSafe . '.restore', $tableSafe, $t['src_id'], c12_audit_label($tableSafe, $t['label']));
    return true;
}

/* Xoá vĩnh viễn 1 dòng khỏi thùng rác (chỉ dùng khi NV xác nhận).
 * 🔴 Dọn KÈM file ảnh + dòng bảng con — giữ đúng hành vi xoá cũ của CMS
 *    (deleteNews/deleteTrainee/deleteGallery vốn @unlink ảnh). Nếu không dọn,
 *    ảnh rác nằm lại máy chủ VĨNH VIỄN và ảnh con của thư viện thành mồ côi.
 *    Chỉ dọn ở bước PURGE — lúc trong Thùng rác vẫn giữ đủ để khôi phục. */
function c12_trash_purge($trashId)
{
    $db = $GLOBALS['obMySQLi'];
    $trashId = 0 + $trashId;
    if ($trashId <= 0) return false;

    $res = $db->query("SELECT * FROM `_trash` WHERE trash_id=$trashId");
    if ($res === false) return false;
    $t = $res->fetch_assoc();
    if (!$t) return false;

    $row = json_decode($t['row_json'], true);
    if (!is_array($row)) $row = array();

    $srcTable = isset($t['src_table']) ? $t['src_table'] : '';

    c12_purge_assets($srcTable, $row);

    $done = $db->query("DELETE FROM `_trash` WHERE trash_id=$trashId") !== false;

    // Đây là thao tác KHÔNG CỨU ĐƯỢC ⇒ nhật ký giữ bản sao để còn dựng lại được
    // nếu xoá nhầm. NHƯNG chỉ với NỘI DUNG CÔNG TY tự viết.
    //
    // 🔴 KHÔNG chép dữ liệu KHÁCH HÀNG (tên/SĐT/email) vào nhật ký:
    //    xoá vĩnh viễn 1 khách thường là vì khách YÊU CẦU XOÁ (NĐ 13/2023 quyền
    //    được xoá dữ liệu). Nếu nhật ký giữ bản sao thì coi như CHƯA XOÁ, vẫn
    //    vi phạm — mà bảng nhật ký còn không có ai dọn. Với các bảng khách, chỉ
    //    ghi "ai xoá cái gì lúc nào" là đủ để truy trách nhiệm.
    //   · form_sign/form_advisory/call_to_action/shop_order*: khách hàng.
    //   · trainee_evaluate: TÊN HỌC VIÊN THẬT + bài đánh giá của họ — học viên xin
    //     gỡ thì phải gỡ sạch, không giữ bản sao ở chỗ không ai dọn.
    //   (support_online KHÔNG nằm đây: đó là số hotline của chính công ty, đang
    //    hiện công khai trên web — không phải dữ liệu cá nhân của khách.)
    $detail = c12_is_customer_table($srcTable)
        ? array('ghi_chu' => 'Dữ liệu cá nhân — không lưu bản sao trong nhật ký (quyền được xoá).')
        : array('ban_sao_du_lieu' => $row);

    c12_audit(
        $srcTable . '.purge', $srcTable,
        isset($t['src_id']) ? $t['src_id'] : '',
        c12_audit_label($srcTable, isset($t['label']) ? $t['label'] : ''),
        $detail,
        $done
    );

    return $done;
}

/* Dọn file ảnh + bảng con gắn với 1 bản ghi bị xoá vĩnh viễn. */
function c12_purge_assets($srcTable, $row)
{
    $db      = $GLOBALS['obMySQLi'];
    $newsDir = defined('NEWS_DIR')    ? NEWS_DIR    : '';
    $galDir  = defined('GALLERY_DIR') ? GALLERY_DIR : '';

    switch ($srcTable) {
        case 'news':
            c12_unlink_unused($newsDir, c12_row_get($row, 'news_image'),       'news', 'news_image');
            c12_unlink_unused($newsDir, c12_row_get($row, 'news_image_title'), 'news', 'news_image_title');
            break;

        case 'trainee_evaluate':
            c12_unlink_unused($newsDir, c12_row_get($row, 'trainee_image'), 'trainee_evaluate', 'trainee_image');
            break;

        case 'gallery':
            // xoá ảnh con (file + dòng) — deleteGallery cũ làm đúng việc này
            $gidRaw = c12_row_get($row, 'gallery_id');
            $gid = is_numeric($gidRaw) ? (0 + $gidRaw) : 0;
            if ($gid > 0) {
                $files = array();
                $r = $db->query("SELECT * FROM `gallery_imgs` WHERE gallery_id=$gid");
                if ($r !== false) {
                    while ($img = $r->fetch_assoc()) {
                        $files[] = isset($img['image_file']) ? $img['image_file'] : '';
                    }
                }
                // xoá dòng TRƯỚC, rồi mới dọn file: lúc đó truy vấn "còn ai dùng file này"
                // mới cho kết quả đúng (nếu ảnh dùng chung với gallery khác ⇒ giữ file).
                $db->query("DELETE FROM `gallery_imgs` WHERE gallery_id=$gid");
                foreach ($files as $f) {
                    c12_unlink_unused($galDir, $f, 'gallery_imgs', 'image_file');
                }
            }
            break;

        case 'gallery_imgs':
            c12_unlink_unused($galDir, c12_row_get($row, 'image_file'), 'gallery_imgs', 'image_file');
            break;
    }
}

/* Xoá file ảnh CHỈ KHI không còn bản ghi SỐNG nào trỏ tới nó.
 * 🔴 Vì sao bắt buộc: thùng rác KHÔNG lưu bytes ảnh, xoá file là mất vĩnh viễn.
 *    Có 2 đường dẫn tới thảm hoạ nếu thiếu chốt này:
 *    (1) 2 bản ghi dùng chung 1 tên ảnh → purge bản này làm mất ảnh của bản kia;
 *    (2) khôi phục xong nhưng mục rác còn sót (DELETE _trash lỗi) → NV purge mục
 *        sót → xoá ảnh của bản ghi ĐANG SỐNG.
 *    Hỏi lại DB là rẻ; đoán sai là mất ảnh không cứu được. */
function c12_unlink_unused($dir, $file, $table, $col)
{
    if (!is_string($file) || $file === '') return false;

    $db        = $GLOBALS['obMySQLi'];
    $tableSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $colSafe   = preg_replace('/[^a-zA-Z0-9_]/', '', $col);
    $fileE     = $db->real_escape_string($file);

    $res = $db->query("SELECT 1 FROM `$tableSafe` WHERE `$colSafe`='$fileE' LIMIT 1");
    if ($res === false) return false;              // hỏi không được ⇒ KHÔNG xoá (an toàn)
    if ($res->fetch_assoc()) return false;         // còn bản ghi sống dùng ảnh ⇒ GIỮ

    return c12_unlink_safe($dir, $file);
}

/* Đọc 1 field từ mảng row, trả '' nếu thiếu. */
function c12_row_get($row, $key)
{
    return (is_array($row) && isset($row[$key])) ? $row[$key] : '';
}

/* Xoá 1 file ảnh AN TOÀN: chỉ trong thư mục cho phép, chặn path traversal.
 * Tên file trong DB là tên trần (CMS nối GALLERY_DIR/NEWS_DIR + tên) → mọi
 * dấu / \ .. đều là bất thường ⇒ từ chối, KHÔNG xoá. */
function c12_unlink_safe($dir, $file)
{
    if (!is_string($dir) || $dir === '')   return false;
    if (!is_string($file) || $file === '') return false;
    if (strpos($file, '..') !== false)     return false;
    if (strpos($file, '/') !== false)      return false;
    if (strpos($file, '\\') !== false)     return false;
    if (strpos($file, "\0") !== false)     return false;

    $path = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $file;
    if (file_exists($path) && is_file($path)) return @unlink($path);
    return false;
}

/* ---------- #14 nhãn trạng thái lead ---------- */
function c12_lead_statuses()
{
    return array(
        0 => array('Mới',         'default'),
        1 => array('Đã gọi',      'info'),
        2 => array('Đã tư vấn',   'warning'),
        3 => array('Đã đăng ký',  'success'),
    );
}

/* Badge HTML cho 1 trạng thái. */
function c12_status_badge($status)
{
    $map = c12_lead_statuses();
    $s = (int)$status;
    if (!isset($map[$s])) $s = 0;
    list($text, $cls) = $map[$s];
    return '<span class="label label-' . $cls . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>';
}

/* <select> chọn trạng thái (dùng trong hàng bảng hoặc thanh công cụ). */
function c12_status_select($name, $current = '', $withAll = false, $attr = '')
{
    $html = '<select name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" ' . $attr . '>';
    if ($withAll) $html .= '<option value="">-- Tất cả --</option>';
    foreach (c12_lead_statuses() as $val => $info) {
        $sel = ((string)$current === (string)$val) ? ' selected' : '';
        $html .= '<option value="' . $val . '"' . $sel . '>' . htmlspecialchars($info[0], ENT_QUOTES, 'UTF-8') . '</option>';
    }
    $html .= '</select>';
    return $html;
}

/* Đọc bộ lọc chung từ $_GET (dùng ở init/*.php). Trả mảng $filters + query-string. */
function c12_read_filters($opts = array())
{
    $f = array();
    if (isset($_GET['q']) && trim($_GET['q']) !== '')       $f['q'] = trim($_GET['q']);
    if (isset($_GET['status']) && $_GET['status'] !== '')    $f['status'] = (int)$_GET['status'];
    if (isset($_GET['viewed']) && $_GET['viewed'] !== '')    $f['viewed'] = (int)$_GET['viewed'];
    if (isset($_GET['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $_GET['from'])) $f['from'] = $_GET['from'];
    if (isset($_GET['to'])   && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $_GET['to']))   $f['to']   = $_GET['to'];
    return $f;
}

/* Chuỗi query-string của bộ lọc để gắn vào link phân trang / xuất Excel. */
function c12_filters_qs($filters)
{
    $parts = array();
    foreach (array('q','status','viewed','from','to') as $k) {
        if (isset($filters[$k]) && $filters[$k] !== '') {
            $parts[] = urlencode($k) . '=' . urlencode($filters[$k]);
        }
    }
    return $parts ? '&' . implode('&', $parts) : '';
}

/* ---------- tiện ích lọc dùng chung (search / date / status) ---------- */
/* Ép số nguyên an toàn cho id/limit. */
function c12_int($v, $default = 0) { return is_numeric($v) ? (int)$v : $default; }

/* Escape 1 chuỗi cho LIKE (đã bọc trong dấu nháy ở nơi gọi). */
function c12_like($db, $s)
{
    $s = $db->real_escape_string($s);
    // escape ký tự đại diện của LIKE
    return str_replace(array('%', '_'), array('\\%', '\\_'), $s);
}

/* ==========================================================================
 * C12: QUẢN LÝ API KEYS & TÍCH HỢP AUTOMATION (N8N, CHATBOT, WEBHOOK)
 * ========================================================================== */

/**
 * Lấy kết nối MySQLi an toàn trong mọi hoàn cảnh
 */
function c12_api_db()
{
    if (isset($GLOBALS['obMySQLi']) && $GLOBALS['obMySQLi'] instanceof mysqli && !$GLOBALS['obMySQLi']->connect_errno) {
        return $GLOBALS['obMySQLi'];
    }
    global $obMySQLi;
    if ($obMySQLi instanceof mysqli && !$obMySQLi->connect_errno) {
        $GLOBALS['obMySQLi'] = $obMySQLi;
        return $obMySQLi;
    }
    $docRoot = defined('DOCROOT') ? DOCROOT : (dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
    $dbFile = $docRoot . 'config' . DIRECTORY_SEPARATOR . 'db.php';
    if (file_exists($dbFile)) {
        require $dbFile;
        if (isset($obMySQLi) && $obMySQLi instanceof mysqli) {
            $GLOBALS['obMySQLi'] = $obMySQLi;
            return $obMySQLi;
        }
    }
    return null;
}

/**
 * Định nghĩa danh sách các quyền hạn (Scopes) và nhãn hiển thị
 */
function c12_api_scopes()
{
    return array(
        'all'     => array('label' => 'Toàn quyền (Đăng bài, Upload, Thư viện)', 'class' => 'label-primary', 'icon' => 'globe'),
        'publish' => array('label' => 'Chỉ Đăng bài viết (/api/publish-news.php)', 'class' => 'label-success', 'icon' => 'pencil'),
        'upload'  => array('label' => 'Chỉ Upload media (/api/upload-media.php)', 'class' => 'label-info',    'icon' => 'upload'),
        'media'   => array('label' => 'Chỉ Tra cứu ảnh (/api/media-library.php)', 'class' => 'label-warning', 'icon' => 'picture'),
    );
}

/**
 * Sinh token ngẫu nhiên an toàn chuẩn pl_live_ + 48 hex chars (24 bytes)
 */
function c12_api_key_generate_token()
{
    $prefix = 'pl_live_';
    $bytes = '';
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(24);
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(24);
    } else {
        $bytes = md5(uniqid(mt_rand(), true), true) . md5(microtime(true), true);
    }
    return $prefix . bin2hex(substr($bytes, 0, 24));
}

/**
 * Che mờ mã key để hiển thị an toàn: pl_live_••••••••••••••••3a8f
 */
function c12_api_mask_key($key)
{
    $key = (string)$key;
    $len = strlen($key);
    if ($len <= 12) return '••••••••••••';
    $prefix = substr($key, 0, 8); // 'pl_live_'
    $suffix = substr($key, -4);
    $maskedMiddle = str_repeat('•', 24);
    return $prefix . $maskedMiddle . $suffix;
}

/**
 * Lấy danh sách API Keys có lọc và tìm kiếm
 */
function c12_api_key_list($filters = array())
{
    $db = c12_api_db();
    if (!$db) return array();
    $where = array('1=1');

    if (isset($filters['q']) && trim($filters['q']) !== '') {
        $q = c12_like($db, trim($filters['q']));
        $where[] = "(`key_name` LIKE '%$q%' OR `api_key` LIKE '%$q%')";
    }
    if (isset($filters['status']) && $filters['status'] !== '') {
        $status = (int)$filters['status'];
        $where[] = "`status` = $status";
    }
    if (isset($filters['scope']) && $filters['scope'] !== '' && $filters['scope'] !== 'all_scopes') {
        $scope = $db->real_escape_string($filters['scope']);
        $where[] = "`scope` = '$scope'";
    }

    $sql = "SELECT * FROM `api_keys` WHERE " . implode(' AND ', $where) . " ORDER BY `id` DESC";
    $res = $db->query($sql);
    $list = array();
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $list[] = $r;
        }
    }
    return $list;
}

/**
 * Lấy chi tiết 1 API Key theo ID
 */
function c12_api_key_get($id)
{
    $db = c12_api_db();
    if (!$db) return false;
    $id = (int)$id;
    $res = $db->query("SELECT * FROM `api_keys` WHERE `id` = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc();
    }
    return false;
}

/**
 * Thống kê metrics cho dashboard API Key
 */
function c12_api_key_metrics()
{
    $db = c12_api_db();
    $out = array(
        'total' => 0,
        'active' => 0,
        'suspended' => 0,
        'total_requests' => 0
    );
    if (!$db) return $out;

    $res = $db->query("SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN `status` = 1 THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN `status` = 0 THEN 1 ELSE 0 END) AS suspended,
        SUM(`total_requests`) AS total_requests
    FROM `api_keys`");

    if ($res && $r = $res->fetch_assoc()) {
        $out['total'] = (int)$r['total'];
        $out['active'] = (int)$r['active'];
        $out['suspended'] = (int)$r['suspended'];
        $out['total_requests'] = (int)$r['total_requests'];
    }
    return $out;
}

/**
 * Tạo mới 1 API Key
 */
function c12_api_key_create($name, $scope = 'all', $rateLimit = 60)
{
    $db = c12_api_db();
    if (!$db) {
        return array('error' => 'Lỗi kết nối cơ sở dữ liệu.', 'key' => '');
    }

    $name = trim(strip_tags($name));
    if (empty($name)) {
        return array('error' => 'Vui lòng nhập tên ứng dụng / đơn vị tích hợp cho API Key.', 'key' => '');
    }

    $validScopes = array('all', 'publish', 'upload', 'media');
    if (!in_array($scope, $validScopes)) {
        $scope = 'all';
    }

    $rateLimit = (int)$rateLimit;
    if ($rateLimit <= 0) $rateLimit = 60;

    $newToken = c12_api_key_generate_token();
    $now = time();

    $stmt = $db->prepare("INSERT INTO `api_keys` (`key_name`, `api_key`, `scope`, `rate_limit`, `status`, `total_requests`, `last_used_at`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, 1, 0, 0, ?, ?)");
    if (!$stmt) {
        return array('error' => 'Lỗi chuẩn bị cơ sở dữ liệu: ' . $db->error, 'key' => '');
    }

    $stmt->bind_param('sssiii', $name, $newToken, $scope, $rateLimit, $now, $now);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return array('error' => 'Không thể lưu API Key: ' . $err, 'key' => '');
    }

    $insertId = $stmt->insert_id;
    $stmt->close();

    if (function_exists('c12_audit')) {
        c12_audit('apikey.create', 'api_keys', $insertId, $name, array('scope' => $scope, 'rate_limit' => $rateLimit));
    }

    return array('error' => '', 'key' => $newToken, 'id' => $insertId);
}

/**
 * Cập nhật API Key (Tên, Scope, Rate limit, Trạng thái)
 */
function c12_api_key_update($id, $name, $scope = 'all', $rateLimit = 60, $status = 1)
{
    $db = c12_api_db();
    if (!$db) return 'Lỗi kết nối cơ sở dữ liệu.';

    $id = (int)$id;
    $name = trim(strip_tags($name));
    if (empty($name)) {
        return 'Tên ứng dụng / đơn vị tích hợp không được để trống.';
    }

    $validScopes = array('all', 'publish', 'upload', 'media');
    if (!in_array($scope, $validScopes)) {
        $scope = 'all';
    }

    $rateLimit = (int)$rateLimit;
    if ($rateLimit <= 0) $rateLimit = 60;
    $status = $status ? 1 : 0;
    $now = time();

    $stmt = $db->prepare("UPDATE `api_keys` SET `key_name` = ?, `scope` = ?, `rate_limit` = ?, `status` = ?, `updated_at` = ? WHERE `id` = ?");
    if (!$stmt) {
        return 'Lỗi cơ sở dữ liệu: ' . $db->error;
    }

    $stmt->bind_param('ssiiii', $name, $scope, $rateLimit, $status, $now, $id);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return 'Không thể cập nhật API Key: ' . $err;
    }
    $stmt->close();

    if (function_exists('c12_audit')) {
        c12_audit('apikey.update', 'api_keys', $id, $name, array('scope' => $scope, 'rate_limit' => $rateLimit, 'status' => $status));
    }

    return '';
}

/**
 * Đổi trạng thái Bật / Tạm khóa API Key
 */
function c12_api_key_toggle($id)
{
    $db = c12_api_db();
    if (!$db) return 'Lỗi kết nối cơ sở dữ liệu.';

    $id = (int)$id;
    $current = c12_api_key_get($id);
    if (!$current) {
        return 'Không tìm thấy API Key.';
    }

    $newStatus = $current['status'] ? 0 : 1;
    $now = time();
    $db->query("UPDATE `api_keys` SET `status` = $newStatus, `updated_at` = $now WHERE `id` = $id");

    if (function_exists('c12_audit')) {
        c12_audit('apikey.toggle', 'api_keys', $id, $current['key_name'], array('new_status' => $newStatus));
    }

    return '';
}

/**
 * Cấp lại token mới cho API Key đã có (Regenerate)
 */
function c12_api_key_regenerate($id)
{
    $db = c12_api_db();
    if (!$db) return array('error' => 'Lỗi kết nối cơ sở dữ liệu.', 'key' => '');

    $id = (int)$id;
    $current = c12_api_key_get($id);
    if (!$current) {
        return array('error' => 'Không tìm thấy API Key.', 'key' => '');
    }

    $newToken = c12_api_key_generate_token();
    $now = time();

    $stmt = $db->prepare("UPDATE `api_keys` SET `api_key` = ?, `updated_at` = ? WHERE `id` = ?");
    if (!$stmt) {
        return array('error' => 'Lỗi chuẩn bị DB: ' . $db->error, 'key' => '');
    }

    $stmt->bind_param('sii', $newToken, $now, $id);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return array('error' => 'Không thể đổi mã: ' . $err, 'key' => '');
    }
    $stmt->close();

    if (function_exists('c12_audit')) {
        c12_audit('apikey.regenerate', 'api_keys', $id, $current['key_name']);
    }

    return array('error' => '', 'key' => $newToken);
}

/**
 * Xóa hẳn 1 API Key
 */
function c12_api_key_delete($id)
{
    $db = c12_api_db();
    if (!$db) return 'Lỗi kết nối cơ sở dữ liệu.';

    $id = (int)$id;
    $current = c12_api_key_get($id);
    if (!$current) {
        return 'Không tìm thấy API Key cần xóa.';
    }

    $db->query("DELETE FROM `api_keys` WHERE `id` = $id LIMIT 1");

    if (function_exists('c12_audit')) {
        c12_audit('apikey.delete', 'api_keys', $id, $current['key_name']);
    }

    return '';
}

?>