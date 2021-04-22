<?php define("ACCOUNT_ACTION", 'sys/account_manage/') ?>
<?php echo alert_box(); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="pull-left">
                            <h3 class="card-title">帳號清單</h3>
                        </div>
                        <div class="pull-right">
                            <?php if (get_seesion_user()['module'][get_module_url()]['c'] == 1) : ?>
                                <div class="btn-group"><a href="<?php echo site_url(ACCOUNT_ACTION . 'add_account/'); ?>" class="btn btn-info btn-sm fa fa-plus" style="margin-right:5px;">新增</a></div>
                            <?php endif; ?>
                            <div class="btn-group"> <a href="#" data-toggle="modal" data-target="#<?= $search_model->model_id ?>" class="btn btn-secondary btn-sm fa fa-filter">篩選</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                            <th>管理</th>
                                        <?php endif; ?>
                                        <th>姓名<br>帳號</th>
                                        <th>聯絡電話</th>
                                        <th>Email</th>
                                        <th>是否啟用</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $account_dto) : ?>
                                        <?php if ($account_dto->sid != 1 || get_seesion_user()['groups_sid'] == 1) : ?>
                                            <tr>
                                                <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                    <td>
                                                        <?php $id = 'choice_form_' . $account_dto->sid; ?>
                                                        <form id="<?= $id ?>" method="post">
                                                            <!--隱藏欄位-->
                                                            <input type="hidden" name="sid" value="<?php echo $account_dto->sid ?>" />
                                                            <div class="btn-group">
                                                                <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="檢視" style="margin-right:5px;">
                                                                        <a class="btn btn-sm btn-info" onclick="action_form('<?= $id ?>', 'modify_account');"><i class="fa fa-search"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 && $account_dto->sid > 2 && $account_dto->sid != get_seesion_user()['sid']) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                        <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_account');"><i class="fa fa-trash"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </form>
                                                    </td>
                                                <?php endif; ?>

                                                <td><?= $account_dto->cname ?><br><?= $account_dto->account ?></td>
                                                <td><?= $account_dto->phone ?></td>
                                                <td><?= $account_dto->email ?></td>
                                                <td nowrap="nowrap">
                                                    <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                        <div class="input_form">
                                                            <div class="forms-group">
                                                                <label class="text-forms forms--radio" style="margin-bottom: 0;"><span style=" color: #C63333;">(不啟用)</span>
                                                                    <input type="radio" name="isuse<?php echo $account_dto->sid; ?>" value="0" onclick="change_is_use('sys_account', 'isuse', '<?php echo $account_dto->sid; ?>', this.value)" <?php echo $account_dto->isuse == 0 ? 'checked' : ''; ?>>
                                                                    <span class="forms__indicator"></span>
                                                                </label>
                                                                <label class="text-forms forms--radio" style="margin-bottom: 0;"><span style=" color: blue;">(啟用)</span>
                                                                    <input type="radio" name="isuse<?php echo $account_dto->sid; ?>" value="1" onclick="change_is_use('sys_account', 'isuse', '<?php echo $account_dto->sid; ?>', this.value)" <?php echo $account_dto->isuse == 1 ? 'checked' : ''; ?>>
                                                                    <span class="forms__indicator"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php
                                                        echo ($account_dto->isuse == 1) ? '<div class="glyphicon glyphicon-check"/>' : '<div class="glyphicon glyphicon-remove"/>'
                                                        ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="card-tool">
                            <?php echo $page_list; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $search_model->view ?>
<?php $this->load->view('_partial/public_area_list'); ?>

<script type="text/javascript">
    function action_form(formid, url) {
        $('#' + formid).attr('action', '<?php echo site_url(ACCOUNT_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(ACCOUNT_ACTION); ?>' + url).submit();
        });
    }
</script>