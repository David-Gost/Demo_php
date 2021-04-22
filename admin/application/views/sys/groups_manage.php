<?php define("GROUPS_MANAGE_ACTION", 'sys/groups_manage/') ?>
<?php echo alert_box(); ?>
<div class="box box-primary">
    <div class="box-header">
        <div class="btn-group">
            <button type="button" id="addbtn" class="btn btn-primary btn-md bg-blue-gradient fa fa-save" autocomplete="off"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;儲存"; ?></button>
        </div>
        <div class="btn-group">
            <a href="<?php echo site_url(GROUPS_MANAGE_ACTION); ?>"><button type="button" id="addbtn" class="btn btn-primary btn-md bg-blue-gradient fa fa-arrow-left" autocomplete="off">&nbsp;回上頁</button></a>
        </div>
    </div>
    <div class="box-body">
        <form id="groups_form" method="post">
            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
            <input type="hidden" name="sid" value="<?php echo $groups_dto->sid ?>" />
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">權限群組基本資料</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>所屬公司</label>
                            <?=$company_info->cname?>
                        </div><!-- /.form-group -->
                    </div><!-- /.col -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>群組名稱</label>
                            <input type="text" name="cname" class="form-control" id="cname" value="<?php echo $groups_dto->cname; ?>" />
                        </div><!-- /.form-group -->
                    </div><!-- /.col -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>排序</label>
                            <input type="text" name="sequence" class="form-control" id="sequence" value="<?php echo $groups_dto->sequence; ?>" />
                        </div><!-- /.form-group -->
                    </div><!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>備註說明</label>
                            <textarea name="remark" class="form-control" id="remark" rows="3"><?php echo $groups_dto->remark; ?></textarea>
                        </div><!-- /.form-group -->
                    </div><!-- /.col -->
                </div>
                <div class="box-header">
                    <h3 class="box-title">權限設定</h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <?php
                            if (!is_null(get_seesion_user())) {

                                $user_data = get_seesion_user();
                                $modules = $user_data['module'];
                            }
                            //判斷是否有相同module_id的flag
                            $temp_id = 0;
                            $click_module_id = $this->session->userdata('module_id');
                            foreach ($modules as $module) {

                                $sid = $module['sid'];
                                $module_name = $module['module_name'];

                                //判斷相同的module_id則不執行
                                if ($temp_id == $sid) {
                                    continue;
                                }
                            ?>
                                <tr>
                                    <th colspan="6" style="background-color: #3F729B; color: white; font-size: 18px;">
                                        <?php echo $module_name; ?>
                                    </th>
                                </tr>
                                <?php
                                foreach ($modules as $rule) {

                                    $module_sid = $rule['module_sid'];
                                    //echo var_dump($function_module_id);
                                    $rule_sid = $rule['rule_sid'];
                                    $rule_name = $rule['rule_name'];
                                    $url = $rule['url'];
                                    if ($sid == $module_sid) {

                                        //是否可編輯條件
                                        $editr = $rule["r"] ? true : false;
                                        $editc = $rule["c"] ? true : false;
                                        $editu = $rule["u"] ? true : false;
                                        $editd = $rule["d"] ? true : false;

                                        //是否已勾選
                                        $checkr = "";
                                        $checkc = "";
                                        $checku = "";
                                        $checkd = "";

                                        if ($groups_rule != '') {
                                            foreach ($groups_rule as $rulevalue) {
                                                if ($rule_sid == $rulevalue['rule_sid']) {
                                                    if ($rulevalue['r']) {
                                                        $checkr = "checked";
                                                    }
                                                    if ($rulevalue['c']) {
                                                        $checkc = "checked";
                                                    }
                                                    if ($rulevalue['u']) {
                                                        $checku = "checked";
                                                    }
                                                    if ($rulevalue['d']) {
                                                        $checkd = "checked";
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $rule_name; ?></td>
                                            <td>
                                                <input type="checkbox" class="minimal" name="row_check[]" value="<?php echo $rule_sid; ?>">
                                                選擇
                                            </td>
                                            <td>
                                                <?php if ($editr) : ?>
                                                    <input type="checkbox" name='r_select[]' class="minimal" value="<?php echo $rule_sid; ?>" <?php echo $checkr; ?>>
                                                    R(頁面權限)
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($editc) : ?>
                                                    <input type="checkbox" name='c_select[]' class="minimal" value="<?php echo $rule_sid; ?>" <?php echo $checkc; ?>>
                                                    C(新增)
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($editu) : ?>
                                                    <input type="checkbox" name='u_select[]' class="minimal" value="<?php echo $rule_sid; ?>" <?php echo $checku; ?>>
                                                    U(修改)
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($editd) : ?>
                                                    <input type="checkbox" name='d_select[]' class="minimal" value="<?php echo $rule_sid; ?>" <?php echo $checkd; ?>>
                                                    D(刪除)
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                                $temp_id = $sid;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="box-footer">
        <div class="btn-group">
            <button type="button" id="addbtn1" class="btn btn-primary btn-md bg-blue-gradient fa fa-save" autocomplete="off"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;儲存"; ?></button>
        </div>
        <div class="btn-group">
            <a href="<?php echo site_url(GROUPS_MANAGE_ACTION); ?>"><button type="button" id="addbtn" class="btn btn-primary btn-md bg-blue-gradient fa fa-arrow-left" autocomplete="off">&nbsp;回上頁</button></a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#addbtn').bind('click', function() {

        $('#groups_form').attr('action', "<?php echo site_url(GROUPS_MANAGE_ACTION . 'merge_groups/' . page()); ?>").submit();
    });
    $('#addbtn1').bind('click', function() {

        $('#groups_form').attr('action', "<?php echo site_url(GROUPS_MANAGE_ACTION . 'merge_groups/' . page()); ?>").submit();
    });

    $('#all_check').on('ifClicked', function() {
        if (!$('#all_check').prop('checked')) {
            //全部勾選
            $("input[type='checkbox']").each(function() {
                $(this).iCheck('check');
            });
        } else {
            //取消全選
            $("input[type='checkbox']").each(function() {
                $(this).iCheck('uncheck');
            });
        }
    });

    $("input[name='row_check[]']").on('ifClicked', function() {
        var id = $(this).val();
        if (!$(this).prop('checked')) {
            $("input[value='" + id + "']").iCheck('check');
        } else {
            $("input[value='" + id + "']").iCheck('uncheck');
        }
    });
</script>