<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $sysinfo_dto->sitetitle; ?></title>
    <!--insert sysinfo start-->
    <?php $this->load->view('_partial/sysinfo'); ?>
    <!--insert sysinfo end-->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!---------------------------------------------------AdminLTE start------------------------------------------------------------>
    <!-- jQuery UI 1.12.1 -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/jQueryUI/jquery-ui.min.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/Ionicons/css/ionicons.min.css'); ?>">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/AutoFill-2.3.4/css/autoFill.bootstrap4.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/css/buttons.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/ColReorder-1.5.2/css/colReorder.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/FixedColumns-3.3.0/css/fixedColumns.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/FixedHeader-3.1.6/css/fixedHeader.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/RowGroup-1.1.1/css/rowGroup.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/RowReorder-1.2.6/css/rowReorder.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Scroller-2.0.1/css/scroller.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/SearchPanes-1.0.1/css/searchPanes.bootstrap4.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Select-1.3.1/css/select.bootstrap4.min.css'); ?>" />
    <!--iCheck-->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/iCheck/all.css'); ?>">
    <!-- select2 -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/select2/select2.min.css'); ?>">
    <!-- smartselect -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/smartselect/smartselect.min.css'); ?>">
    <!-- flatpickr 日期選擇 -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/flatpickr/dist/flatpickr.min.css'); ?>">
    <!-- colorpicker -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/morris/morris.css'); ?>">
    <!--fullcalendar 行事曆-->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/fullcalendar/fullcalendar.css'); ?>">
    <link rel="stylesheet" media="print" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/fullcalendar/fullcalendar.print.css'); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'dist/css/adminlte.min.css'); ?>">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!---------------------------------------------------JS------------------------------------------------------------>
    <!-- jQuery -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- 月曆,語言需要的套件 -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
    <!-- jQuery UI 1.12.1 -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/jQueryUI/jquery-ui.min.js'); ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/JSZip-2.5.0/jszip.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/pdfmake-0.1.36/pdfmake.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/pdfmake-0.1.36/vfs_fonts.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/AutoFill-2.3.4/js/dataTables.autoFill.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/AutoFill-2.3.4/js/autoFill.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/buttons.colVis.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/buttons.flash.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/buttons.html5.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Buttons-1.6.1/js/buttons.print.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/ColReorder-1.5.2/js/dataTables.colReorder.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/FixedColumns-3.3.0/js/dataTables.fixedColumns.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/FixedHeader-3.1.6/js/dataTables.fixedHeader.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/RowGroup-1.1.1/js/dataTables.rowGroup.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/RowReorder-1.2.6/js/dataTables.rowReorder.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Scroller-2.0.1/js/dataTables.scroller.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/SearchPanes-1.0.1/js/dataTables.searchPanes.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(ASSETS_BACKEND . 'plugins/datatables/Select-1.3.1/js/dataTables.select.min.js'); ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/fastclick/fastclick.js'); ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/iCheck/icheck.min.js'); ?>"></script>
    <!-- flatpickr 日期選擇 -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/flatpickr/dist/flatpickr.js'); ?>"></script>
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/flatpickr/dist/l10n/zh.js'); ?>"></script>
    <!-- colorpicker -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
    <!-- select2 -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/select2/select2.min.js'); ?>"></script>
    <!-- smartselect -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/smartselect/jquery.smartselect.js'); ?>"></script>
    <!--ckeditor-->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/ckeditor/ckeditor.js') ?>"></script>
    <!-- popper 提示文字-->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/popper/popper.js'); ?>"></script>
    <!-- slimScroll-->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/morris/morris.min.js'); ?>"></script>

    <!--fullcalendar 行事曆-->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/fullcalendar/fullcalendar.js'); ?>"></script>
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/fullcalendar/zh-tw.js'); ?>"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'dist/js/adminlte.js'); ?>"></script>
    <!---------------------------------------------------AdminLTE end------------------------------------------------------------>

    <!--裁切圖片-->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'user/css/cut_pic.css'); ?>">
    <script src="<?php echo base_url(ASSETS_BACKEND . 'user/js/cut_pic.js'); ?>"></script>
    <!-- iOS7按鈕 -->
    <link href="<?php echo base_url(ASSETS_BACKEND . 'user/css/switchery.min.css'); ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(ASSETS_BACKEND . 'user/js/switchery.min.js'); ?>" type="text/javascript"></script>
    <!--Messenger-->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'user/js/messenger.js'); ?>"></script>
    <link href="<?php echo base_url(ASSETS_BACKEND . 'user/css/messenger.css'); ?>" rel='stylesheet' type='text/css'>

    <!-- cms -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'user/css/acubedt_cms.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'user/css/style_admin.css'); ?>">
    <script src="<?php echo base_url(ASSETS_BACKEND . 'user/js/acubedt.js'); ?>"></script>

    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/chartjs-old/Chart.min.js'); ?>"></script>

    <style>
            <!--a標籤理面icon顏色為白色-->
            .btn-group > a >.fa ,.btn-group > span > a >.fa{
                color: white;
            }

            .flatpickr-wrapper{
                position: relative;
                display: inline-block;
                width: 100%;
            }


            .flatpickr_date{
                style:" background-color: white;";
            }


            .list_img{
                max-width:100px;
                height: auto;
            }

            .table-overflow-x{
                overflow-x:auto;
            }

        </style>

</head>
<!--測邊欄預設收和-->
<!--<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">-->
<!--測邊欄預設打開-->

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">