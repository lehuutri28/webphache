<?php
/**
 * #21 Người dùng — thêm / sửa / khoá tài khoản.
 * 🔴 CHỈ vai trò 'owner' (Chủ/Quản lý) vào được — chốt chặn ở admin/index.php + ở đây.
 */

// Chốt chặn kép: kể cả ai đó gọi thẳng file này.
if (!c12_can_page('users')) {
    $_SESSION['error_msg'] = 'Bạn không có quyền quản lý người dùng.';
    redirect(ADMIN_URL);
}

if (isset($_POST) && !empty($_POST)) {

    // Thêm người dùng
    if (isset($_POST['add_user'])) {
        c12_csrf_guard();

        $err = c12_user_add(
            isset($_POST['txt_username']) ? $_POST['txt_username'] : '',
            isset($_POST['txt_password']) ? $_POST['txt_password'] : '',
            isset($_POST['txt_fullname']) ? $_POST['txt_fullname'] : '',
            isset($_POST['sel_role'])     ? $_POST['sel_role']     : ''
        );

        if ($err !== '') $_SESSION['error_msg'] = $err;
        else             $_SESSION['success']   = 'Đã thêm người dùng. Báo cho họ tên đăng nhập + mật khẩu.';

        redirect($_SERVER['HTTP_REFERER']);
    }

    // Sửa người dùng
    if (isset($_POST['edit_user']) && isset($_POST['hd_user_id'])) {
        c12_csrf_guard();

        $err = c12_user_update(
            intval($_POST['hd_user_id']),
            isset($_POST['txt_fullname']) ? $_POST['txt_fullname'] : '',
            isset($_POST['sel_role'])     ? $_POST['sel_role']     : '',
            isset($_POST['chk_active'])   ? 1 : 0,
            isset($_POST['txt_password']) ? $_POST['txt_password'] : ''
        );

        if ($err !== '') $_SESSION['error_msg'] = $err;
        else             $_SESSION['success']   = 'Đã lưu thay đổi.';

        redirect($_SERVER['HTTP_REFERER']);
    }
}

$userList  = c12_user_list();
$userRoles = c12_roles();

$editUser = false;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $editUser = c12_user_get(intval($_GET['id']));
}
?>
