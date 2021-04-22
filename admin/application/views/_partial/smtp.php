<?php define("SMTP_ACTION", 'sys/smtp_manage/'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SMTP維護</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm fa fa-save" autocomplete="off" onclick="modify_smpt();">&nbsp;儲存</button>
                        </div>
                    </div>
                    <form method="post" id='smtp_form'>
                        <div class="card-body">
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">信件發送設定</h3>
                                </div>
                                
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>系統信箱名稱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="admin_cname" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統寄信時的寄件者名稱</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>系統信箱</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="smtp_user" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統信箱(寄件用)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>系統信箱密碼</td>
                                                    <td>
                                                        <input type="password" class="form-control" name="smtp_pass" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ 系統信箱密碼(寄件用)</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>SMTP PORT</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="smtp_port" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ SMTP PORT</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>SMTP CRYPTO</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="smtp_crypto" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ SMTP CRYPTO</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>SMTP HOST</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="smtp_host" />
                                                    </td>
                                                    <td>
                                                        <span class="description_text">※ SMTP HOST</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<script type="text/javascript">
    get_smtp_dto()

    function get_smtp_dto() {
        $.ajax({
            method: "POST",
            url: "<?php echo site_url(SMTP_ACTION . 'get_smtp_dto'); ?>",
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status) {
                    $.each(data.value, function(key, val) {
                        $('[name="' + key + '"]').val(val);
                    });

                }
            }
        });
    }


    function modify_smpt() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(SMTP_ACTION . 'modify_smtp'); ?>",
            dataType: 'json',
            data: $('#smtp_form').serialize(), // serializes the form's elements.
            success: function(result) {
                console.log(result);
                if (result.status) {
                    alert(result.msg);
                    get_smtp_dto()
                } else {
                    alert(result.msg);
                }
            }
        });

    }
</script>