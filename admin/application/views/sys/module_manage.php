<?php define("MODULE_ACTION", 'sys/module_manage/'); ?>
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
                                <div class="btn-group"><button type="button" onclick="action_form('add_form', 'merge_module');" class="btn btn-info btn-sm fa <?php echo $active_type == 'a' ? "fa-plus" : "fa-save"; ?>"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;確定"; ?></button></div>
                                <?php if ($active_type == 'm') : ?>
                                    <div class="btn-group"><button type="button" onclick="action_form('add_form', '');" class="btn btn-danger btn-sm fa fa-close">取消</button></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <form id="add_form" method="post">
                            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
                            <input type="hidden" name="sid" value="<?php echo $module_dto->sid ?>" />
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>分類名稱</label>
                                        <input type="text" name="module_name" id="module_name" class="form-control" value="<?php echo $module_dto->module_name; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>圖標<a style="cursor: pointer;" data-toggle="modal" data-target="#myModalIcons">&nbsp;&nbsp;(Icons Preview)</a></label>
                                        <input type="text" name="module_icons" id="module_icons" class="form-control" value="<?php echo $module_dto->module_icons; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>是否為系統功能</label>
                                        <label style="color: red;" class="form-control">
                                            <input name="module_type" id="module_type" type="checkbox" class="minimal" <?php echo $module_dto->module_type == 1 ? 'checked' : ''; ?>>
                                            (打勾表示使用)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>分類排序</label>
                                        <input type="number" name="sequence" id="sequence" class="form-control" value="<?php echo $module_dto->sequence; ?>" />
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
                                        <th>名稱</th>
                                        <th>圖標</th>
                                        <th>是否為系統功能</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($module_list as $module_dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>
                                                    <?php $id = 'choice_form_' . $module_dto->sid; ?>
                                                    <?php $disabled = ($active_type == 'm') ? 'disabled' : ''; ?>
                                                    <form id="<?= $id ?>" method="post">
                                                        <!--隱藏欄位-->
                                                        <input type="hidden" name="sid" value="<?php echo $module_dto->sid ?>" />
                                                        <div class="btn-group">
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="編輯">
                                                                    <a class="btn btn-sm btn-info <?= $disabled ?>" onclick="action_form('<?= $id ?>', 'choice_module');"><i class="fa fa-edit"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                    <a class="btn btn-sm btn-danger <?= $disabled ?>" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_module');"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $module_dto->module_name; ?></td>
                                            <td>
                                                <?php if ($module_dto->module_icons !== '') : ?>
                                                    <i class='fa <?php echo $module_dto->module_icons; ?>'> &nbsp;<?php echo $module_dto->module_icons; ?></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo ($module_dto->module_type == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">'; ?></td>
                                            <td><?php echo $module_dto->sequence; ?></td>
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

<!-- Modal -->
<div class="modal fade" id="myModalIcons" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="display: block;width: 210%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="min-height:250px">
                <?php $this->load->view('_partial/icons'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function action_form(formid, url) {
        $('#' + formid).attr('action', '<?php echo site_url(MODULE_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(MODULE_ACTION); ?>' + url).submit();
        });
    }
</script>