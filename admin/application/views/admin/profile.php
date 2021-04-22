<?php define("DASHBOARD", 'My_dashboard/'); ?>
<?php echo alert_box(); ?>

<section class="content">
    <div class="container-fluid">
        <form id="data_form" class="form-group-sm" method="post" enctype="multipart/form-data">
            <!--隱藏欄位-->
            <input type="hidden" name="sid" value="<?php echo get_seesion_user()['sid']; ?>" />
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="pull-left">
                                <h3 class="card-title">{{title}}</h3>
                            </div>
                            <div class="pull-right">
                                <button type="button" id="btnsave" class="btn btn-info btn-sm fa fa-save" autocomplete="off">&nbsp;儲存</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4>帳號基本資料</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>帳號</label>
                                        <input type="text" name="account" class="form-control" id="account" value="<?php echo get_seesion_user()['account']; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>密碼</label>
                                        <input type="password" name="password" class="form-control" id="password" value="" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>密碼確認</label>
                                        <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" value="" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>中文姓名</label>
                                        <input type="text" name="cname" class="form-control" id="cname" value="<?php echo get_seesion_user()['cname']; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>英文姓名</label>
                                        <input type="text" name="cname_en" class="form-control" id="cname_en" value="<?php echo get_seesion_user()['cname_en']; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>生日</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="birthday" class="form-control" id="birthday" value="<?php echo get_seesion_user()['birthday']; ?>" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>電話</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" name="tel" class="form-control" id="tel" value="<?php echo get_seesion_user()['tel']; ?>" placeholder="ex:06-1231234" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>手機</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-mobile"></i></span>
                                            </div>
                                            <input type="text" name="phone" class="form-control" id="phone" value="<?php echo get_seesion_user()['phone']; ?>" placeholder="ex:0912345678" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            </div>
                                            <input type="email" name="email" class="form-control" id="email" value="<?php echo get_seesion_user()['email']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>縣市</label>
                                        <select id="city_sid" name="city_sid" class="form-control">
                                            <option value="0">---縣市---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>鄉鎮區</label>
                                        <select id="area_sid" name="area_sid" class="form-control">
                                            <option value="0">---鄉鎮區---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>郵遞區號</label>
                                        <input type="text" name="post_code" class="form-control" id="post_code" value="<?php echo get_seesion_user()['post_code']; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>地址</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-home"></i></span>
                                            </div>
                                            <input type="text" name="address" class="form-control" id="address" value="<?php echo get_seesion_user()['address']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label>備註說明</label>
                                        <textarea name="remark" class="form-control" id="remark" rows="10"><?php echo get_seesion_user()['remark']; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="field text-center">
                                        <label for=" ">大頭照</label>
                                        <div class="cut-block">
                                            <div class="imageBox" id="imageBox">
                                                <div class="thumbBox" id="thumbBox"></div>
                                                <?php if (!empty(get_seesion_user()['picinfo'])) { ?>
                                                    <div class="spinner" id="spinner" style=";margin: 5px;">
                                                        <img src="<?php echo base_url(get_fullpath_with_file(get_seesion_user()['picinfo'])); ?>" class="img-responsive">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="spinner" id="spinner" style="display: none"></div>
                                                <?php } ?>
                                            </div>
                                            <div class="action">
                                                <div class="cut-views" id="file_up_btn" <?= (!empty(get_seesion_user()['picinfo'])) ? 'style="display:none;"' : '' ?>>
                                                    <input type="file" name="picinfo" id="picinfo" 　style="float:left; width: 250px;">
                                                </div>
                                            </div>
                                            <input type="hidden" name="origin_picinfo" id="origin_picinfo" value="<?= get_seesion_user()['picinfo'] ?>">
                                            <input type="hidden" name="hid_base64_pic" id="hid_base64_pic" value="">
                                            <input type="hidden" name="hid_picinfo" value="">
                                        </div>
                                        <div class="btcut">
                                            <span id="btn_list">
                                                <input type="button" id="btnZoomIn" value="放大">
                                                <input type="button" id="btnZoomOut" value="縮小">
                                                <input type="button" id="btnRotating" value="旋轉">
                                                <input type="button" id="btnFlip" value="鏡像">
                                                <input type="button" id="btnCrop" value="確認">
                                            </span>
                                            <a id="del_pic_btn" <?= (empty(get_seesion_user()['picinfo'])) ? 'style="display:none;"' : '' ?> title="刪除圖片" onclick="delete_pic(<?php echo get_seesion_user()['sid'] ?>, 'sys_account', 'picinfo', 'spinner', 'origin_picinfo');">
                                                <i class="fa fa-remove"></i>刪除圖片
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4>緊急聯絡人資料</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>緊急聯絡人姓名</label>
                                        <input type="text" name="contact_name" class="form-control" id="contact_name" value="<?php echo get_seesion_user()['contact_name']; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>緊急聯絡人電話</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" name="contact_phone" class="form-control" id="contact_phone" value="<?php echo get_seesion_user()['contact_phone']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>與緊急聯絡人關係</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-sitemap"></i></span>
                                            </div>
                                            <input type="text" name="contact_relationship" class="form-control" id="contact_relationship" value="<?php echo get_seesion_user()['contact_relationship']; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="text/javascript">
    $('#btnsave').bind('click', function() {
        $('#data_form').attr('action', "<?php echo site_url(DASHBOARD . 'merge_profile'); ?>").submit();
    });

    $("#birthday").flatpickr({
        'allowInput': true
    });

    $.ajax({
        url: '<?php echo BASE_URL . '/ajax/ajax_cityarea/load_city'; ?>',
        data: null,
        type: "POST",
        dataType: 'json',
        success: function(result) {
            $.each(result, function(key, value) {
                $("#city_sid").append($("<option value='0'>---縣市---</option>").attr("value", key).text(value));
            });
            $("#city_sid option[value='<?php echo get_seesion_user()['city_sid'] ?>']").attr('selected', 'selected');
            load_area('<?php echo get_seesion_user()['city_sid'] ?>');
        }
    });

    $("#city_sid").change(function() { //事件發生
        var city_sid = $(this).val();
        load_area(city_sid);
    });

    function load_area(city_sid) {

        $.ajax({
            url: '<?php echo BASE_URL . '/ajax/ajax_cityarea/load_area'; ?>',
            data: {
                city_sid: city_sid
            },
            type: "POST",
            dataType: 'json',
            success: function(result) {

                $("#area_sid").empty();
                $("#area_sid").append("<option value='0'>---鄉鎮區---</option>");
                $.each(result, function(key, value) {
                    $("#area_sid").append($("<option value='0'>---鄉鎮區---</option>").attr("value", key).text(value));
                });
                $("#area_sid option[value='<?php echo get_seesion_user()['area_sid'] ?>']").attr('selected', 'selected');
            }
        });
    }

    $("#area_sid").change(function() { //事件發生

        var city_sid = $('#city_sid').val();
        var area_sid = $(this).val();

        $.ajax({
            url: '<?php echo BASE_URL . '/ajax/ajax_cityarea/load_post_code'; ?>',
            data: {
                city_sid: city_sid,
                area_sid: area_sid
            },
            type: "POST",
            dataType: 'json',
            success: function(result) {
                $('#post_code').prop('value', result);
            }
        });
    });
</script>