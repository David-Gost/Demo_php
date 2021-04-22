<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="<?php echo base_url(get_fullpath_with_file(get_seesion_user()['picinfo'])); ?>" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
        <a href="<?php echo site_url('My_dashboard/profile'); ?>" class="d-block"><?php echo get_seesion_user()[USER_SESS_CNAME]; ?></a>
    </div>
</div>

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <?php
        if (!is_null(get_seesion_user())) {
            $user_data = get_seesion_user();
            $modules = $user_data['module'];
        }
        //判斷是否有相同module_id的flag
        $temp_id = 0;
        if (isset(get_seesion_user()['module'][get_module_url()]['sid'])) {
            $click_module_id = get_seesion_user()['module'][get_module_url()]['sid'];
        } else {
            $click_module_id = '';
        }
        foreach ($modules as $module) {

            $sid = $module['sid'];
            $module_name = $module['module_name'];
            $module_icons = $module['module_icons'];

            //判斷相同的module_id則不執行
            if ($temp_id == $sid) {
                continue;
            }

            if ($click_module_id == $sid) {
                $open = 'menu-open';
                $active = 'active';
            } else {
                $open = '';
                $active = '';
            }

            if (!$module["r"]) {
                continue;
            }
        ?>
            <li id="<?php echo $sid; ?>" class="nav-item has-treeview <?= $open; ?>">
                <a href="#" class="nav-link <?php // echo $active; 
                                            ?>">
                    <i class="nav-icon fa <?php echo ($module_icons == '') ? 'fa-folder' : $module_icons; ?>"></i>
                    <p>
                        <?php echo $module_name; ?>
                        <i class="right fa fa-angle-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <?php
                    // AC_MARK:因為開分頁依照原本紀錄sesstion的方式會造成看B頁時左欄選擇在A頁,因此先以抓網址rule_name判斷
                    if (isset(get_seesion_user()['module'][get_module_url()])) {
                        $now_rule_name = get_seesion_user()['module'][get_module_url()]['rule_name'];
                    } else {
                        $now_rule_name = '';
                    }
                    foreach ($modules as $key => $rule) {
                        if (!$rule["r"]) {
                            continue;
                        }

                        $module_sid = $rule['module_sid'];
                        $rule_sid = $rule['rule_sid'];
                        $rule_name = $rule['rule_name'];
                        $url = $rule['url'];
                        $temp_key = $this->session->userdata('temp_key');
                        if ($sid == $module_sid) {
                    ?>
                            <li class='nav-item'><a href="<?php echo site_url($url); ?>" class="nav-link <?= ($rule_name == $now_rule_name) ? 'active' : '' ?>" onclick='setActive(<?php echo $sid; ?>,<?php echo $key; ?>)'><i class="fa fa-circle-o nav-icon"></i>
                                    <p><?php echo $rule_name; ?></p>
                                </a></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </li>
        <?php
            $temp_id = $sid;
        }
        ?>

        <li class="nav-header"></li>
        <li class="nav-item">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">
                <i class="nav-icon fa fa-circle-o text-info"></i>
                <p>後台面板</p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->

<script type="text/javascript">
    function setActive(id, key) {
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('ajax/ajax_menu/keep_module') ?>',
            dataType: 'json',
            data: {
                module_id: id,
                key: key
            }
        });
    }
</script>