<?php define("CITY_MANAGE_ACTION", 'sys/cityarea_manage/') ?>
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
                                <div class="btn-group"><button type="button" onclick="action_form('add_form', 'merge_cityarea');" class="btn btn-info btn-sm fa <?php echo $active_type == 'a' ? "fa-plus" : "fa-save"; ?>"><?php echo $active_type == 'a' ? "&nbsp;新增" : "&nbsp;確定"; ?></button></div>
                                <?php if ($active_type == 'm') : ?>
                                    <div class="btn-group"><button type="button" onclick="action_form('add_form', '');" class="btn btn-danger btn-sm fa fa-close">取消</button></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <form id="add_form" method="post">
                            <input type="hidden" name="active_type" value="<?php echo $active_type ?>" />
                            <input type="hidden" name="sid" value="<?php echo $cityarea_dto->sid ?>" />
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>上層分類</label>
                                        <?php
                                        if ($active_type == 'a') {
                                            $js = 'id="parent_sid" onChange="action_form(\'add_form\', \'\');" class="form-control"';
                                        } else {
                                            $js = 'id="parent_sid" class="form-control"';
                                        }
                                        echo form_dropdown('parent_sid', $main_cityarea_array, $selected, $js);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>區域名稱</label>
                                        <input type="text" name="area_name" class="form-control" id="area_name" value="<?php echo $cityarea_dto->area_name; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>郵遞區號</label>
                                        <input type="text" name="post_code" id="post_code" class="form-control" value="<?php echo $cityarea_dto->post_code; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>說明</label>
                                        <input type="text" name="remark" id="remark" class="form-control" value="<?php echo $cityarea_dto->remark; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>排序</label>
                                        <input type="number" name="sequence" id="sequence" class="form-control" value="<?php echo $cityarea_dto->sequence; ?>" />
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
                                        <th>區域名稱</th>
                                        <th>郵遞區號</th>
                                        <th>說明</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cityarea_list as $cityarea_dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>
                                                    <?php $id = 'choice_form_' . $cityarea_dto->sid; ?>
                                                    <?php $disabled = ($active_type == 'm') ? 'disabled' : ''; ?>
                                                    <form id="<?= $id ?>" method="post">
                                                        <!--隱藏欄位-->
                                                        <input type="hidden" name="sid" value="<?php echo $cityarea_dto->sid ?>" />
                                                        <input type="hidden" id="parent_sid" name="parent_sid" value="<?php echo $selected ?>" />
                                                        <div class="btn-group">
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="編輯">
                                                                    <a class="btn btn-sm btn-info <?= $disabled ?>" onclick="action_form('<?= $id ?>', 'choice_cityarea');"><i class="fa fa-edit"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                    <a class="btn btn-sm btn-danger <?= $disabled ?>" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_cityarea');"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo $cityarea_dto->area_name; ?></td>
                                            <td><?php echo $cityarea_dto->post_code; ?></td>
                                            <td><?php echo $cityarea_dto->remark; ?></td>
                                            <td><?php echo $cityarea_dto->sequence; ?></td>
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
        $('#' + formid).attr('action', '<?php echo site_url(CITY_MANAGE_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(CITY_MANAGE_ACTION); ?>' + url).submit();
        });
    }
</script>