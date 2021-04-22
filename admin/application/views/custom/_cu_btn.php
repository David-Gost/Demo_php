<?php if (get_seesion_user()['module'][get_module_url()]['c'] == 1 && $active_type == 'a') { ?>
    <a class="btn btn-success btn-sm fa fa-save" href="#" id="btnsave" style="margin-right:5px;">&nbsp;新增</a>
<?php } else if (get_seesion_user()['module'][get_module_url()]['u'] == 1 && $active_type == 'm') { ?>
    <a class="btn btn-success btn-sm fa fa-save" href="#" id="btnsave">&nbsp;儲存</a>
<?php }; ?>