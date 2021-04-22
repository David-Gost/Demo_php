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
                                        <th width="20%">功能</th>
                                        <th>店家</th>
                                        <th>餘額</th>
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

<div class="modal fade" id="store_info_modal" role="dialog" aria-labelledby="create_modal_label" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-s">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="store_info_label"></h4>
            </div>
            <div class="modal-body">
                <form id="store_info_form">

                    <input type="hidden" name="sid" class="form-control" value="" />
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>店家名稱</label>

                                    <input type="text" name="store_name" class="form-control" value="" />

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

<div class="modal fade" id="del_store_modal" role="dialog" aria-labelledby="create_modal_label" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-s">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="del_store_label">刪除店家</h4>
            </div>
            <div class="modal-body">
                <form id="store_del_form">

                    <input type="hidden" name="sid" class="form-control" value="" />
                    刪除店家會一併刪除所設定產品資料、營業時間，是否繼續？
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="del_store()"> 確認</button>
            </div>
        </div>
    </div>
</div>

<script>
    function show_stoer_info(store_sid, store_name) {

        $('#store_info_form input[name="sid"]').val(store_sid);
        $('#store_info_form input[name="store_name"]').val(store_name);

        if (store_sid == "") {
            $("#store_info_label").html("新增店家")
        } else {
            $("#store_info_label").html("修改店家")
        }

        $('#store_info_modal').modal('show');

    }

    function data_submit() {

        var store_name = $('#store_info_form input[name="store_name"]').val();

        if (store_name == '') {
            alert("店家名稱請勿空白!");
        } else {

            $.ajax({
                url: "<?= base_url('ajax/ajax_store/merge_store_info') ?>",
                type: "POST",
                dataType: "json",
                data: $("#store_info_form").serialize(),
                success: function(result) {

                    alert(result.message);
                    $('#store_info_modal').modal('hide');
                    ac_xxx_datatable.draw();

                },
                error: function(error_contant) {}
            });

        }
    }

    function del_check(store_sid) {

        $('#store_del_form input[name="sid"]').val(store_sid);
        $('#del_store_modal').modal('show');

    }

    function del_store() {

        $.ajax({
            url: "<?= base_url('ajax/ajax_store/del_store') ?>",
            type: "POST",
            dataType: "json",
            data: $("#store_del_form").serialize(),
            success: function(result) {

                alert(result.message);
                $('#del_store_modal').modal('hide');
                ac_xxx_datatable.draw();

            },
            error: function(error_contant) {}
        });

    }

    function edit_product(store_sid) {

        $('<form method="post" action="<?php echo base_url('custom/store_manage/product'); ?>"><input type="hidden" name="store_sid" class="form-control" value="' + store_sid + '" /></form>').appendTo('body').submit().remove();

    }

    function edit_opening(store_sid) {

        $('<form method="post" action="<?php echo base_url('custom/store_manage/opening'); ?>"><input type="hidden" name="store_sid" class="form-control" value="' + store_sid + '" /></form>').appendTo('body').submit().remove();

    }
</script>

<script>
    // 呼叫ac_xxx_datatable
    var ac_xxx_datatable;
    $(document).ready(function() {
        ac_xxx_datatable = new ac_xxx_datatable('<?= base_url('custom/store_manage/ajax_store_data') ?>', {
            "columns": [

                {
                    "data": "storeSid",
                    "name": "storeSid",
                    "orderable": false,
                    "render": function(data, type, row) {
                        // console.log(row);
                        let view = '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="編輯"><a class="btn btn-sm btn-info"' +
                            'onclick="show_stoer_info(' + row.storeSid + ',\'' + row.pharmacyName + '\');"><i class="fa fa-edit"></i></a></span>';

                        view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="刪除"><a class="btn btn-sm btn-danger "' +
                            ' data-toggle="modal" onclick="del_check(' + row.storeSid + ');"><i class="fa fa-trash"></i></a></span>'

                        view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="編輯商品"><a class="btn btn-sm btn-primary "' +
                            '  onclick="edit_product(' + row.storeSid + ');"><i class="fa fa-delicious"></i>&nbsp;&nbsp;編輯商品</a></span>'

                        view += '<span data-toggle="tooltip" data-placement="right" title="" style="margin-right:5px;" data-original-title="營業時間"><a class="btn btn-sm btn-secondary "' +
                            '  onclick="edit_opening(' + row.storeSid + ');"><i class="fa fa-calendar-plus-o"></i>&nbsp;&nbsp;營業時間</a></span>'

                        return view;
                    }
                },
                {
                    "data": "pharmacyName",
                    "name": "pharmacyName",
                    "orderable": false,
                    "render": null,
                },
                {
                    "data": "cashBalance",
                    "name": "cashBalance",
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
            buttons: [

                {
                    text: '新增店家',
                    action: function(event, api, dom, obj) {
                        show_stoer_info('', '');
                    }
                },

                {
                    text: '匯出excel',
                    extend: 'excelHtml5',
                    title: '',
                    filename: '店家資料',
                    'action': function(e, dt, button, config) {
                        ac_datatable_core.ac_datatable_export_help(e, dt, button, config, this, 'excelHtml5');
                    },
                    exportOptions: {
                        columns: [1, 2]
                    }

                }

            ],
            lengthChange: true,
            deferRender: true,
            scroller: false,
            pageLength: 100,
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