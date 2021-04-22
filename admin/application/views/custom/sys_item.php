<?php define("SYS_ITEM_ACTION", 'custom/sys_item_manage/') ?>
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
                                <div class="btn-group"><button type="button" onclick="action_form('add_form', 'merge_sys_item');" class="btn btn-info btn-sm fa <?php echo $active_type == 'a' ? "fa-plus" : "fa-save"; ?>" style="margin-right:5px;"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;確定"; ?></button></div>
                                <?php if ($active_type == 'm') : ?>
                                    <div class="btn-group"><button type="button" onclick="action_form('add_form', '');" class="btn btn-danger btn-sm fa fa-close">取消</button></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="add_form" method="post">
                            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
                            <input type="hidden" name="sid" value="<?php echo $sys_item_dto->sid ?>" />
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>分類</label>
                                        <?php
                                        echo form_dropdown('classification_sid', $classification_list, $classification_dto->sid, 'id="classification_sid" class="form-control" onChange="action_form(\'add_form\', \'\');"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>名稱</label>
                                        <input type="text" name="cname" class="form-control" id="cname" value="<?php echo $sys_item_dto->cname; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>副標/子項目/備註</label>
                                        <input type="text" name="nickname" class="form-control" id="nickname" value="<?php echo $sys_item_dto->nickname; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>排序</label>
                                        <input type="number" name="sequence" class="form-control" id="sequence" value="<?php echo $sys_item_dto->sequence; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>說明(備註於後台使用者觀看)</label>
                                        <textarea class="form-control" id="remark" name="remark" rows="3"><?php echo $sys_item_dto->remark; ?></textarea>
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
                                        <th>副標/子項目/備註</th>
                                        <th>說明(備註於後台使用者觀看)</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sys_item_list as $dto) : ?>
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
                                                                <?php if (get_seesion_user()['groups_sid'] == 1 || $classification_dto->is_modify == 1) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="編輯" style="margin-right:5px;">
                                                                        <a class="btn btn-sm btn-info <?= $disabled ?>" onclick="action_form('<?= $id ?>', 'choice_sys_item');"><i class="fa fa-edit"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if (get_seesion_user()['groups_sid'] == 1 || $classification_dto->is_ckedit == 1) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="文字編輯器" style="margin-right:5px;">
                                                                        <a class="btn btn-sm btn-secondary <?= $disabled ?>" href="<?php echo site_url(BOOKMARK_ACTION . "index/custom_sys_item/$dto->sid/true"); ?>"><i class="fa fa-file"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                <?php if (get_seesion_user()['groups_sid'] == 1 || $classification_dto->is_del == 1) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                        <a class="btn btn-sm btn-danger <?= $disabled ?>" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_sys_item');"><i class="fa fa-trash"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $dto->cname; ?></td>
                                            <td><?php echo $dto->nickname; ?></td>
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
        $('#' + formid).attr('action', '<?php echo site_url(SYS_ITEM_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(SYS_ITEM_ACTION); ?>' + url).submit();
        });
    }
</script>