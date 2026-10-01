<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - ASIA CMS ADMIN VIEW: API KEYS MANAGEMENT
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Professional Admin UI to manage API Keys, scopes, rate limits,
 *          usage telemetry, and developer integration documentation.
 * ============================================================================
 */

$isEdit = ($editKey !== false);
?>

<style>
/* Modern styling matching Asia CMS + Bootstrap 3 */
.c12-api-kpi-card {
    background: #fff;
    border: 1px solid #e1e8ed;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.c12-api-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.c12-api-kpi-num {
    font-size: 26px;
    font-weight: 700;
    line-height: 1.2;
    margin-top: 4px;
}
.c12-api-kpi-label {
    color: #6c7a89;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.c12-key-code {
    font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
    font-size: 12.5px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 4px 8px;
    border-radius: 4px;
    color: #2c3e50;
    word-break: break-all;
    display: inline-block;
}
.c12-action-btn-group .btn {
    margin-right: 3px;
}
.c12-scope-badge {
    font-size: 11px;
    padding: 3px 7px;
    border-radius: 3px;
    font-weight: 600;
}
.c12-new-token-alert {
    background: #fcf8e3;
    border: 2px solid #8a6d3b;
    border-radius: 6px;
    padding: 16px 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(138, 109, 59, 0.15);
}
.c12-copy-btn {
    cursor: pointer;
    transition: all 0.2s;
}
.c12-copy-btn:hover {
    background-color: #31b0d5;
    color: #fff;
}
.c12-docs-box pre {
    background: #282c34;
    color: #abb2bf;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 12px;
    border: none;
}
</style>

<!-- 1. ALERT NOTIFICATIONS -->
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <b><span class="glyphicon glyphicon-ok-sign"></span> <?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?></b>
    </div>
<?php unset($_SESSION['success']); } ?>

<?php if (isset($_SESSION['error_msg'])) { ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <b><span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8'); ?></b>
    </div>
<?php unset($_SESSION['error_msg']); } ?>

<!-- NEW KEY BANNER (HIỂN THỊ DUY NHẤT 1 LẦN KHI VỪA SINH KEY MỚI) -->
<?php if (isset($_SESSION['newly_generated_token'])) { 
    $genData = $_SESSION['newly_generated_token'];
?>
    <div class="c12-new-token-alert">
        <div class="media">
            <div class="media-left media-middle">
                <span class="glyphicon glyphicon-lock text-warning" style="font-size: 36px; margin-right: 12px;"></span>
            </div>
            <div class="media-body">
                <h4 class="media-heading text-warning" style="font-weight: 700;">
                    <span class="glyphicon glyphicon-info-sign"></span> HÃY SAO CHÉP MÃ KHÓA API MỚI NGAY BÂY GIỜ!
                </h4>
                <p style="margin-bottom: 8px; color: #555;">
                    Khóa API cho <strong><?php echo $genData['name']; ?></strong> vừa được tạo. Để đảm bảo an toàn, mã bí mật này chỉ được hiển thị đầy đủ một lần duy nhất tại đây.
                </p>
                <div class="input-group" style="max-width: 750px;">
                    <input type="text" id="c12_raw_new_token" class="form-control" style="font-family: Menlo, Monaco, Consolas, monospace; font-size: 14px; font-weight: bold; color: #1e7e34; background: #fff;" value="<?php echo htmlspecialchars($genData['token'], ENT_QUOTES, 'UTF-8'); ?>" readonly />
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button" onclick="c12CopyInputText('c12_raw_new_token', this)">
                            <span class="glyphicon glyphicon-copy"></span> Sao Chép Key
                        </button>
                    </span>
                </div>
                <div class="small text-muted" style="margin-top: 6px;">
                    <span class="glyphicon glyphicon-warning-sign"></span> Hãy lưu mã này vào phần cấu hình n8n (Credentials/Environment Header) hoặc ứng dụng của bạn trước khi tải lại trang.
                </div>
            </div>
        </div>
    </div>
<?php unset($_SESSION['newly_generated_token']); } ?>

<!-- 2. KPI TELEMETRY METRIC WIDGETS -->
<div class="row">
    <div class="col-sm-3">
        <div class="c12-api-kpi-card" style="border-left: 4px solid #337ab7;">
            <div class="c12-api-kpi-label">Tổng Khóa API</div>
            <div class="c12-api-kpi-num text-primary">
                <span class="glyphicon glyphicon-lock"></span> <?php echo number_format($metrics['total']); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="c12-api-kpi-card" style="border-left: 4px solid #5cb85c;">
            <div class="c12-api-kpi-label">Đang Hoạt Động</div>
            <div class="c12-api-kpi-num text-success">
                <span class="glyphicon glyphicon-ok-circle"></span> <?php echo number_format($metrics['active']); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="c12-api-kpi-card" style="border-left: 4px solid #f0ad4e;">
            <div class="c12-api-kpi-label">Đang Tạm Khóa</div>
            <div class="c12-api-kpi-num text-warning">
                <span class="glyphicon glyphicon-ban-circle"></span> <?php echo number_format($metrics['suspended']); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="c12-api-kpi-card" style="border-left: 4px solid #5bc0de;">
            <div class="c12-api-kpi-label">Tổng Request Phục Vụ</div>
            <div class="c12-api-kpi-num text-info">
                <span class="glyphicon glyphicon-transfer"></span> <?php echo number_format($metrics['total_requests']); ?>
            </div>
        </div>
    </div>
</div>

<!-- 3. FORM THÊM MỚI / CHỈNH SỬA API KEY -->
<div class="panel <?php echo $isEdit ? 'panel-warning' : 'panel-primary'; ?>">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon <?php echo $isEdit ? 'glyphicon-edit' : 'glyphicon-plus-sign'; ?>"></span>
            <?php echo $isEdit ? 'Chỉnh Sửa API Key #' . (int)$editKey['id'] . ' (' . htmlspecialchars($editKey['key_name'], ENT_QUOTES, 'UTF-8') . ')' : 'Tạo Khóa API Key Mới'; ?>
        </h3>
    </div>
    <div class="panel-body">
        <form method="post" action="<?php echo ADMIN_URL ?>&p=api_keys">
            <?php echo c12_csrf_field(); ?>
            <?php if ($isEdit) { ?>
                <input type="hidden" name="hd_key_id" value="<?php echo (int)$editKey['id']; ?>" />
            <?php } ?>

            <div class="row">
                <div class="col-sm-4 form-group">
                    <label>Tên Ứng Dụng / Gợi Nhớ <span class="text-danger">*</span></label>
                    <input type="text" name="txt_key_name" class="form-control" required
                           placeholder="vd: n8n Auto Post News (WF-018)"
                           value="<?php echo $isEdit ? htmlspecialchars($editKey['key_name'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
                    <span class="help-block small">Tên gợi nhớ mục đích sử dụng của Key (n8n, Chatbot, Webhook,...).</span>
                </div>

                <div class="col-sm-4 form-group">
                    <label>Phạm Vi Truy Cập (Scope) <span class="text-danger">*</span></label>
                    <select name="sel_scope" class="form-control">
                        <?php foreach ($scopes as $scKey => $scData) { 
                            $selected = ($isEdit && $editKey['scope'] === $scKey) ? ' selected' : '';
                        ?>
                            <option value="<?php echo $scKey; ?>"<?php echo $selected; ?>>
                                <?php echo htmlspecialchars($scData['label'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <span class="help-block small">Quyền hạn mà API Key được phép thực hiện trên máy chủ.</span>
                </div>

                <div class="col-sm-2 form-group">
                    <label>Giới Hạn Tần Suất</label>
                    <div class="input-group">
                        <input type="number" name="txt_rate_limit" class="form-control" min="1" max="1000"
                               value="<?php echo $isEdit ? (int)$editKey['rate_limit'] : 60; ?>" />
                        <span class="input-group-addon">req/phút</span>
                    </div>
                    <span class="help-block small">Chống spam hoặc quá tải.</span>
                </div>

                <?php if ($isEdit) { ?>
                <div class="col-sm-2 form-group">
                    <label>Trạng Thái</label>
                    <div class="checkbox" style="margin-top: 6px;">
                        <label>
                            <input type="checkbox" name="chk_status" value="1" <?php echo ((int)$editKey['status'] === 1) ? 'checked' : ''; ?> />
                            <b>Kích hoạt</b>
                        </label>
                    </div>
                    <span class="help-block small">Bỏ chọn = Tạm khóa</span>
                </div>
                <?php } ?>
            </div>

            <button type="submit" name="<?php echo $isEdit ? 'btn_update_api_key' : 'btn_create_api_key'; ?>" value="1" class="btn <?php echo $isEdit ? 'btn-warning' : 'btn-primary'; ?>">
                <span class="glyphicon <?php echo $isEdit ? 'glyphicon-floppy-disk' : 'glyphicon-ok'; ?>"></span>
                <?php echo $isEdit ? 'Lưu Thay Đổi' : 'Tạo Khóa API Ngay'; ?>
            </button>
            <?php if ($isEdit) { ?>
                <a href="<?php echo ADMIN_URL; ?>&p=api_keys" class="btn btn-default">
                    <span class="glyphicon glyphicon-remove"></span> Hủy Bỏ
                </a>
            <?php } ?>
        </form>
    </div>
</div>

<!-- 4. DANH SÁCH API KEYS HIỆN CÓ -->
<div class="panel panel-default">
    <div class="panel-heading" style="background-color: #f5f5f5;">
        <h3 class="panel-title" style="font-weight: 600;">
            <span class="glyphicon glyphicon-list"></span> Danh Sách Khóa Tích Hợp (API Keys)
            <span class="badge pull-right"><?php echo count($listApiKeys); ?> khóa</span>
        </h3>
    </div>
    <div class="panel-body">
        <!-- Bộ lọc & Tìm kiếm -->
        <form method="get" class="form-inline" style="margin-bottom: 16px;">
            <input type="hidden" name="t" value="admin" />
            <input type="hidden" name="p" value="api_keys" />

            <div class="form-group" style="margin-right: 8px;">
                <input type="text" name="q" class="form-control input-sm" placeholder="Tìm tên hoặc mã key..."
                       value="<?php echo isset($filters['q']) ? htmlspecialchars($filters['q'], ENT_QUOTES, 'UTF-8') : ''; ?>" style="min-width: 220px;" />
            </div>

            <div class="form-group" style="margin-right: 8px;">
                <select name="scope" class="form-control input-sm">
                    <option value="all_scopes">-- Mọi quyền (Scope) --</option>
                    <?php foreach ($scopes as $scKey => $scData) { 
                        $sel = (isset($filters['scope']) && $filters['scope'] === $scKey) ? ' selected' : '';
                    ?>
                        <option value="<?php echo $scKey; ?>"<?php echo $sel; ?>><?php echo htmlspecialchars($scData['label'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group" style="margin-right: 8px;">
                <select name="status" class="form-control input-sm">
                    <option value="all">-- Mọi trạng thái --</option>
                    <option value="1" <?php echo (isset($filters['status']) && $filters['status'] === 1) ? 'selected' : ''; ?>>Đang hoạt động</option>
                    <option value="0" <?php echo (isset($filters['status']) && $filters['status'] === 0) ? 'selected' : ''; ?>>Đang tạm khóa</option>
                </select>
            </div>

            <button type="submit" class="btn btn-info btn-sm">
                <span class="glyphicon glyphicon-search"></span> Lọc
            </button>
            <?php if (!empty($filters)) { ?>
                <a href="<?php echo ADMIN_URL; ?>&p=api_keys" class="btn btn-default btn-sm">Xóa Lọc</a>
            <?php } ?>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped" style="vertical-align: middle;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="width: 45px;">#</th>
                        <th style="width: 220px;">Tên Ứng Dụng</th>
                        <th>Mã API Key (Bảo Mật)</th>
                        <th style="width: 140px;">Quyền Hạn</th>
                        <th style="width: 100px;">Rate Limit</th>
                        <th style="width: 160px;">Sử Dụng Gần Nhất</th>
                        <th style="width: 110px;">Trạng Thái</th>
                        <th style="width: 170px; text-align: right;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($listApiKeys)) { 
                    $stt = 0;
                    foreach ($listApiKeys as $row) {
                        $stt++;
                        $keyId = (int)$row['id'];
                        $rawKey = $row['api_key'];
                        $maskedKey = c12_api_mask_key($rawKey);
                        $scopeKey = $row['scope'];
                        $scopeInfo = isset($scopes[$scopeKey]) ? $scopes[$scopeKey] : array('label' => $scopeKey, 'class' => 'label-default', 'icon' => 'tag');
                        $isActive = ((int)$row['status'] === 1);
                ?>
                    <tr>
                        <td><strong><?php echo $keyId; ?></strong></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['key_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <div class="small text-muted">Tạo lúc: <?php echo date('d/m/Y H:i', (int)$row['created_at']); ?></div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <code id="c12_key_display_<?php echo $keyId; ?>" 
                                      class="c12-key-code" 
                                      data-masked="<?php echo htmlspecialchars($maskedKey, ENT_QUOTES, 'UTF-8'); ?>"
                                      data-full="<?php echo htmlspecialchars($rawKey, ENT_QUOTES, 'UTF-8'); ?>"
                                      data-state="masked">
                                    <?php echo htmlspecialchars($maskedKey, ENT_QUOTES, 'UTF-8'); ?>
                                </code>
                                <button type="button" class="btn btn-default btn-xs" style="margin-left: 6px;" 
                                        title="Ẩn/Hiện mã key" 
                                        onclick="c12ToggleKeyMask(<?php echo $keyId; ?>, this)">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-xs c12-copy-btn" style="margin-left: 4px;" 
                                        title="Sao chép mã Key" 
                                        onclick="c12CopyApiKey('<?php echo htmlspecialchars($rawKey, ENT_QUOTES, 'UTF-8'); ?>', this)">
                                    <span class="glyphicon glyphicon-copy"></span>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="label <?php echo $scopeInfo['class']; ?> c12-scope-badge">
                                <span class="glyphicon glyphicon-<?php echo $scopeInfo['icon']; ?>"></span>
                                <?php echo htmlspecialchars($scopeKey, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background-color: #6c7a89;"><?php echo (int)$row['rate_limit']; ?>/phút</span>
                        </td>
                        <td>
                            <div><strong><?php echo number_format((int)$row['total_requests']); ?></strong> requests</div>
                            <div class="small text-muted">
                                <?php 
                                    $lastUsed = (int)$row['last_used_at'];
                                    if ($lastUsed > 0) {
                                        $diff = time() - $lastUsed;
                                        if ($diff < 60) echo 'Vừa xong';
                                        elseif ($diff < 3600) echo floor($diff / 60) . ' phút trước';
                                        elseif ($diff < 86400) echo floor($diff / 3600) . ' giờ trước';
                                        else echo date('d/m/Y H:i', $lastUsed);
                                    } else {
                                        echo 'Chưa gọi';
                                    }
                                ?>
                            </div>
                        </td>
                        <td>
                            <form method="post" action="<?php echo ADMIN_URL; ?>&p=api_keys" style="display:inline;">
                                <?php echo c12_csrf_field(); ?>
                                <input type="hidden" name="hd_key_id" value="<?php echo $keyId; ?>" />
                                <button type="submit" name="btn_toggle_api_key" value="1" 
                                        class="btn btn-xs <?php echo $isActive ? 'btn-success' : 'btn-warning'; ?>"
                                        title="Nhấn để <?php echo $isActive ? 'Tạm khóa' : 'Kích hoạt'; ?>">
                                    <span class="glyphicon <?php echo $isActive ? 'glyphicon-ok' : 'glyphicon-pause'; ?>"></span>
                                    <?php echo $isActive ? 'Hoạt động' : 'Tạm khóa'; ?>
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <div class="c12-action-btn-group">
                                <!-- Nút Sửa -->
                                <a href="<?php echo ADMIN_URL; ?>&p=api_keys&edit_id=<?php echo $keyId; ?>" 
                                   class="btn btn-default btn-xs" title="Sửa thông tin">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>

                                <!-- Nút Cấp lại key mới (Regenerate) -->
                                <button type="button" class="btn btn-info btn-xs" title="Cấp lại token mới"
                                        onclick="c12ConfirmRegenerate(<?php echo $keyId; ?>, '<?php echo htmlspecialchars(addslashes($row['key_name']), ENT_QUOTES, 'UTF-8'); ?>')">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </button>

                                <!-- Nút Xóa -->
                                <button type="button" class="btn btn-danger btn-xs" title="Xóa API Key"
                                        onclick="c12ConfirmDelete(<?php echo $keyId; ?>, '<?php echo htmlspecialchars(addslashes($row['key_name']), ENT_QUOTES, 'UTF-8'); ?>')">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } 
                } else { ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                            <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; margin-bottom: 8px; display:block;"></span>
                            Không tìm thấy API Key nào phù hợp bộ lọc.
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 5. TÀI LIỆU HƯỚNG DẪN TÍCH HỢP CHO DEVELOPER / AUTOMATION -->
<div class="panel panel-info c12-docs-box">
    <div class="panel-heading">
        <h3 class="panel-title" style="font-weight: 700;">
            <span class="glyphicon glyphicon-book"></span> Hướng Dẫn Tích Hợp API Gateway (n8n, Webhook, Mobile App)
        </h3>
    </div>
    <div class="panel-body">
        <p>Mọi request gửi tới các endpoint của <code>phache.com.vn</code> bắt buộc phải đính kèm Header xác thực:</p>
        <pre><code>Authorization: Bearer &lt;MÃ_API_KEY_CỦA_BẠN&gt;
Content-Type: application/json</code></pre>

        <h4 style="font-weight: 600; margin-top: 18px;">Danh Sách Endpoints Tiếp Nhận Dữ Liệu:</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="well well-sm" style="background:#fff;">
                    <strong>1. Đăng Bài Tin Tức Chuẩn SEO</strong>
                    <div class="small text-muted" style="margin: 4px 0;">Method: <code>POST</code> | Scope: <code>publish</code> hoặc <code>all</code></div>
                    <code>https://phache.com.vn/api/publish-news.php</code>
                </div>
            </div>
            <div class="col-md-4">
                <div class="well well-sm" style="background:#fff;">
                    <strong>2. Upload Ảnh Media Trực Tiếp</strong>
                    <div class="small text-muted" style="margin: 4px 0;">Method: <code>POST</code> | Scope: <code>upload</code> hoặc <code>all</code></div>
                    <code>https://phache.com.vn/api/upload-media.php</code>
                </div>
            </div>
            <div class="col-md-4">
                <div class="well well-sm" style="background:#fff;">
                    <strong>3. Tra Cứu Thư Viện 1.600+ Ảnh Đồ Uống</strong>
                    <div class="small text-muted" style="margin: 4px 0;">Method: <code>GET/POST</code> | Scope: <code>media</code> hoặc <code>all</code></div>
                    <code>https://phache.com.vn/api/media-library.php</code>
                </div>
            </div>
        </div>

        <h4 style="font-weight: 600; margin-top: 14px;">Lệnh Test cURL Mẫu:</h4>
        <pre><code>curl -X POST https://phache.com.vn/api/publish-news.php \
  -H "Authorization: Bearer pl_live_xxxxxxxxxxxxxxxxxxxxxxxx" \
  -H "Content-Type: application/json" \
  -d '{"title": "Test Bài Viết Mới", "content_html": "&lt;p&gt;Nội dung bài viết...&lt;/p&gt;"}'</code></pre>
    </div>
</div>

<!-- MODAL XÁC NHẬN CẤP LẠI TOKEN MỚI (REGENERATE) -->
<div class="modal fade" id="c12_modal_regenerate" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo ADMIN_URL; ?>&p=api_keys">
                <?php echo c12_csrf_field(); ?>
                <input type="hidden" name="hd_key_id" id="c12_regen_key_id" value="" />
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-warning" style="font-weight: 700;">
                        <span class="glyphicon glyphicon-warning-sign"></span> Cảnh Báo: Cấp Lại Token Cho API Key
                    </h4>
                </div>
                <div class="modal-body">
                    <p>Bạn đang yêu cầu cấp lại mã token mới cho khóa: <strong id="c12_regen_key_name"></strong>.</p>
                    <div class="alert alert-danger" style="margin-bottom: 0;">
                        <span class="glyphicon glyphicon-alert"></span> <strong>Chú ý quan trọng:</strong> Mã token cũ sẽ bị hủy hiệu lực ngay lập tức. Mọi luồng tự động (n8n, webhook, cron) đang sử dụng mã cũ sẽ bị ngắt kết nối cho đến khi bạn cập nhật mã mới vào ứng dụng.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" name="btn_regenerate_api_key" value="1" class="btn btn-warning">
                        <span class="glyphicon glyphicon-refresh"></span> Đồng Ý Cấp Mã Mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL XÁC NHẬN XÓA API KEY -->
<div class="modal fade" id="c12_modal_delete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo ADMIN_URL; ?>&p=api_keys">
                <?php echo c12_csrf_field(); ?>
                <input type="hidden" name="hd_key_id" id="c12_del_key_id" value="" />
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-danger" style="font-weight: 700;">
                        <span class="glyphicon glyphicon-trash"></span> Xác Nhận Xóa Vĩnh Viễn API Key
                    </h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa API Key: <strong id="c12_del_key_name"></strong> không?</p>
                    <p class="text-danger small">Hành động này không thể hoàn tác. Khóa này sẽ bị xóa khỏi hệ thống và không thể khôi phục.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" name="btn_delete_api_key" value="1" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash"></span> Xác Nhận Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JAVASCRIPT XỬ LÝ TƯƠNG TÁC GIAO DIỆN -->
<script type="text/javascript">
// 1. Sao chép nội dung vào Clipboard
function c12CopyApiKey(text, btnElement) {
    if (!text) return;
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            c12ShowCopiedFeedback(btnElement);
        }).catch(function() {
            c12FallbackCopy(text, btnElement);
        });
    } else {
        c12FallbackCopy(text, btnElement);
    }
}

function c12CopyInputText(inputId, btnElement) {
    var input = document.getElementById(inputId);
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    c12CopyApiKey(input.value, btnElement);
}

function c12FallbackCopy(text, btnElement) {
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
        document.execCommand("copy");
        c12ShowCopiedFeedback(btnElement);
    } catch (e) {
        alert("Không thể tự động sao chép. Vui lòng bôi đen và nhấn Ctrl+C!");
    }
    document.body.removeChild(tempInput);
}

function c12ShowCopiedFeedback(btn) {
    if (!btn) return;
    var originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="glyphicon glyphicon-ok" style="color:#5cb85c;"></span> Đã chép!';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-default');
    setTimeout(function() {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-default');
    }, 2000);
}

// 2. Toggle Ẩn / Hiện Masked Key
function c12ToggleKeyMask(keyId, btn) {
    var el = document.getElementById('c12_key_display_' + keyId);
    if (!el) return;
    var state = el.getAttribute('data-state');
    var icon = btn.querySelector('.glyphicon');

    if (state === 'masked') {
        el.textContent = el.getAttribute('data-full');
        el.setAttribute('data-state', 'full');
        if (icon) {
            icon.className = 'glyphicon glyphicon-eye-close text-danger';
        }
    } else {
        el.textContent = el.getAttribute('data-masked');
        el.setAttribute('data-state', 'masked');
        if (icon) {
            icon.className = 'glyphicon glyphicon-eye-open';
        }
    }
}

// 3. Modal Confirm Helpers
function c12ConfirmRegenerate(id, name) {
    document.getElementById('c12_regen_key_id').value = id;
    document.getElementById('c12_regen_key_name').textContent = '#' + id + ' - ' + name;
    $('#c12_modal_regenerate').modal('show');
}

function c12ConfirmDelete(id, name) {
    document.getElementById('c12_del_key_id').value = id;
    document.getElementById('c12_del_key_name').textContent = '#' + id + ' - ' + name;
    $('#c12_modal_delete').modal('show');
}
</script>
