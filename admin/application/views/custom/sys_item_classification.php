<?php define("SYS_ITEM_CLASSIFICATION_ACTION", 'custom/sys_item_classification_manage/') ?>
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
                                <div class="btn-group"><button type="button" onclick="action_form('add_form', 'merge_sys_item_classification');" class="btn btn-info btn-sm fa <?php echo $active_type == 'a' ? "fa-plus" : "fa-save"; ?>" style="margin-right:5px;"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;確定"; ?></button></div>
                                <?php if ($active_type == 'm') : ?>
                                    <div class="btn-group"><button type="button" onclick="action_form('add_form', '');" class="btn btn-danger btn-sm fa fa-close">取消</button></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="add_form" method="post">
                            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
                            <input type="hidden" name="sid" value="<?php echo $sys_item_classification_dto->sid ?>" />
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label>分類名稱</label>
                                        <input type="text" name="cname" class="form-control" id="cname" value="<?php echo $sys_item_classification_dto->cname; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>分類排序</label>
                                        <input type="number" name="sequence" class="form-control" id="sequence" value="<?php echo $sys_item_classification_dto->sequence; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>是否顯示給客戶</label>
                                        <label style="color: #C63333;" class="form-control">
                                            <input name="is_show" id="is_show" type="checkbox" class="minimal iclick" value="<?= $sys_item_classification_dto->is_show; ?>" <?= show_checked($sys_item_classification_dto->is_show); ?>>
                                            (打勾表示可以)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>是否可以新增</label>
                                        <label style="color: #C63333;" class="form-control">
                                            <input name="is_add" id="is_add" type="checkbox" class="minimal iclick" value="<?= $sys_item_classification_dto->is_add; ?>" <?= show_checked($sys_item_classification_dto->is_add); ?>>
                                            (打勾表示可以)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>是否可以修改</label>
                                        <label style="color: #C63333;" class="form-control">
                                            <input name="is_modify" id="is_modify" type="checkbox" class="minimal iclick" value="<?= $sys_item_classification_dto->is_modify; ?>" <?= show_checked($sys_item_classification_dto->is_modify); ?>>
                                            (打勾表示可以)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>是否可以刪除</label>
                                        <label style="color: #C63333;" class="form-control">
                                            <input name="is_del" id="is_del" type="checkbox" class="minimal iclick" value="<?= $sys_item_classification_dto->is_del; ?>" <?= show_checked($sys_item_classification_dto->is_del); ?>>
                                            (打勾表示可以)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>是否文字編輯器</label>
                                        <label style="color: #C63333;" class="form-control">
                                            <input name="is_ckedit" id="is_ckedit" type="checkbox" class="minimal iclick" value="<?= $sys_item_classification_dto->is_ckedit; ?>" <?= show_checked($sys_item_classification_dto->is_ckedit); ?>>
                                            (打勾表示可以)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>備註</label>
                                        <input type="text" name="remark" class="form-control" id="remark" value="<?php echo $sys_item_classification_dto->remark; ?>" />
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
                                        <th>SID</th>
                                        <th>名稱</th>
                                        <th>是否可以新增</th>
                                        <th>是否可以修改</th>
                                        <th>是否可以刪除</th>
                                        <th>是否文字編輯器</th>
                                        <th>是否顯示給客戶</th>
                                        <th>備註</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sys_item_classification_list as $dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>
                                                    <?php $id = 'choice_form_' . $dto->sid; ?>
                                                    <?php $disabled = ($active_type == 'm') ? 'disabled' : ''; ?>
                                                    <form id="<?= $id ?>" method="post">
                                                        <!--隱藏欄位-->
                                                        <input type="hidden" name="sid" value="<?php echo $dto->sid ?>" />
                                                        <div class="btn-group">
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="編輯" style="margin-right:5px;">
                                                                    <a class="btn btn-sm btn-info <?= $disabled ?>" onclick="action_form('<?= $id ?>', 'choice_sys_item_classification');"><i class="fa fa-edit"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                    <a class="btn btn-sm btn-danger <?= $disabled ?>" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_sys_item_classification');"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $dto->sid; ?></td>
                                            <td><?php echo $dto->cname; ?></td>
                                            <td><?php echo ($dto->is_add == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">' ?></td>
                                            <td><?php echo ($dto->is_modify == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">' ?></td>
                                            <td><?php echo ($dto->is_del == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">' ?></td>
                                            <td><?php echo ($dto->is_ckedit == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">' ?></td>
                                            <td><?php echo ($dto->is_show == 1) ? '<i class="fa fa-check">' : '<i class="fa fa-close">' ?></td>
                                            <td><?php echo $dto->remark; ?></td>
                                            <td><?php echo $dto->sequence; ?></td>
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
        $('#' + formid).attr('action', '<?php echo site_url(SYS_ITEM_CLASSIFICATION_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(SYS_ITEM_CLASSIFICATION_ACTION); ?>' + url).submit();
        });
    }
</script>