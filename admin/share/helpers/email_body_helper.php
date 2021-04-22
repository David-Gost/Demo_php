<?php

function email_body_demo() {

    $body_head = mail_body_head();
    $body_big_title = mail_body_big_title('各種信件大標題');
    $body_small_title = mail_body_small_title('各種信件小標題（可移除）');
    $body_pic = mail_body_pic('');
    $body_description = mail_body_description();
    $body_text = mail_body_text();
    $body_back = mail_body_back();
    $body_foot = mail_body_foot();
    $body_line = mail_body_line();
    $body_empty = mail_body_empty();

    $body = '';

    return $body_head . $body_big_title . $body_small_title . $body_pic . $body_description . $body_text . $body_back . $body_foot;
}

//----------------------------------------------以下為範例----------------------------------------------

function mail_body_head() {
    $body = '<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0;"/>
        <meta name="format-detection" content="telephone=no"/>

        <style>
            /* Reset styles */ 
            body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
            body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
            table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
            img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
            #outlook a { padding: 0; }
            .ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }

            /* Extra floater space for advanced mail clients only */ 
            @media all and (max-width: 600px) {
                .floater { width: 320px; }
            }

            /* Set color for auto links (addresses, dates, etc.) */ 
            a, a:hover {
                color: #127DB3;
            }
            .footer a, .footer a:hover {
                color: #999999;
            }

        </style>

        <!-- MESSAGE SUBJECT -->
        <title>email</title>

    </head>

    <!-- BODY -->
    <!-- Set message background color (twice) and text color (twice) -->
    <body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;color: #000000;" text="#000000" bgcolor="#f6f6f6">

        <!-- SECTION / BACKGROUND -->
        <!-- Set section background color -->
        <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background">

            <!-- ======== 預留 空白區塊 ========-->
            <tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <!-- ======== 預留 空白區塊 end ========-->

            <tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;">

                    <!-- WRAPPER -->
                    <!-- Set wrapper width (twice) -->
                    <table border="0" cellpadding="0" cellspacing="0" align="center"
                           width="600" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
                           max-width: 600px;" class="wrapper" bgcolor="#ffffff">
                        <!-- ======== LOGO 請依照logo大小調整寬度和高度 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                                padding-top: 20px;">
                                <!-- LOGO -->
                                <!-- Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2. URL format: http://domain.com/?utm_source={{Campaign-Source}}&utm_medium=email&utm_content=logo&utm_campaign={{Campaign-Name}} -->
                                <a target="_blank" style="text-decoration: none;"
                                   href="' . str_replace("/admin", "", site_url(FRONT)) . '"><img border="0" vspace="0" hspace="0"
                                        src="' . str_replace('https', 'http', str_replace('/admin', '', base_url(ASSETS_BACKEND . 'images/logo.png'))) . '"
                                        width="250" height="42"
                                        alt="Logo" title="Logo" style="
                                        color: #000000;
                                        font-size: 10px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;" /></a>
                            </td>
                        </tr>
                        <!-- ======== LOGO end========-->';
    return $body;
}

function mail_body_big_title($title) {
    $body = '<!-- ======== 標題列 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
                                padding-top: 20px;
                                color: #000000;
                                font-family: sans-serif;" class="header">
                                ' . $title . '
                            </td>
                        </tr>';
    return $body;
}

function mail_body_small_title($title) {
    $body = '<tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
                                padding-top: 5px;
                                color: #000000;
                                font-family: sans-serif;" class="subheader">
                                ' . $title . '
                            </td>
                        </tr>
                        <!-- ======== 標題列 end========-->';
    return $body;
}

function mail_body_pic($picinfo = '') {
    $body = '
                        <!-- ======== 信件大圖 ========-->
                        <!-- ======== 預留 空白區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <!-- ======== 預留 空白區塊 end ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                                padding-top: 20px;" class="hero"><a target="_blank" style="text-decoration: none;"
                                              href="' . (($picinfo) ? base_url(get_fullpath_with_file($picinfo)) : str_replace("/admin", "", site_url(FRONT))) . '"><img border="0" vspace="0" hspace="0"
                            src="' . (($picinfo) ? base_url(get_fullpath_with_file($picinfo)) : (str_replace("https", "http", str_replace("/admin", "", base_url(ASSETS_BACKEND . "images/logo.png"))))) . '"
                            alt="Please enable images to view this content" title="Hero Image"
                            width="530" style="
                            width: 88.33%;
                            max-width: 530px;
                            color: #FFFFFF; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/></a></td>
                        </tr>
                        <!-- ======== 信件大圖 end ========-->';
    return $body;
}

//範例用
function mail_body_description() {
    $body = '<!-- ======== 各種描述文字區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
                                padding-top: 25px; 
                                color: #000000;
                                font-family: sans-serif;" class="paragraph">
                                我們已收到你的訂單，訂單明細請返回&nbsp;<a href="http://ui.acubedt.com/rwd_lina/application/views/member_orderlist.php" target="_blank" style="color: #e13e84; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 160%;">訂單列表</a>&nbsp;查詢。
                            </td>
                        </tr>

                        <!-- ======== 各種描述文字區塊 end ========-->';
    return $body;
}

//範例用
function mail_body_text() {
    $body = '<!-- ======== 預留 空白區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <!-- ======== 預留 空白區塊 end ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 10px; padding-right: 10px;" class="floaters">
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="left" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            <span style="color: #e13e84;">匯款總金額</span>
                                        </td>
                                    </tr>
                                </table>
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="right" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            $19,900元整
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 10px; padding-right: 10px;" class="floaters">
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="left" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            <span style="color: #e13e84;">銀行名稱:</span>
                                        </td>
                                    </tr>
                                </table>
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="right" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            中華郵政(代碼：700)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 10px; padding-right: 10px;" class="floaters">
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="left" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            <span style="color: #e13e84;">匯款帳號</span>
                                        </td>
                                    </tr>
                                </table>
                                <table width="280" border="0" cellpadding="0" cellspacing="0" align="right" valign="top" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; display: inline-table; float: none;" class="floater">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; font-size: 17px; font-weight: 400; line-height: 160%;padding-top: 30px;font-family: sans-serif;color: #000000;">
                                            0000000-00000000
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        ';
    return $body;
}

function mail_body_back() {
    $body = '<!-- ======== 預留 空白區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>


                        <!-- BUTTON -->
                        <!-- Set button background color at TD, link/text color at A and TD, font family ("sans-serif" or "Georgia, serif") at TD. For verification codes add "letter-spacing: 5px;". Link format: http://domain.com/?utm_source={{Campaign-Source}}&utm_medium=email&utm_content={{Button-Name}}&utm_campaign={{Campaign-Name}} -->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                                padding-top: 25px;
                                padding-bottom: 35px;" class="button"><a
                                    href="' . str_replace("/admin", "", site_url(FRONT)) . '" target="_blank" style="text-decoration: underline;">
                                    <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle" style="padding: 12px 24px; margin: 0; text-decoration: underline; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
                                                                                                                                                                                                        bgcolor="#0B5073"><a target="_blank" style="text-decoration: underline;
                                                                 color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;"
                                                                 href="' . str_replace("/admin", "", site_url(FRONT)) . '">
                                                    回首頁
                                                </a>
                                            </td></tr></table></a>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;word-wrap:break-word;
                                padding-top: 25px;" class="line"><hr
                                    color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                            </td>
                        </tr>

                        <!-- End of WRAPPER -->
                    ';
    return $body;
}

function mail_body_foot() {
    $body = '</table>
                    <!-- SECTION / BACKGROUND -->
                    <!-- Set section background color -->
                </td>
            </tr>
            
            <tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;">

                    <!-- WRAPPER -->
                    <!-- Set wrapper width (twice) -->
                    <table border="0" cellpadding="0" cellspacing="0" align="center"
                           width="600" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
                           max-width: 600px;" class="wrapper" >

                        <!-- ======== 預留 空白區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <!-- ======== 預留 空白區塊 end ========-->


                        <!-- FOOTER -->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
                                padding-top: 20px;
                                padding-bottom: 20px;
                                color: #999999;
                                font-family: sans-serif;" class="footer">
                            </td>
                        </tr>
                        <!-- End of WRAPPER -->
                    </table>
                    <!-- End of SECTION / BACKGROUND -->
                </td>
            </tr>
            <!-- ======== 預留 空白區塊 ========-->
            <tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <!-- ======== 預留 空白區塊 end ========-->
        </table>

    </body>
</html>';
    return $body;
}

function mail_body_line() {
    $body = '<tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;word-wrap:break-word;
                    padding-top: 25px;" class="line"><hr
                        color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                </td>
            </tr>';
    return $body;
}

function mail_body_empty() {
    $body = '<!-- ======== 預留 空白區塊 ========-->
                        <tr>
                            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px;padding-top: 7px;padding-bottom: 7px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <!-- ======== 預留 空白區塊 end ========-->';
    return $body;
}

//寄信
function send_email_body($sid, $to, $content, $havecc = true) {
    if ($sid == '' || empty($sid)) {
        $sid == 1;
    }

    $CI = & get_instance();
    $CI->load->model("sys/Mailinfo_model", "mailinfo_model");

    $mailinfo_dto = $CI->mailinfo_model->find_by_id($sid);
    $CI->email->from($mailinfo_dto->frommail, $mailinfo_dto->fromname);

    if ($havecc) {
        $CI->email->reply_to($mailinfo_dto->addmail, $mailinfo_dto->addname);
    }

    if ($to != '') {
        $CI->email->to($to);
    } else {
        $CI->email->to($mailinfo_dto->addmail);
    }

    if ($mailinfo_dto->isbcc == 1) {
        $CI->email->bcc($mailinfo_dto->addmail); //管理者收密件副本				
    } else {

        if ($havecc) {
            if ($mailinfo_dto->isrepeat == 1) {
                $CI->email->cc($mailinfo_dto->addmail); //管理者收副本
            }
        }
    }

    $CI->email->subject($mailinfo_dto->subject);
    $CI->email->message($content);

    return $CI->email->send();
}
