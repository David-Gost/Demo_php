<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sysinfo_dto->sitetitle; ?></title>
    <?php $this->load->view('_partial/sysinfo'); ?>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'dist/css/adminlte.min.css'); ?>">
    <!--iCheck-->
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'plugins/iCheck/all.css'); ?>">

    <!-- jQuery -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/iCheck/icheck.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url(ASSETS_BACKEND . 'plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!--only for this page start-->
    <link href="https://fonts.googleapis.com/css?family=Bowlby+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(ASSETS_BACKEND . 'dist/css/loginpage.css'); ?>">
    <style>
        .bg {
            background: url(<?php echo ($sysinfo_dto->bgpic != '') ? base_url(get_fullpath_with_file($sysinfo_dto->bgpic)) : ''; ?>)center center no-repeat;
            background-size: cover;
            background-attachment: fixed;
            position: fixed;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            -webkit-filter: blur(2px);
            filter: blur(2px);
            z-index: -1
        }

        .bg:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2)
        }

        .text-maskwrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent
        }

        .text-mask {
            font-family: 'Bowlby One', 'Noto Sans TC', 'Microsoft JhengHei', sans-serif;
            font-size: 50px;
            font-weight: 500;
            margin-bottom: 30px;
            width: 100%;
            color: #fff;
            text-align: center;
            line-height: 1;
            background: url(<?php echo ($sysinfo_dto->bgpic != '') ? base_url(get_fullpath_with_file($sysinfo_dto->bgpic)) : ''; ?>)center center no-repeat;
            background-size: cover;
            background-attachment: fixed;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: background 2s
        }

        @media screen and (max-width:480px) {
            .text-mask {
                font-size: 40px
            }

            .bg:before {
                background: rgba(255, 255, 255, 0.4)
            }
        }
    </style>
</head>

<body class="login-page ">
    <div class="<?php echo $sysinfo_dto->bgpic == '' ? '' : 'bg'; ?>"></div>
    <div class="content_outer">
        <div class="content_middle">
            <div class='text-maskwrapper'>
                <div class='text-mask'><?php echo $sysinfo_dto->sitename; ?></div>
            </div>
            <div class="content_inner">
                <div class="login-logo-detail">
                    <div class="login-box" style=" margin: 0 auto;">
                        <div class="login-logo login-box-top">
                            <a href="#"><b><?php echo $sysinfo_dto->sitecompany; ?></b>網站管理系統</a>
                        </div><!-- /.login-logo -->
                        <div class="login-box-body login-box-botton">
                            <p class="login-box-msg">使用者登入資訊</p>
                            <form action="<?php echo base_url('login/process'); ?>" method="post">
                                <!--alert error-->
                                <?php echo alert_box() ?>
                                <div class="form-group has-feedback">
                                    <input type="text" name="username" class="form-control" placeholder="Username" value="" />
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" name="password" class="form-control" placeholder="Password" value="" />
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                                <?php if ($is_useverify) : ?>
                                    <div class="form-group has-feedback verification-code">
                                        <span class="verification-code-detail" id="captcha">
                                            <?php echo $captcha['image']; ?>
                                        </span>
                                        <a><i class="fa fa-refresh fa-lg fa-fw  " onclick="reset_captcha()"></i></a>
                                    </div>
                                    <div class="form-group has-feedback display">
                                        <input type="text" name="userCaptcha" class="form-control" placeholder="captcha" value="" />
                                        <span class="glyphicon glyphicon-certificate form-control-feedback"></span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <button type="submit" class="default_btn">登入</button>
                                </div>
                            </form>
                        </div><!-- /.login-box-body -->
                    </div><!-- /.login-box -->
                </div>
            </div>
            <!--公司資訊-->
            <footer class="footer_bottom">
                <div class="container">
                    <div class="footer_copyright">
                        <span class="copyright_left">© Copyright 2018 <?php echo $sysinfo_dto->metaauthor; ?> 版權所有，翻印必究</span>
                        <span class="copyright_right">Designed by <span><?php echo $sysinfo_dto->metaauthor; ?></span></span>
                    </div>
                </div>
            </footer>
            <!--//公司資訊-->
        </div>
    </div>

    <script>
        function reset_captcha() {
            $.ajax({
                url: '<?php echo BASE_URL . '/ajax/ajax_captcha/build_captcha'; ?>',
                data: {},
                type: "POST",
                dataType: 'json',
                success: function(result) {
                    $('#captcha').html(result.captcha);
                }
            });
        }
    </script>
</body>

</html>