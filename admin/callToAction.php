<?php /* C12: call_to_action — XEM + tìm + lọc trạng thái/ngày + Xuất Excel + đánh trạng thái lead.
   KHÔNG nút xoá (giữ dữ liệu cũ theo lệnh Letri). Cột thật: cta_name, cta_phone, cta_course, cta_purpose, cta_create, cta_status. */
if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success"><b><?php echo $_SESSION['success']?></b></div>
<?php unset($_SESSION['success']); } ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a data-toggle="collapse" href="#list-so">
                <span class="glyphicon glyphicon-time"></span> Danh sách khách đăng ký tư vấn (Call To Action)
                <span class="badge"><?php echo isset($totalRows) ? (int)$totalRows : (is_array($listCallToAction) ? count($listCallToAction) : 0) ?></span>
            </a>
        </h3>
    </div>

    <div id="list-so" class="panel-collapse collapse in">
        <div class="panel-body">
            <style>.c12-toolbar{margin-bottom:12px}.c12-toolbar .form-control{display:inline-block;width:auto;height:30px;padding:2px 8px;font-size:13px;vertical-align:middle}.c12-toolbar label{font-weight:normal;margin:0 2px 0 8px;font-size:13px}</style>
            <form method="get" class="c12-toolbar form-inline" action="">
                <input type="hidden" name="t" value="admin" /><input type="hidden" name="p" value="callToAction" />
                <input type="text" name="q" class="form-control" placeholder="Tìm tên / SĐT / khoá / nhu cầu"
                       value="<?php echo isset($filters['q']) ? htmlspecialchars($filters['q'], ENT_QUOTES) : ''?>" />
                <label>Trạng thái</label>
                <?php echo c12_status_select('status', isset($filters['status']) ? $filters['status'] : '', true, 'class="form-control"'); ?>
                <label>Từ</label><input type="date" name="from" class="form-control" value="<?php echo isset($filters['from'])?htmlspecialchars($filters['from'],ENT_QUOTES):''?>" />
                <label>Đến</label><input type="date" name="to" class="form-control" value="<?php echo isset($filters['to'])?htmlspecialchars($filters['to'],ENT_QUOTES):''?>" />
                <button type="submit" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-search"></span> Lọc</button>
                <a href="<?php echo ADMIN_URL?>&p=callToAction" class="btn btn-default btn-xs">Xóa lọc</a>
                <a href="<?php echo ADMIN_URL?>&p=export&type=cta<?php echo isset($filterQs)?$filterQs:''?>" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-download-alt"></span> Xuất Excel</a>
            </form>
            <div class="clearfix" style="margin-bottom:12px">
                <div class="pull-right"><?php if (isset($pagi)) $pagi->show()?></div>
            </div>
            <div class="table-responsive">
                <form id="frm_list_cta" action="" method="post">
                    <?php echo c12_csrf_field(); /* #9 CSRF */ ?>
                    <table class="table table-hover">
                    <!-- #16 tỉ lệ cột — 8 cột KHỚP thead: checkbox·#·Tên·SĐT·Khoá·Nhu cầu·Ngày·Trạng thái.
                         (Trước đây chỉ 7 col ⇒ lệch 1 cột so với 8 <th> ⇒ header giãn vỡ. Fix 24/7.) -->
                    <colgroup>
                        <col style="width:38px" /><!-- checkbox -->
                        <col style="width:44px" /><!-- # -->
                        <col style="width:18%" /><!-- Tên khách -->
                        <col style="width:13%" /><!-- Điện thoại -->
                        <col style="width:26%" /><!-- Khoá quan tâm -->
                        <col style="width:20%" /><!-- Nhu cầu -->
                        <col style="width:13%" /><!-- Ngày gửi -->
                        <col style="width:10%" /><!-- Trạng thái -->
                    </colgroup>
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="chk_cta_all" /></th>
                        <th>#</th>
                        <th>Tên khách</th>
                        <th>Điện thoại</th>
                        <th>Khoá quan tâm</th>
                        <th>Nhu cầu</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (!empty($listCallToAction) && is_array($listCallToAction)) {
                            $stt = isset($startRow) ? (int)$startRow : 0;
                            foreach ($listCallToAction as $v) {
                                $stt++;
                    ?>
                        <tr>
                            <td><input type="checkbox" name="chk_cta[]" class="chk_cta" value="<?php echo (int)$v['cta_id']?>" /></td>
                            <td><?php echo $stt?></td>
                            <td><?php echo htmlspecialchars(isset($v['cta_name']) ? $v['cta_name'] : '')?></td>
                            <td><?php echo htmlspecialchars(isset($v['cta_phone']) ? $v['cta_phone'] : '')?></td>
                            <td><?php echo htmlspecialchars(isset($v['cta_course']) ? $v['cta_course'] : '')?></td>
                            <td><?php echo htmlspecialchars(isset($v['cta_purpose']) ? $v['cta_purpose'] : '')?></td>
                            <td><?php echo htmlspecialchars(isset($v['cta_create']) ? $v['cta_create'] : '')?></td>
                            <td><?php echo c12_status_badge(isset($v['cta_status']) ? $v['cta_status'] : 0)?></td>
                        </tr>
                    <?php
                            }
                        } else {
                    ?>
                        <tr><td colspan="8" style="text-align:center" class="text-muted">
                            <?php echo (isset($filters) && !empty($filters)) ? 'Không có kết quả khớp bộ lọc.' : 'Chưa có khách đăng ký.'?></td></tr>
                    <?php
                        }
                    ?>
                    </tbody>
                    <?php if (!empty($listCallToAction)) { ?>
                    <tfoot>
                        <tr><th colspan="8">
                            <span class="small">Đánh dấu đã chọn thành:</span>
                            <?php echo c12_status_select('bulk_status', '', false, 'class="input-sm"'); ?>
                            <button name="set_status_multiple" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-tag"></span> Đánh dấu</button>
                        </th></tr>
                    </tfoot>
                    <?php } ?>
                    </table>
                </form>
            </div><!-- table-responsive -->
            <div class="pull-right"><?php if (isset($pagi)) $pagi->show()?></div>
        </div><!-- panel-body -->
    </div><!-- #list-so -->
</div><!-- panel -->
<script>
(function(){var a=document.getElementById('chk_cta_all');if(a)a.onclick=function(){var c=document.querySelectorAll('.chk_cta');for(var i=0;i<c.length;i++)c[i].checked=a.checked;};})();
</script>
