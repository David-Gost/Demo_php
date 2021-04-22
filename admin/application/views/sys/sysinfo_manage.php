<?php define("SYSINFO_MANAGE_ACTION", 'sys/sysinfo_manage/'); ?>
<?php echo alert_box(); ?>
<style>
    .description_text {
        color: red;
        font-size: 10pt;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <form id="data_form" class="form-group-sm" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="pull-left">
                                <h3 class="card-title">{{title}}</h3>
                            </div>
                            <div class="pull-right">
                                <input type="hidden" name="sid" id="sid" value="<?php echo $sysinfo->sid ?>" />
                                <input type="hidden" name="bgpic" id="bgpic" value="<?php echo $sysinfo->bgpic ?>" />
                                <div class="btn-group"> <button type="button" id="btnsave" class="btn btn-info btn-sm fa fa-save" autocomplete="off">&nbsp;儲存</button></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <div class="pull-left">
                                        <h3 class="card-title">系統資訊設置</h3>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>系統名稱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="sitename" id="sitename" value="<?php echo $sysinfo->sitename ?>">
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統名稱(後台登入系統名稱)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>系統名稱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="sitecompany" id="sitecompany" value="<?php echo $sysinfo->sitecompany ?>">
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統名稱(後台登入公司名稱)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>系統子名稱(英)</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="subsitename" id="subsitename" value="<?php echo $sysinfo->subsitename ?>">
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統子名稱(後台系統子名稱-英)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>系統小圖</td>
                                                    <td>
                                                        <img src='<?php echo base_url(get_fullpath_with_file($sysinfo->sysico)); ?>' style="width:40px;float: left;" />
                                                        <input type="hidden" name="origin_ico" value="<?php echo $sysinfo->sysico; ?>" />
                                                        <div>
                                                            <?php if (get_seesion_user()['module'][get_module_url()]['u'] == 1) : ?>
                                                                <?php if (!empty($sysinfo->sysico)) : ?>
                                                                    <a class="btn btn-xs" title="刪除圖片" onclick="delete_ico(<?php echo $sysinfo->sid ?>);">
                                                                        <i class="fa fa-remove"></i>刪除圖片
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <span style="color:#FF0000;">※ 建議大小：40x40 px</span>

                                                            <input type="file" name="sysico" id="sysico" class=" btn" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 設定網站的小圖</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>啟用https(前台)</td>
                                                    <td>
                                                        <input type="checkbox" name='httpson_front' class="minimal" id="httpson_front" <?php echo show_checked($sysinfo->httpson_front); ?> onchange="checkboxStatus(this);" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 用來啟用或關閉https傳輸協定(前台)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>啟用https(後台)</td>
                                                    <td>
                                                        <input type="checkbox" class="minimal" name='httpson_end' id="httpson_end" <?php echo show_checked($sysinfo->httpson_end); ?> onchange="checkboxStatus(this);" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 用來啟用或關閉https傳輸協定(後台)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>登入驗證碼</td>
                                                    <td>
                                                        <input type="checkbox" class="minimal" name='useverify' id="useverify" <?php echo show_checked($sysinfo->useverify); ?> onchange="checkboxStatus(this);" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 用來啟用或關閉登入驗證碼機制</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>存取來源IP</td>
                                                    <td>
                                                        <textarea id="ipsource" class="form-control" name="ipsource" rows="5"><?php echo $sysinfo->ipsource ?></textarea>
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 用來限制可存取的來源IP，一般以IPv4格式 xxx.xxx.xxx.xxx，並使用換行來新增多筆<br />※ 如不限制，請保留空白。</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <div class="pull-left">
                                        <h3 class="card-title">網站參數資訊設置</h3>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>網站網址</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="siteurl" id="siteurl" value="<?php echo $sysinfo->siteurl ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 網站網址(信件中網址連結用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站標題名稱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="sitetitle" id="sitetitle" value="<?php echo $sysinfo->sitetitle ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 網站標題，勿過長(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站公司名稱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metacompany" id="metacompany" value="<?php echo $sysinfo->metacompany ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的相關描述(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站版權所有宣告</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metacopyright" id="metacopyright" value="<?php echo $sysinfo->metacopyright ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的版權所有者(公司)名稱(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站分類等級</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metarating" id="metarating" value="<?php echo $sysinfo->metarating ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的瀏覽分類等級(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網頁訪問權限</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metarobots" id="metarobots" value="<?php echo $sysinfo->metarobots ?>" />
                                                        <input type='date' class="form-control" style="width: 100%; visibility: hidden;" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 告知搜尋引擎哪些頁面需要索引，哪些頁面不需要索引。<br />all：HTML 檔案將被檢索，且頁面上的鏈結可以被查詢。<br />
                                                            none：HTML 檔案將不被檢索，且頁面上的鏈結不可以被查詢。<br />
                                                            index：HTML 檔案將被檢索。<br />
                                                            follow：HTML 檔案頁面上的鏈結可以被查詢。<br />
                                                            noindex：HTML 檔案將不被檢索，但頁面上的鏈結可以被查詢。<br />
                                                            nofollow：HTML 檔案將不被檢索，頁面上的鏈結可以被查詢。
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>是否被Cache所限制</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metapragma" id="metapragma" value="<?php echo $sysinfo->metapragma ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 禁止瀏覽器從本地機的緩存中調閱網頁內容。這樣設定，訪問者將無法離線瀏覽。</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網頁緩存</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metacache_control" id="metacache_control" value="<?php echo $sysinfo->metacache_control ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 禁止瀏覽器從本地機的緩存中調閱網頁內容。這樣設定，訪問者將無法離線瀏覽。</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網頁到期時間</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metaexpires" id="metaexpires" value="<?php echo $sysinfo->metaexpires ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 設定網頁到期時間，一旦網頁過期，必須到服務器上重新調閱。必須使用 GMT 的時間格式(例：Sun, 31 Dec 2000 00:00:01 GMT)。如設content=-1，表永遠會讀取最新資料</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <div class="pull-left">
                                        <h3 class="card-title">網站建立者資訊</h3>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>網站建立者</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metaauthor" id="metaauthor" value="<?php echo $sysinfo->metaauthor ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的建立者(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站建立者信箱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metaauthoremail" id="metaauthoremail" value="<?php echo $sysinfo->metaauthoremail ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的建立者的信箱</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站建立者網址</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="metaauthorurl" id="metaauthorurl" value="<?php echo $sysinfo->metaauthorurl ?>" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的建立者的網站</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>網站建立日期</td>
                                                    <td>
                                                        <input type='date' class="form-control flatpickr_date" style="background-color: white;" name="metacreationdate" id="metacreationdate" value="<?php echo $sysinfo->metacreationdate ?>" autocomplete="off" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 此網站的建立日期(搜尋引擎參考用)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>


<script type="text/javascript">
    $('#btnsave').bind('click', function() {
        $('#data_form').attr('action', "<?php echo site_url(SYSINFO_MANAGE_ACTION . 'modify_sysinfo'); ?>").submit();
    });

    function delete_ico(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('ajax/ajax_uploadfile_del/del_sysinfo_ico') ?>',
            dataType: 'json',
            data: {
                sid: id
            },
            success: function(data) {
                if (data.result > 0) {
                    location.href = '<?php echo site_url(SYSINFO_MANAGE_ACTION); ?>';
                }
            }
        });
    }

    function checkboxStatus(checkboxObj) {
        if (checkboxObj.checked == true) {
            checkboxObj.value = 1;
        } else {
            checkboxObj.value = 0;
        }
    }
</script>