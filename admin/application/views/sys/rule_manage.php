<?php define("RULE_ACTION", 'sys/rule_manage/'); ?>
<?php echo alert_box(); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="pull-left">
                            <h3 class="card-title">{{title}}</h3>
                        </div>
                        <div class="pull-right">
                            <?php if ((get_seesion_user()['module'][get_module_url()]['c'] == 1 && $active_type == 'a') || (get_seesion_user()['module'][get_module_url()]['u'] == 1 && $active_type == 'm')) : ?>
                                <div class="btn-group"><button type="button" onclick="action_form('add_form', 'merge_rule');" class="btn btn-info btn-sm fa <?php echo $active_type == 'a' ? "fa-plus" : "fa-save"; ?>"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;確定"; ?></button></div>
                                <?php if ($active_type == 'm') : ?>
                                    <div class="btn-group"><button type="button" onclick="action_form('add_form', '');" class="btn btn-danger btn-sm fa fa-close">取消</button></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <form id="add_form" method="post">
                            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
                            <input type="hidden" name="sid" value="<?php echo $rule_dto->sid ?>" />
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>功能分類</label>
                                        <?php
                                        if ($active_type == 'a') {
                                            $module_js = 'id="module_sid" onChange="action_form(\'add_form\', \'\');" class="form-control"';
                                        } else {
                                            $module_js = 'id="module_sid" class="form-control"';
                                        }
                                        echo form_dropdown('module_sid', $module_array, $selected, $module_js);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>功能名稱</label>
                                        <input class="form-control" type="text" name="rule_name" id="rule_name" value="<?php echo $rule_dto->rule_name; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>功能url</label>
                                        <input class="form-control" type="text" name="url" id="url" value="<?php echo $rule_dto->url; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>功能排序</label>
                                        <input class="form-control" type="number" name="sequence" id="sequence" value="<?php echo $rule_dto->sequence; ?>" />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable_only_search" style="width:100%">
                                <thead>
                                    <tr>
                                        <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                            <th>管理</th>
                                        <?php endif; ?>
                                        <th>功能名稱</th>
                                        <th>url</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rule_list as $rule_dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>
                                                    <?php $id = 'choice_form_' . $rule_dto->sid; ?>
                                                    <?php $disabled = ($active_type == 'm') ? 'disabled' : ''; ?>
                                                    <form id="<?= $id ?>" method="post">
                                                        <!--隱藏欄位-->
                                                        <input type="hidden" name="sid" value="<?php echo $rule_dto->sid ?>" />
                                                        <input type="hidden" id="module_sid" name="module_sid" value="<?php echo $selected ?>" />
                                                        <div class="btn-group">
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="編輯">
                                                                    <a class="btn btn-sm btn-info <?= $disabled ?>" onclick="action_form('<?= $id ?>', 'choice_rule');"><i class="fa fa-edit"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                    <a class="btn btn-sm btn-danger <?= $disabled ?>" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_rule');"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $rule_dto->rule_name; ?></td>
                                            <td><?php echo $rule_dto->url; ?></td>
                                            <td><?php echo $rule_dto->sequence; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('_partial/public_area_list'); ?>

<script type="text/javascript">
    function action_form(formid, url) {
        $('#' + formid).attr('action', '<?php echo site_url(RULE_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(RULE_ACTION); ?>' + url).submit();
        });
    }
</script>