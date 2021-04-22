<!-- Sidebar user panel -->
<div class="user-panel">
    <div class="pull-left image">
        <img style="visibility: hidden" src="<?php echo base_url(ASSETS_BACKEND . 'dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p><?php echo get_seesion_user()[USER_SESS_CNAME]; ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>