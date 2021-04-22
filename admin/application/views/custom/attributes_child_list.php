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


                            <button type="button" onclick="show_info('');" class="btn btn-info btn-sm fa fa-plus" style="margin-right:5px;">&nbsp;新增</button>
                            <a class="btn btn-secondary btn-sm fa fa-arrow-left" href="<?php echo site_url(ATTRIBUTES_ACTION); ?>">&nbsp;返回列表</a>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable_only_search" style="width:100%">
                                <thead>
                                    <tr>
                                        <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                            <th style=" width: 10%;">管理</th>
                                        <?php endif; ?>
                                        <th>名稱</th>
                                        <th>tag</th>
                                        <th>排序</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($child_attributes_array as $dto) : ?>
                                        <tr>
                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                <td>


                                                    <div class="btn-group">

                                                        <span data-toggle="tooltip" data-placement="right" title="編輯" style="margin-right:5px;">
                                                            <a class="btn btn-sm btn-info" onclick="show_info('<?= $dto->sid ?>');"><i class="fa fa-edit"></i></a>
                                                        </span>

                                                        <span data-toggle="tooltip" data-placement="right" title="刪除" style="margin-right:5px;">
                                                            <a class="btn btn-sm btn-danger " data-toggle="modal" data-target="#myModal" onclick="action_del('del_form_<?= $dto->sid ?>');"><i class="fa fa-trash"></i></a>
                                                        </span>

                                                        <form id="del_form_<?= $dto->sid ?>" method="post">
                                                            <input type="hidden" name="sid" value="<?= $dto->sid ?>">
                                                        </form>

                                                    </div>

                                                </td>

                                            <?php endif; ?>
                                            <td><?= $dto->cname ?></td>
                                            <td><?= $dto->tag ?></td>
                                            <td><?= $dto->sequence ?></td>

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