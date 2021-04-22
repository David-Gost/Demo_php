<?php define("ACCOUNT_ACTION", 'sys/account_manage/'); ?>
<?php echo alert_box(); ?>
<section class="content">
    <div class="container-fluid">
        <form id="data_form" class="form-group-sm" method="post" enctype="multipart/form-data">
            <!--隱藏欄位-->
            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
            <input type="hidden" name="sid" value="<?php echo $account_dto->sid ?>" />
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="pull-left">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link btn-sm <?= ($tab_select == 'default') ? 'active' : '' ?>" href="<?php echo site_url(ACCOUNT_ACTION . "modify_account"); ?>">基本資料</a></li>
                                </ul>
                            </div>
                            <div class="pull-right">
                                <?php $this->load->view('custom/_cu_btn'); ?>
                                <div class="btn-group"><a class="btn btn-secondary btn-sm fa fa-arrow-left" href="<?php echo site_url(ACCOUNT_ACTION . 'account_list/' . page()); ?>">&nbsp;返回列表</a></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>所屬公司</label>
                                        <?php
                                        echo form_dropdown('company_sid', $main_company_array, $company_selected, 'class="form-control" id="company_sid"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>權限群組</label>
                                        <?php
                                        echo form_dropdown('groups_sid', $main_groups_array, $groups_selected, 'class="form-control" id="groups_sid"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>帳號</label>
                                        <input type="text" name="account" class="form-control" id="account" value="<?php echo $account_dto->account; ?>" placeholder="帳號最少要三碼 英數字" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>職稱</label>
                                        <?php
                                        echo form_dropdown('company_title_sid', $main_company_title_array, $company_title_selected, 'class="form-control" id="company_title_sid"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>密碼</label>
                                        <input type="password" name="password" class="form-control" id="password" value="" placeholder="密碼最少要七碼 英數字最少各一" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>密碼確認</label>
                                        <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" value="" placeholder="密碼最少要七碼 英數字最少各一" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>中文姓名</label>
                                        <input type="text" name="cname" class="form-control" id="cname" value="<?php echo $account_dto->cname; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>英文姓名</label>
                                        <input type="text" name="cname_en" class="form-control" id="cname_en" value="<?php echo $account_dto->cname_en; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>電話</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" name="tel" class="form-control" id="tel" value="<?php echo $account_dto->tel; ?>" placeholder="02-1231122" />
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
                                            <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $account_dto->phone; ?>" placeholder="0912233345" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>生日</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="birthday" class="form-control flatpickr_date" style="background-color: white;" id="birthday" value="<?php echo $account_dto->birthday; ?>" autocomplete="off" />
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
                                        <input type="text" name="post_code" class="form-control" id="post_code" value="<?php echo $account_dto->post_code; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>地址</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-home"></i></span>
                                            </div>
                                            <input type="text" name="address" class="form-control" id="address" value="<?php echo $account_dto->address; ?>" />
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
                                            <input type="email" name="email" class="form-control" id="email" value="<?php echo $account_dto->email; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>就職日</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="dutydate" class="form-control flatpickr_date" style="background-color: white;" id="dutydate" value="<?php echo $account_dto->dutydate ?: date('Y-m-d'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>離職日</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="departuredate" class="form-control flatpickr_date" style="background-color: white;" id="departuredate" value="<?php echo $account_dto->departuredate; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="field text-center">
                                        <label for=" ">大頭照<span style="color:red;">(300x300)</span></label>
                                        <div class="cut-block">
                                            <div class="imageBox" id="imageBox">
                                                <div class="thumbBox" id="thumbBox"></div>
                                                <?php if (!empty($account_dto->picinfo)) { ?>
                                                    <div class="spinner" id="spinner" style=";margin: 5px;">
                                                        <img src="<?php echo base_url(get_fullpath_with_file($account_dto->picinfo)); ?>" class="img-responsive">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="spinner" id="spinner" style="display: none"></div>
                                                <?php } ?>
                                                <input type="hidden" name="origin_picinfo" id="origin_picinfo" value="<?= $account_dto->picinfo ?>">
                                            </div>
                                            <div class="action">
                                                <div class="cut-views" id="file_up_btn">
                                                    <input type="file" name="picinfo" id="picinfo" 　style="float:left; width: 250px;">
                                                </div>
                                            </div>
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
                                            <?php if (!empty($account_dto->picinfo)) { ?>
                                                <a title="刪除圖片" onclick="delete_pic(<?php echo $account_dto->sid ?>, 'sys_account', 'picinfo', 'spinner', 'origin_picinfo', true);">
                                                    <i class="fa fa-remove"></i>刪除圖片
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>備註說明</label>
                                        <textarea name="remark" class="form-control" id="remark" rows="3"><?php echo $account_dto->remark; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>緊急聯絡人姓名</label>
                                        <input type="text" name="contact_name" class="form-control" id="contact_name" value="<?php echo $account_dto->contact_name; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>緊急聯絡人電話</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" name="contact_phone" class="form-control" id="contact_phone" value="<?php echo $account_dto->contact_phone; ?>" />
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
                                            <input type="text" name="contact_relationship" class="form-control" id="contact_relationship" value="<?php echo $account_dto->contact_relationship; ?>" />
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
        $('#data_form').attr('action', "<?php echo site_url(ACCOUNT_ACTION . 'merge_account'); ?>").submit();
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
            $("#city_sid option[value='<?php echo $account_dto->city_sid ?>']").attr('selected', 'selected');
            load_area('<?php echo $account_dto->city_sid ?>');
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

                $("#area_sid option[value='<?php echo $account_dto->area_sid ?>']").attr('selected', 'selected');
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