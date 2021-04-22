<?php define("GROUPS_MANAGE_ACTION", 'sys/groups_manage/') ?>
<?php echo alert_box(); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="pull-left">
                            <h3 class="card-title">權限群組清單</h3>
                        </div>
                        <div class="pull-right">
                            <?php if (get_seesion_user()['module'][get_module_url()]['c'] == 1) : ?>
                                <div class="btn-group"><a href="<?php echo site_url(GROUPS_MANAGE_ACTION . 'add_groups/'); ?>" class="btn btn-info btn-sm fa fa-plus">新增</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable_only_search" style="width:100%">
                                <thead>
                                    <tr>
                                        <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                            <th>管理</th>
                                        <?php endif; ?>
                                        <th>群組名稱</th>
                                        <th>備註</th>
                                        <th>群組人數</th>
                                        <th>建立日期</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($groups_list as $groups_dto) : ?>
                                        <?php if ($groups_dto->sid != 1 || get_seesion_user()['groups_sid'] == 1) : ?>
                                            <tr>
                                                <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1 || get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                    <td>
                                                        <?php $id = 'choice_form_' . $groups_dto->sid; ?>
                                                        <form id="<?= $id ?>" method="post">
                                                            <!--隱藏欄位-->
                                                            <input type="hidden" name="sid" value="<?php echo $groups_dto->sid ?>" />
                                                            <div class="btn-group">
                                                                <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="編輯">
                                                                        <a class="btn btn-sm btn-info" onclick="action_form('<?= $id ?>', 'modify_groups');"><i class="fa fa-edit"></i></a>
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if (get_seesion_user()['module'][get_module_url()]['d'] == 1) : ?>
                                                                    <?php if ($groups_dto->sid > 2) : ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="刪除">
                                                                            <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#myModal" onclick="action_del('<?= $id ?>', 'del_groups');"><i class="fa fa-trash"></i></a>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </form>
                                                    </td>
                                                    <td><?php echo $groups_dto->cname; ?></td>
                                                    <td><?php echo $groups_dto->remark; ?></td>
                                                    <td><?php echo $groups_dto->groups_no; ?></td>
                                                    <td><?php echo mdate(DATE_FORMAT, $groups_dto->createdate); ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endif; ?>
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
        $('#' + formid).attr('action', '<?php echo site_url(GROUPS_MANAGE_ACTION); ?>' + url).submit();
    }

    function action_del(formid, url) {
        $('#modal_btn_del').bind('click', function() {
            $('#' + formid).attr('action', '<?php echo site_url(GROUPS_MANAGE_ACTION); ?>' + url).submit();
        });
    }
</script>