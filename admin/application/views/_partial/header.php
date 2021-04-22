<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <!--上方選單-->
        <?php // $this->load->view('_partial/top_menu'); ?>
    </ul>

    <!--搜尋框-->
    <?php // $this->load->view('_partial/search'); ?>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- 瀏覽記錄下拉 -->
        <?php $this->load->view('_partial/history_menu'); ?>
        <!-- Messages Dropdown Menu -->
        <?php // $this->load->view('_partial/messages_menu'); ?>
        <!-- Notifications Dropdown Menu -->
        <?php // $this->load->view('_partial/notice_menu'); ?>
        
<!--        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fa fa-th-large"></i></a>
        </li>-->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('My_dashboard/profile'); ?>"><i class="fa fa-user"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('login/logout'); ?>"><i class="fa fa-sign-out"></i></a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->