<div class="panel">
    <div class="panel_header">
        <h1>LIÊN HỆ</h1>
    </div>
    <div class="clear"></div>
    <div class="panel_content">
        <iframe style="border: 0;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.8198705512327!2d106.63592521524349!3d10.825092944184115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752965345eba8d%3A0x5632d9676ee73236!2zNCBOZ3V54buFbiBQaMO6YyBDaHUsIFBoxrDhu51uZyAxNSwgVMOibiBCw6xuaCwgSOG7kyBDaMOtIE1pbmgsIFZpZXRuYW0!5e0!3m2!1sen!2sus!4v1453790749455" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        <div class="container">
            <div id="contact_content">
                <?php echo quotesDecode($template['page']['page_content'])?>
            </div>
            
            <?php
                if (isset($_SESSION['success'])) { 
            ?>
                    <div class="success"><?php echo $_SESSION['success']?></div>
            <?php
                    unset($_SESSION['success']);
                } // end if
                
                if (isset($_SESSION['error'])) {
                    if (isset($_SESSION['send_mail'])) {
                        echo '<div class="errors">' . $_SESSION['send_mail'] . '</div>'; 
                    } else {
            ?>
                        <div class="errors">Có lỗi xảy ra do bạn chưa nhập đủ dữ liệu hoặc dữ liệu không phù hợp.</div>
            <?php
                    }
                    
                    $error = $_SESSION['error'];
                    unset($_SESSION['error']);
                } // end if error
            ?>
            <div class="col-md-8 col-md-offset-2">
                <form method="post">
                    <div class="note text-center padding5">Những mục có dấu (*) là bắt buộc phải nhập</div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="control-label">Họ Tên <span class="note">(*)</span></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txt_name'])?$error['txt_name']:''?></b></div>
                            <div class="form-input">
                                <i class="fa fa-user fa-lg icon-append"></i>
                                <input type="text" class="icon-text form-control" name="txt_name" value="<?php echo isset($_POST['txt_name'])?$_POST['txt_name']:''?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Số điện thoại <?php echo isset($product['product_id'])?'<span class="note">(*)</span>':''?></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txt_phone'])?$error['txt_phone']:''?></b></div>
                            <div class="form-input">
                                <i class="icon-append fa fa-phone fa-lg"></i>
                                <input type="text" class="icon-text form-control" name="txt_phone" value="<?php echo isset($_POST['txt_phone'])?$_POST['txt_phone']:''?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Email <span class="note">(*)</span></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txt_email'])?$error['txt_email']:''?></b></div>
                            <div class="form-input">
                                <i class="icon-append fa fa-envelope-o fa-lg"></i>
                                <input type="text" name="txt_email" class="icon-text form-control" value="<?php echo isset($_POST['txt_email'])?$_POST['txt_email']:''?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Địa chỉ <?php echo isset($product['product_id'])?'giao hàng <span class="note">(*)</span>':''?></label>
                        </div>
                        <div class="col-md-9 form-input">
                            <div><b class="error"><?php echo isset($error['txta_address'])?$error['txta_address']:''?></b></div>
                            <textarea name="txta_address" class="form-control" rows="3"><?php echo isset($_POST['txta_address'])?$_POST['txta_address']:''?></textarea>
                        </div>
                    </div>
                    <?php
                        if (!isset($product['product_id'])) {
                    ?>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Tiêu đề <span class="note">(*)</span></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txt_subject'])?$error['txt_subject']:''?></b></div>
                            <div class="form-input">
                                <i class="icon-append fa fa-header fa-lg"></i>
                                <input type="text" class="icon-text form-control" name="txt_subject" value="<?php echo isset($_POST['txt_subject'])?$_POST['txt_subject']:''?>"/>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Nội dung <span class="note">(*)</span></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txta_content'])?$error['txta_content']:''?></b></div>
                            <textarea name="txta_content" class="form-control" rows="3"><?php echo isset($_POST['txta_content'])?$_POST['txta_content']:''?></textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <label class="inline">Mã xác thực <span class="note">(*)</span></label>
                        </div>
                        <div class="col-md-9">
                            <div><b class="error"><?php echo isset($error['txt_captcha'])?$error['txt_captcha']:''?></b></div>
                            <img src="<?php echo AJAX_URL?>&p=gcapt" /><br />
                            <div class="form-group">
                                <i class="icon-append fa fa-keyboard-o fa-lg"style="left: 3px;"></i>
                                <input type="text" name="txt_captcha" class="icon-text form-control"/>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group col-md-12">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button class="cms_send btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i>&nbsp; Gửi</button>&nbsp;
                            <button class="cms_reset btn btn-primary" type="reset"><i class="fa fa-refresh"></i> Nhập lại</button>
                        </div>
                    </div>
                    <input type="hidden" name="template_function" value="sendContact" />
                </form>
            </div>
        </div>
    </div><!-- panel_content -->
</div><!-- panel -->