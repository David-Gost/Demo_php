<!-- insert head start-->
<?php $this->load->view('_partial/head'); ?>
<!-- insert head end -->
<!-- insert header start -->
<?php $this->load->view('_partial/header'); ?>
<!-- insert header end -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo -->
    <?php $this->load->view('_partial/logo'); ?>

    <!-- Sidebar -->
    <div class="sidebar">
       <?php $this->load->view('_partial/menu'); ?>
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{title}}</h1>
                </div>
                <!-- insert breadcrumb start -->
                <?php $this->load->view('_partial/breadcrumb'); ?>
                <!-- insert breadcrumb end -->
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content start-->
    <section class="content">   
        {body}
    </section><!-- /.content -->
    <!-- Main content end-->
</div>
<!-- Content Wrapper. Contains page content end -->

<!-- insert footer start-->
<?php $this->load->view('_partial/footer') ?>
<!-- insert footer end -->