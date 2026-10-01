<?php
/* CTA (Lead trang chủ). Letri chốt: KHÔNG xoá — chỉ xem + đánh trạng thái + tìm + xuất Excel. */
if (isset($_POST) && !empty($_POST)) {
    // Đánh dấu TRẠNG THÁI hàng loạt — #14
    if (isset($_POST['set_status_multiple']) && isset($_POST['chk_cta']) && is_array($_POST['chk_cta']) && !empty($_POST['chk_cta'])) {
        c12_csrf_guard(); // #9
        $st = (int) (isset($_POST['bulk_status']) ? $_POST['bulk_status'] : 0);
        if ($st < 0 || $st > 3) $st = 0;
        foreach ($_POST['chk_cta'] as $ctaID) {
            updateCallToAction((0 + $ctaID), "cta_status=$st");
        }
        $_SESSION['success'] = 'Đã cập nhật trạng thái cho các mục đã chọn.';
        redirect($_SERVER['HTTP_REFERER']);
    }
}

include_once(CLASS_DIR . 'pagination' . DIRECTORY_SEPARATOR . 'pagination.class' . EXT);

$filters     = c12_read_filters();
$filterQs    = c12_filters_qs($filters);
$rowsPerPage = isset($_GET['pp']) ? min(max(intval($_GET['pp']), 20), 500) : 100;
$curPage     = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
$startRow    = ($curPage - 1) * $rowsPerPage;
$totalRows   = getCallToAction('', '', TRUE, $filters);
$hrefAdvisor = ADMIN_URL . "&p=callToAction" . $filterQs;
$pagi = new pagination();
$pagi->items($totalRows);
$pagi->limit($rowsPerPage);
$pagi->currentPage($curPage);
$pagi->nextLabel('');
$pagi->prevLabel('');
$pagi->nextIcon('&#9658;');
$pagi->prevIcon('&#9668;');
$pagi->target($hrefAdvisor);
$pagi->parameterName('pageNum');

$listCallToAction = getCallToAction('', array($startRow, $rowsPerPage), FALSE, $filters);

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $dataSend = getCallToAction(intval($_GET['id']));
    if (isset($dataSend['cta_id']) && empty($dataSend['cta_id'])) {
        unset($dataSend['cta_id']);
    }
}

?>
