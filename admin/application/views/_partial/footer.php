<footer class="main-footer">
    <div class="float-right d-none d-sm-inline-block">
        <strong>系統時間：<span id="tx"></span></strong> 
    </div>
    <strong>Copyright &copy; <?php echo substr($sysinfo_dto->metacreationdate, 0, 4); ?> <a href="<?php echo $sysinfo_dto->metaauthorurl; ?>"><?php echo $sysinfo_dto->metaauthor; ?></a>.</strong> All rights reserved.
</footer>
</div>

<script type="text/javascript">
    // FlipSwitch
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {size: 'small'});
    });

    // 系統時間 
    time();
    function time() {
        var d = new Date();
        document.getElementById("tx").innerHTML = d.toLocaleTimeString();
    }
    setInterval(time, 1000);

    //select2
    $(".select").select2();

    //iCheck
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    //datatable
    $('.datatable_default').DataTable();
    $('.datatable_only_search').DataTable({
        "paging": false, //分頁
        "ordering": false //排序
    });
    $('.datatable_no_order').DataTable({
        "ordering": false //排序
    });

    //popper　提示文字
    $('[data-toggle="tooltip"]').tooltip();

    $(".flatpickr_date").flatpickr({
        'allowInput': true,
        "disableMobile": true
    });

    $(".flatpickr_date_time").flatpickr({
        'allowInput': true,
        'enableTime': true,
    });

//刪除照片
    function delete_pic(id, table, field, view, origin, is_reload = true)
    {
        $.ajax({
            type: "POST",
            url: '<?php echo BASE_URL . '/ajax/ajax_uploadfile_del/del_pic'; ?>',
            dataType: 'json',
            data: {sid: id, table: table, field: field, view: view, origin: origin},
            success: function (data) {
                if (data.result > 0) {
                    if (is_reload) {
                        location.reload();
                    } else {
                        $('#' + data.view).empty();
                        $('#' + data.view).html('<img style=" visibility: hidden;" style="max-height: 140px; height:100%;" src="<?php echo base_url(get_upload_base_path() . get_upload_default_folder() . 'no.png'); ?>"/>');
                        $('#' + data.origin).val('');
                        $('#file_up_btn').show();
                        $('#del_pic_btn').hide();
                    }
                }
            }
        });
    }
</script>

</body>
</html>