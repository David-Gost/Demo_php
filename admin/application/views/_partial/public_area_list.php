<!-- 刪除確認 Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header">
                <div class="pull-left">
                    <h4 id="time_modal_label">刪除項目</h4>
                </div>
                <div class="pull-right">
                    <div class="btn-group"><button type="button" class="btn btn-tool" data-dismiss="modal"><i class="fa fa-remove"></i></button></div>
                </div>
            </div>
            <div class="card-body">
                確定要刪除此項目??
            </div>
            <div class="card-footer">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-info" id="modal_btn_del">確定</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //是否使用
    function change_is_use(table, field, sid, val) {
        $.ajax({
            url: '<?php echo BASE_URL . '/ajax/ajax_change/modify_field_val'; ?>',
            data: {
                table: table,
                field: field,
                sid: sid,
                val: val
            },
            type: "POST",
            dataType: 'json',
            success: function(result) {
                if (result != 'OK') {
                    alert('設定錯誤');
                }
            }
        });
    }

    //變更排序
    function change_sequence(table, field, sid) {
        sequence = $('#sq_' + sid).val();
        $.ajax({
            url: '<?php echo BASE_URL . '/ajax/ajax_change/modify_field_val'; ?>',
            data: {
                table: table,
                sid: sid,
                field: field,
                val: sequence
            },
            type: "POST",
            dataType: 'json',
            success: function(result) {
                if (result != 'OK') {
                    alert('設定錯誤');
                } else {
                    location.reload();
                }
            }
        });
    }
</script>