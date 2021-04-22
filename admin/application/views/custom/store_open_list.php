<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="ac_datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="15%">功能</th>
                                        <th>營業時間</th>
                                        <th>營業時段數</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal -->

<div class="modal fade" id="info_modal" role="dialog" aria-labelledby="create_modal_label" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-s">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="info_label"></h4>
            </div>
            <div class="modal-body">
                <form id="info_form">

                    <input type="hidden" name="store_sid" value="<?= $store_sid ?>" />
                    <input type="hidden" name="sid" value="" />
                    <input type="hidden" name="opening_date_sid" value="" />
                    <input type="hidden" name="week_num" value="" />
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>星期</label>
                                    <select class="form-control" id="select_week_num" multiple>
                                        <?php foreach ($select_array as $key => $name) : ?>
                                            <option value="<?= $key ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="data_submit()"> 確認</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="del_modal" role="dialog" aria-labelledby="create_modal_label" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-s">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="del_label">刪除</h4>
            </div>
            <div class="modal-body">
                <form id="del_form">

                    <input type="hidden" name="sid" class="form-control" value="" />
                    確認刪除設定時段？
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="del_data()"> 確認</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="open_time_modal" role="dialog" aria-labelledby="create_modal_label" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="open_time_label">商品規格</h4>
            </div>
            <div class="modal-body">
                <form id="open_time_form">

                    <input type="hidden" name="relation_sid" value="" />
                    <input type="hidden" name="sid" value="" />
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">

                                    <label>開始時間</label>

                                    <input type="time" min="00:00" max="23:59" name="start_time" class="form-control" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">

                                    <label>結束時間</label>

                                    <input type="time" min="00:00" max="23:59" name="end_time" class="form-control" />
                                </div>
                            </div>

                            <div class="col-3">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;"><a class="btn btn-sm btn-info" onclick="merage_time_info();">確定</a></span>
                                    <span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px; visibility:hidden" tag="cancle_select"><a class="btn btn-sm btn-danger " data-toggle="modal" onclick="clear_data();">取消</a></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" tag="open_time_table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="20%">功能</th>
                                <th>開始時間</th>
                                <th>結束時間</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    function clear_data() {

        $('#open_time_form input[name="sid"]').val('');
        $('#open_time_form input[name="start_time"]').val('');
        $('#open_time_form input[name="end_time"]').val('');

        $('span[tag="cancle_select"]').css('visibility', 'hidden');;

    }

    function show_time_info(open_time_sid, start_time, end_time) {

        $('span[tag="cancle_select"]').css('visibility', 'visible');;

        $('#open_time_form input[name="sid"]').val(open_time_sid);
        $('#open_time_form input[name="start_time"]').val(start_time);
        $('#open_time_form input[name="end_time"]').val(end_time);

    }

    function merage_time_info() {

        let start_time = $('#open_time_form input[name="start_time"]').val();
        let end_time = $('#open_time_form input[name="end_time"]').val();

        let msg = "";

        if (start_time == "") {
            msg = "請輸入開始時間!\n";
        }

        if (end_time == "") {
            msg += "請輸入結束時間!\n";
        }

        if (msg == "") {
            let start_time_val =new Date(moment().format("YYYY-MM-DD")+' '+start_time).getTime();
            let end_time_val =new Date(moment().format("YYYY-MM-DD")+' '+end_time).getTime();

            if (start_time_val > end_time_val) {
                msg += "開始時間大於結束時間!";
            }
        }


        if (msg != "") {
            alert(msg);
        } else {

            $.ajax({
                url: "<?= base_url('ajax/ajax_store_opening/merge_open_time') ?>",
                type: "POST",
                dataType: "json",
                data: $("#open_time_form").serialize(),
                success: function(result) {

                    alert(result.message);
                    data_table.ajax.reload();
                    ac_xxx_datatable.draw();
                    clear_data();

                },
                error: function(error_contant) {}
            });

        }
    }

    function del_time_info(time_sid) {

        $.ajax({
            url: "<?= base_url('ajax/ajax_store_opening/del_open_time') ?>",
            type: "POST",
            dataType: "json",
            data: {
                "time_sid": time_sid
            },
            success: function(result) {

                alert(result.message);
                data_table.ajax.reload();
                ac_xxx_datatable.draw();

            },
            error: function(error_contant) {}
        });

    }
</script>
<script>
    let sel_week;

    init_sel_week();

    function init_sel_week() {
        sel_week = $('#select_week_num').smartselect({
            toolbar: false,
            text: {
                selectLabel: '---請選擇星期---'
            },
            template: {
                select: '<button class="form-control" type="button" style="background-color: #ffffff;"><i class="ss-icon"></i><span class="ss-label"></span><i class="ss-caret"></i></button>',
            },
            callback: {
                onOptionChanged: function(e) {

                    $("input[name=week_num]").val(sel_week.getSelectedPairs().value);

                    return true;
                }
            }
        }).getsmartselect();
    }

    function get_week_array(week_num) {
        $.ajax({
            url: "<?= base_url('custom/store_manage/ajax_get_week_num') ?>",
            type: "POST",
            dataType: "json",
            data: {
                'extra_week_num': week_num
            },
            success: function(result) {

                let week_array = '<?= implode(',', array_keys(WEEK_ARRAY)) ?>'.split(',');

                $.each(week_array, function(index, week_num) {
                    sel_week.removeOption(week_num);
                });

                $.each(result, function(index, value) {
                    sel_week.addOption({
                        label: value,
                        value: index
                    });
                });

                if (week_num != '') {
                    sel_week.selectOptions((week_num + "").split(','), false);
                } else {
                    sel_week.selectOptions([]);
                }

            },
            error: function(error_contant) {}
        });

    }

    $('#info_modal').on('hidden.bs.modal', function() {
        sel_week.closeDropdown();
        $('#info_form input[name="opening_date_sid"]').val("");
        $('#info_form input[name="week_num"]').val("");
        sel_week.selectOptions([]);
    })

    function show_info(relation_sid, week_num, opening_date_sid) {

        $('#info_form input[name="sid"]').val(relation_sid);
        $('#info_form input[name="opening_date_sid"]').val(opening_date_sid);
        $('#info_form input[name="week_num"]').val(week_num);

        get_week_array(week_num);

        if (relation_sid == "") {
            $("#info_label").html("新增")
        } else {
            $("#info_label").html("修改")
        }

        $('#info_modal').modal('show');

    }

    function data_submit() {

        var week_num = $('#info_form input[name="week_num"]').val();
        var sid = $('#info_form input[name="sid"]').val();

        if (week_num == '' && sid == '') {
            alert("請選擇星期!");
        } else {

            $.ajax({
                url: "<?= base_url('ajax/ajax_store_opening/merge_open_date_info') ?>",
                type: "POST",
                dataType: "json",
                data: $("#info_form").serialize(),
                success: function(result) {

                    alert(result.message);
                    $('#info_modal').modal('hide');
                    ac_xxx_datatable.draw();

                },
                error: function(error_contant) {}
            });

        }
    }

    function del_check(sid) {

        $('#del_form input[name="sid"]').val(sid);
        $('#del_modal').modal('show');

    }

    function del_data() {

        $.ajax({
            url: "<?= base_url('ajax/ajax_store_opening/del_open_date_info') ?>",
            type: "POST",
            dataType: "json",
            data: $("#del_form").serialize(),
            success: function(result) {

                alert(result.message);
                $('#del_modal').modal('hide');
                ac_xxx_datatable.draw();

            },
            error: function(error_contant) {}
        });

    }
    let data_table;

    function show_open_time(relation_sid, name) {
        $('#pen_time_label').html(name + "營業時間");

        if (data_table != null) {
            data_table.clear();
            data_table.destroy();
        }
        $('#open_time_form input[name="relation_sid"]').val(relation_sid);

        data_table = $('table[tag="open_time_table"]').DataTable({
                searching: false,
                paging: false,
                ordering: false,
                serverSide: true,
                ajax: ({
                    url: "<?= base_url('ajax/ajax_store_opening/find_open_time_array') ?>",
                    type: "POST",
                    dataType: "json",
                    data: function(result) {
                        return $.extend({
                            "relation_sid": relation_sid
                        }, result, {

                        });
                    }
                }),
                columns: [{
                        "data": "sid",
                        "render": function(data, type, row) {
                            // console.log(row);
                            let view = '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="編輯"><a class="btn btn-sm btn-info"' +
                                'onclick="show_time_info(' + row.sid + ',\'' + row.startTime + '\',' + '\'' + row.endTime + '\'' + ');"><i class="fa fa-edit"></i></a></span>';

                            view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="刪除"><a class="btn btn-sm btn-danger "' +
                                ' data-toggle="modal" onclick="del_time_info(' + row.sid + ');"><i class="fa fa-trash"></i></a></span>'


                            return view;
                        }
                    },
                    {
                        "data": "startTime",

                    },
                    {
                        "data": "endTime"

                    }
                ]

            }

        );
        $("#open_time_modal").modal().show();


    }

    function edit_product(store_sid) {

        $('<form method="post" action="<?php echo base_url('custom/store_manage/product'); ?>"><input type="hidden" name="store_sid" class="form-control" value="' + store_sid + '" /></form>').appendTo('body').submit().remove();

    }
</script>

<script>
    // 呼叫ac_xxx_datatable
    var ac_xxx_datatable;
    $(document).ready(function() {
        ac_xxx_datatable = new ac_xxx_datatable('<?= base_url('custom/store_manage/ajax_opening_data') ?>', {
            "columns": [

                {
                    "data": "sid",
                    "name": "sid",
                    "orderable": false,
                    "render": function(data, type, row) {
                        // console.log(row);
                        let view = '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="編輯"><a class="btn btn-sm btn-info"' +
                            'onclick="show_info(' + row.sid + ',\'' + row.weekNum + '\',\'' + row.openingDateSid + '\');"><i class="fa fa-edit"></i></a></span>';

                        view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="刪除"><a class="btn btn-sm btn-danger "' +
                            ' data-toggle="modal" onclick="del_check(' + row.sid + ');"><i class="fa fa-trash"></i></a></span>'

                        view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="編輯時段"><a class="btn btn-sm btn-primary "' +
                            '  onclick="show_open_time(' + row.sid + ',\'' + row.weekName + '\');"><i class="fa fa-calendar-plus-o"></i>&nbsp;&nbsp;編輯時段</a></span>'

                        return view;
                    }
                },
                {
                    "data": "weekName",
                    "name": "weekName",
                    "orderable": false,
                    "render": null,
                },
                {
                    "data": "data_count",
                    "name": "data_count",
                    "orderable": false,
                    "render": null,
                }
            ],
            "stateSave": false
        });

        ac_xxx_datatable.draw(); // 都準備好之後載入資料

        //單選多選時要檢查button的狀態
        ac_xxx_datatable.on('select', function(e, dt, type, indexes) {
            change_edit_del_button();
        }).on('deselect', function(e, dt, type, indexes) {
            change_edit_del_button();
        });
    });

    function change_edit_del_button(disabled) {
        if (disabled === undefined) {
            let selected_data = ac_xxx_datatable.rows({
                selected: true
            }).data();
            $('[ac_action="edit_data"]').prop('disabled', !(selected_data.length == 1));
            $('[ac_action="del_data"]').prop('disabled', !(selected_data.length > 0));
        } else {
            $('[ac_action="edit_data"]').prop('disabled', disabled);
            $('[ac_action="del_data"]').prop('disabled', disabled);
        }
    }

    function ac_clean_datatable_search(selector) {
        ac_xxx_datatable.ac_datatable_clean_search(selector);
        change_edit_del_button(true);
    }

    $('[ac_action="clean_date"]').click(function() {
        ac_xxx_datatable.draw(); // 都準備好之後載入資料
    });
</script>

<script>
    ac_xxx_datatable = function(ac_ajax_url, ac_datatable_param, ac_datatable_selector, ac_datatable_filter_form, ac_datatable_colAttr, ac_post_key, ac_action_modify_sid, ac_datatable_other, ac_other_post) {
        ac_ajax_url = ac_ajax_url || '';
        ac_datatable_selector = ac_datatable_selector || '#ac_datatable'; //table要掛在哪個table上

        ac_datatable_filter_form = ac_datatable_filter_form || '#ac_search_form'; //篩選器的form tag
        ac_datatable_colAttr = ac_datatable_colAttr || 'ac_datatable_colIdx'; //篩選器的input tag 請加上這個更屬性(select也支援)

        ac_post_key = ac_post_key || 'ac_post_key'; //對應post_change時，要修改的資料欄位名稱
        ac_action_modify_sid = ac_action_modify_sid || 'ac_action_modify_sid'; //對應post_change時，要修改資料行的sid

        ac_datatable_other = ac_datatable_other || 'ac_datatable_other'; //要顯示其他欄位請加上這個attr (會去抓ajax抓到的資料對應的物件屬性)

        ac_other_post = ac_other_post || 'ac_other_post'; //送出ajax以前可以加上額外的data在post key value裡面 (如果該dom物件有name屬性，優先變成post key不然就使用ac_other_post屬性的value為post key)

        let ac_datatable_core = {};
        ac_datatable_core.ac_datatable_refresh = function() {
            setTimeout(function() {
                // ac_datatable_core.draw();
                ac_datatable_core.ajax.reload(null, false);
            }, 1);
        }

        ac_datatable_core.ac_datatable_clean_search = function(form_selector) {
            form_selector = form_selector || ac_datatable_filter_form;
            if ($(form_selector).length > 0) {
                $(form_selector)[0].reset();
                if (typeof ac_datatable_core.columns === 'function') {
                    ac_datatable_core.columns().every(function(columnsIdx) {
                        let each_search = $('[' + ac_datatable_colAttr + '="' + columnsIdx + '"]').val();
                        ac_datatable_core.column(columnsIdx).search(each_search);
                    });
                    ac_datatable_core.search('');
                    ac_datatable_core.ac_datatable_refresh();
                }
            }
        }

        ac_datatable_core.ac_datatable_export_help = function(e, dt, button, config, ac_datatable_api, extend, interrupt, resume) {
            let tmp_page_len = dt.settings().page.len();
            interrupt = interrupt || function() {
                dt.settings().page.len(0);
            };
            resume = resume || function() {
                dt.settings().page.len(tmp_page_len);
            };
            interrupt();
            dt.ajax.reload(function(return_data) {
                switch (extend) {
                    case 'excelHtml5':
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(ac_datatable_api, e, dt, button, config);
                        break;
                    case 'csvHtml5':
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(ac_datatable_api, e, dt, button, config);
                        break;
                    case 'pdfHtml5':
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(ac_datatable_api, e, dt, button, config);
                        break;
                    case 'print':
                        $.fn.dataTable.ext.buttons.print.action.call(ac_datatable_api, e, dt, button, config);
                        break;
                }
                resume();
                ac_datatable_core.ac_datatable_refresh();
            });
        }

        ac_datatable_core.post_change = function() {
            let post_sid = $(this).attr(ac_action_modify_sid);
            let post_key = $(this).attr(ac_post_key);
            let post_data = {
                sid: $(this).attr(ac_action_modify_sid)
            };
            post_data[post_key] = $(this).val();
            if (post_sid) {
                $.ajax({
                    type: "POST",
                    url: '',
                    data: post_data,
                    dataType: 'json',
                    success: function(data) {
                        ac_datatable_core.ac_datatable_refresh();
                    }
                });
            }
        }

        ac_datatable_param = ac_datatable_param || {};
        ac_datatable_param_default = {
            dom: 'l<"float-right"fB>rtip', //l = 一頁顯示幾筆，f = 搜尋的輸入框
            buttons: [{
                    text: '返回',
                    action: function(event, api, dom, obj) {
                        javascript: history.go(-1);
                    }
                },
                {
                    text: '新增時段',
                    action: function(event, api, dom, obj) {
                        show_info('', '', '');
                    }
                }

            ],
            searching: false,
            paging: false, //分頁
            ordering: false, //排序
            lengthChange: true,
            deferRender: true,
            scroller: false,
            scrollY: '30em', //卷軸長度，scroller若不為false時，這個一定要設定不然會報錯
            "deferLoading": 0,
            select: null,
            "processing": true, //是否顯示Loading畫面
            "serverSide": true, //是否改用後端搜尋與運算
            rowReorder: false, //拖曳排序，啟動後第一欄被用來當拖曳的感應點，不可被選擇 (serverSide為true時，每拖曳一次會自動重新整理當下頁面)
            searchDelay: 100, //搜尋的延遲時間
            "columns": [],
            "ajax": {
                url: ac_ajax_url,
                type: 'POST',
                data: function(d, settings) {
                    $('[' + ac_other_post + ']').each(function(idx, dom_obj) {
                        if (dom_obj.hasAttribute("name")) {
                            d[$(dom_obj).attr('name')] = $(dom_obj).val();
                        } else {
                            d[$(dom_obj).attr(ac_other_post)] = $(dom_obj).val();
                        }
                    });
                    return d;
                }
            },
            stateSave: true, //是否儲存datatable的狀態
            initComplete: function(settings, json) {
                let ac_datatable_this = this.api();
                settings.aoColumns.forEach(function(colObject, colIdx) {
                    $('[' + ac_datatable_colAttr + '="' + colIdx + '"]').on('change', function() {
                        ac_datatable_this.column(colIdx).search(this.value).draw();
                    });
                });
                let state_data = ac_datatable_this.state();
                if (state_data == null) {
                    ac_datatable_core.ac_datatable_clean_search(ac_datatable_filter_form);
                } else {
                    //若有儲存狀態，載入儲存狀態後要寫回篩選器
                    state_data.columns.forEach(function(columnsObject, columnsIdx) {
                        $('[' + ac_datatable_colAttr + '="' + columnsIdx + '"]').val(columnsObject.search.search);
                    });
                }
                $(ac_datatable_selector).on('change', 'input,select', ac_datatable_core.post_change);
            },
            drawCallback: function(settings, json) {
                let ac_datatable_api = this.api();
                //抓ajax抓到的資料對應的物件屬性
                let ajax_json = ac_datatable_api.ajax.json();
                if (ajax_json) {
                    $('[' + ac_datatable_other + ']').each(function() {
                        let re_obj_key = $(this).attr(ac_datatable_other);
                        if (ajax_json.hasOwnProperty(re_obj_key)) {
                            $(this).text(ajax_json[re_obj_key]);
                        }
                    });
                }
            }
        }
        $.extend(true, ac_datatable_param_default, ac_datatable_param);
        $.extend(true, ac_datatable_core, $(ac_datatable_selector).DataTable(ac_datatable_param_default));
        return ac_datatable_core;
    };
</script>