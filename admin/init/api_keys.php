<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - ASIA CMS ADMIN CONTROLLER: API KEYS MODULE
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Handle API Key creation, modification, status toggle, revocation,
 *          regeneration, and metrics calculation.
 * Security: Strict RBAC (Owner only), CSRF guard on all actions, timing-safe.
 * ============================================================================
 */

// 1. Double Permission Guard
if (!function_exists('c12_can_page') || !c12_can_page('api_keys')) {
    $_SESSION['error_msg'] = 'Bạn không có quyền truy cập khu vực Quản lý API Key.';
    if (function_exists('c12_role_home')) {
        redirect(c12_role_home());
    } else {
        redirect(ADMIN_URL);
    }
}

// 2. Handle POST Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    // Action: Thêm mới API Key
    if (isset($_POST['btn_create_api_key'])) {
        c12_csrf_guard();

        $name = isset($_POST['txt_key_name']) ? $_POST['txt_key_name'] : '';
        $scope = isset($_POST['sel_scope']) ? $_POST['sel_scope'] : 'all';
        $rateLimit = isset($_POST['txt_rate_limit']) ? (int)$_POST['txt_rate_limit'] : 60;

        $res = c12_api_key_create($name, $scope, $rateLimit);
        if (!empty($res['error'])) {
            $_SESSION['error_msg'] = $res['error'];
        } else {
            $_SESSION['success'] = 'Tạo API Key mới thành công!';
            $_SESSION['newly_generated_token'] = array(
                'id' => $res['id'],
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'token' => $res['key']
            );
        }
        redirect(ADMIN_URL . '&p=api_keys');
    }

    // Action: Cập nhật thông tin API Key (Sửa)
    if (isset($_POST['btn_update_api_key']) && isset($_POST['hd_key_id'])) {
        c12_csrf_guard();

        $id = (int)$_POST['hd_key_id'];
        $name = isset($_POST['txt_key_name']) ? $_POST['txt_key_name'] : '';
        $scope = isset($_POST['sel_scope']) ? $_POST['sel_scope'] : 'all';
        $rateLimit = isset($_POST['txt_rate_limit']) ? (int)$_POST['txt_rate_limit'] : 60;
        $status = isset($_POST['chk_status']) ? 1 : 0;

        $err = c12_api_key_update($id, $name, $scope, $rateLimit, $status);
        if (!empty($err)) {
            $_SESSION['error_msg'] = $err;
        } else {
            $_SESSION['success'] = 'Đã lưu thay đổi API Key #' . $id . ' thành công.';
        }
        redirect(ADMIN_URL . '&p=api_keys');
    }

    // Action: Bật / Tắt trạng thái hoạt động (Toggle)
    if (isset($_POST['btn_toggle_api_key']) && isset($_POST['hd_key_id'])) {
        c12_csrf_guard();

        $id = (int)$_POST['hd_key_id'];
        $err = c12_api_key_toggle($id);
        if (!empty($err)) {
            $_SESSION['error_msg'] = $err;
        } else {
            $_SESSION['success'] = 'Đã cập nhật trạng thái API Key #' . $id . '.';
        }
        redirect(ADMIN_URL . '&p=api_keys');
    }

    // Action: Cấp lại token mới (Regenerate)
    if (isset($_POST['btn_regenerate_api_key']) && isset($_POST['hd_key_id'])) {
        c12_csrf_guard();

        $id = (int)$_POST['hd_key_id'];
        $res = c12_api_key_regenerate($id);
        if (!empty($res['error'])) {
            $_SESSION['error_msg'] = $res['error'];
        } else {
            $keyInfo = c12_api_key_get($id);
            $_SESSION['success'] = 'Đã cấp lại mã token mới thành công cho Key #' . $id . '! Vui lòng cập nhật vào ứng dụng kết nối.';
            $_SESSION['newly_generated_token'] = array(
                'id' => $id,
                'name' => htmlspecialchars($keyInfo ? $keyInfo['key_name'] : 'API Key #' . $id, ENT_QUOTES, 'UTF-8'),
                'token' => $res['key']
            );
        }
        redirect(ADMIN_URL . '&p=api_keys');
    }

    // Action: Xóa hẳn API Key
    if (isset($_POST['btn_delete_api_key']) && isset($_POST['hd_key_id'])) {
        c12_csrf_guard();

        $id = (int)$_POST['hd_key_id'];
        $err = c12_api_key_delete($id);
        if (!empty($err)) {
            $_SESSION['error_msg'] = $err;
        } else {
            $_SESSION['success'] = 'Đã xóa vĩnh viễn API Key #' . $id . '.';
        }
        redirect(ADMIN_URL . '&p=api_keys');
    }
}

// 3. Prepare Filters & Data View
$filters = array();
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $filters['q'] = trim($_GET['q']);
}
if (isset($_GET['status']) && $_GET['status'] !== '' && $_GET['status'] !== 'all') {
    $filters['status'] = (int)$_GET['status'];
}
if (isset($_GET['scope']) && $_GET['scope'] !== '' && $_GET['scope'] !== 'all_scopes') {
    $filters['scope'] = trim($_GET['scope']);
}

$listApiKeys = c12_api_key_list($filters);
$metrics = c12_api_key_metrics();
$scopes = c12_api_scopes();

// Check if currently editing a specific key
$editKey = false;
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $editKey = c12_api_key_get((int)$_GET['edit_id']);
}
