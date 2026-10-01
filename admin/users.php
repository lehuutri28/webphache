<?php
/**
 * #21 Người dùng — giao diện. Chỉ Chủ/Quản lý (owner) thấy trang này.
 */
if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success"><b><?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></b></div>
<?php unset($_SESSION['success']); }
if (isset($_SESSION['error_msg'])) { ?>
    <div class="alert alert-danger"><b><?php echo htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8') ?></b></div>
<?php unset($_SESSION['error_msg']); }

$isEdit = ($editUser !== false);
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span>
            <?php echo $isEdit ? 'Sửa người dùng' : 'Thêm người dùng' ?></h3>
    </div>
    <div class="panel-body">
        <form method="post" action="">
            <?php echo c12_csrf_field(); ?>
            <?php if ($isEdit) { ?>
                <input type="hidden" name="hd_user_id" value="<?php echo (int) $editUser['user_id'] ?>" />
            <?php } ?>

            <div class="row">
                <div class="col-sm-3 form-group">
                    <label>Tên đăng nhập <span class="text-danger">*</span></label>
                    <?php if ($isEdit) { ?>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($editUser['username'], ENT_QUOTES, 'UTF-8') ?>" disabled />
                        <span class="help-block small">Không đổi được tên đăng nhập (để nhật ký cũ còn truy được).</span>
                    <?php } else { ?>
                        <input type="text" name="txt_username" class="form-control" required
                               placeholder="vd: nhung.cskh" pattern="[a-zA-Z0-9._\-]{3,50}" />
                        <span class="help-block small">Chữ không dấu, số, dấu chấm/gạch. 3–50 ký tự.</span>
                    <?php } ?>
                </div>

                <div class="col-sm-3 form-group">
                    <label>Tên thật</label>
                    <input type="text" name="txt_fullname" class="form-control" placeholder="vd: Nguyễn Thị Nhung"
                           value="<?php echo $isEdit ? htmlspecialchars((string) $editUser['full_name'], ENT_QUOTES, 'UTF-8') : '' ?>" />
                    <span class="help-block small">Để đọc nhật ký cho dễ hiểu.</span>
                </div>

                <div class="col-sm-3 form-group">
                    <label>Mật khẩu <?php echo $isEdit ? '' : '<span class="text-danger">*</span>' ?></label>
                    <input type="text" name="txt_password" class="form-control" autocomplete="off"
                           placeholder="<?php echo $isEdit ? 'Để trống = giữ nguyên' : 'Ít nhất 8 ký tự' ?>" />
                    <span class="help-block small">
                        <?php echo $isEdit ? 'Chỉ điền khi muốn đổi mật khẩu cho người này.' : 'Từ 8 ký tự. Báo lại cho nhân viên.' ?>
                    </span>
                </div>

                <div class="col-sm-3 form-group">
                    <label>Vai trò <span class="text-danger">*</span></label>
                    <select name="sel_role" class="form-control">
                        <?php foreach ($userRoles as $key => $desc) {
                            $sel = ($isEdit && $editUser['role'] === $key) ? ' selected' : ''; ?>
                            <option value="<?php echo $key ?>"<?php echo $sel ?>><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if ($isEdit) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="chk_active" value="1" <?php echo ((int) $editUser['active'] === 1 ? 'checked' : '') ?> />
                        Đang làm việc (bỏ tick = <b>khoá tài khoản</b> khi nghỉ việc — không xoá, để giữ nhật ký)
                    </label>
                </div>
            <?php } ?>

            <button type="submit" name="<?php echo $isEdit ? 'edit_user' : 'add_user' ?>" value="1" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span> <?php echo $isEdit ? 'Lưu thay đổi' : 'Thêm người dùng' ?>
            </button>
            <?php if ($isEdit) { ?>
                <a href="<?php echo ADMIN_URL ?>&p=users" class="btn btn-default">Huỷ</a>
            <?php } ?>
        </form>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-list"></span>
            Danh sách người dùng <span class="badge"><?php echo count($userList) ?></span></h3>
    </div>
    <div class="panel-body">
        <p class="text-info small">
            Mỗi người một tài khoản riêng ⇒ nhật ký ghi đúng tên người làm, và nghỉ việc chỉ cần
            <b>khoá đúng tài khoản đó</b> — không phải đổi mật khẩu cho cả công ty.
        </p>

        <?php if (empty($userList)) { ?>
            <div class="alert alert-warning" style="margin-bottom:0">
                <b>Chưa có tài khoản riêng nào.</b> Cả công ty đang dùng chung tài khoản cũ.
                Thêm người dùng ở khung trên, đăng nhập thử, rồi mới tắt tài khoản dùng chung.
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th><th>Tên thật</th><th>Vai trò</th>
                            <th>Trạng thái</th><th>Đăng nhập gần nhất</th><th style="width:80px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($userList as $u) { ?>
                        <tr class="<?php echo ((int) $u['active'] === 1 ? '' : 'warning') ?>">
                            <td><b><?php echo htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8') ?></b></td>
                            <td><?php echo htmlspecialchars((string) $u['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php
                                $rd = isset($userRoles[$u['role']]) ? $userRoles[$u['role']] : $u['role'];
                                $cls = ($u['role'] === 'owner') ? 'danger' : (($u['role'] === 'editor') ? 'info' : 'default');
                                ?>
                                <span class="label label-<?php echo $cls ?>"><?php echo htmlspecialchars($rd, ENT_QUOTES, 'UTF-8') ?></span>
                            </td>
                            <td>
                                <?php if ((int) $u['active'] === 1) { ?>
                                    <span class="label label-success">Đang làm việc</span>
                                <?php } else { ?>
                                    <span class="label label-default">Đã khoá</span>
                                <?php } ?>
                            </td>
                            <td><small><?php echo !empty($u['last_login']) ? date('d/m/Y H:i', (int) $u['last_login']) : '<em class="text-muted">chưa đăng nhập</em>' ?></small></td>
                            <td>
                                <a href="<?php echo ADMIN_URL ?>&p=users&id=<?php echo (int) $u['user_id'] ?>" class="btn btn-xs btn-default">
                                    <span class="glyphicon glyphicon-pencil"></span> Sửa</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
