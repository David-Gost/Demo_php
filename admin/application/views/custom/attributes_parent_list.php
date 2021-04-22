<?php define("ATTRIBUTES_ACTION", 'custom/attributes_manage/') ?>
<?php echo alert_box(); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{title}}</h3>
                        <div class="card-tools">

                            <?php if ((get_seesion_user()['module'][get_module_url()]['c'] == 1) || (get_seesion_user()['module'][get_module_url()]['u'] == 1)) : ?>
                                <?php if (get_seesion_user()['groups_sid'] == 1) : ?>
                                    <button type="button" onclick="show_info('');" class="btn btn-info btn-sm fa fa-plus" style="margin-right:5px;">&nbsp;新增</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable_only_search" style="width:100%">
                                <thead>
                                    <tr>
                                        <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                            <th style=" width: 15%;">管理</th>
                                        <?php endif; ?>
                                        <th>名稱</th>
                                        <th>標籤</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($parent_attributes_array as $dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>


                                                    <div class="btn-group">
                                                        <?php if ((get_seesion_user()['module'][get_module_url()]['u'] == 1) && get_seesion_user()['groups_sid'] == 1) : ?>
                                                            <span data-toggle="tooltip" data-placement="right" title="編輯" style="margin-right:5px;">
                                                                <a class="btn btn-sm btn-info" onclick="show_info('<?= $dto->sid ?>');"><i class="fa fa-edit"></i></a>
                                                            </span>
                                                        <?php endif; ?>


                                                        <?php if ($dto->data_count == 0) : ?>

                                                            <?php $form_sid = 'del_form_' . $dto->sid ?>
                                                            <span data-toggle="tooltip" data-placement="right" title="刪除" style="margin-right:5px;">
                                                                <a class="btn btn-sm btn-danger " data-toggle="modal" data-target="#myModal" onclick="action_del('del_form_<?= $dto->sid ?>');"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                            <form id="<?= $form_sid ?>" method="post">
                                                                <input type="hidden" name="sid" value="<?= $dto->sid ?>">
                                                            </form>



                                                        <?php endif; ?>
                                                        <?php $form_sid = 'child_form_' . $dto->sid ?>

                                                        <span data-toggle="tooltip" data-placement="right" title="子項維護" style="margin-right:5px;">
                                                            <a class="btn btn-sm btn-success" onclick="$('#<?= $form_sid ?>').submit();"><i class="fa fa-list"></i></a>
                                                        </span>
                                                        <form id="<?= $form_sid ?>" method="post" action="<?= base_url(ATTRIBUTES_ACTION . 'child_manage') ?>">
                                                            <input type="hidden" name="sid" value="<?= $dto->sid ?>">
                                                        </form>

                                                    </div>

                                                </td>

                                            <?php endif; ?>
                                            <td><?= $dto->cname ?></td>
                                            <td><?= $dto->tag ?></td>

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
<?= $admin_madal ?>


<script type="text/javascript">
    function action_del(formid) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(ATTRIBUTES_ACTION); ?>' + 'del_attributes').submit();
        });
    }
</script>