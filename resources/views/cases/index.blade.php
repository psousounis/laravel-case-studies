@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
               <!--  <h3 class="list-title">{{ trans('messages.cases_list') }} </h3>  -->
                <div class="alert alert-info">
                    <h3 class="list-title">{{ trans('messages.cases_list') }} </h3>  
                  <!--   {!! trans('messages.user_info') !!} -->
                </div>
                <div class="panel-group panel-group-trns">
                    <div class="panel panel-default panel-trns">

                        <div id="collapseSearch" class="panel-collapse collapse in">
                            <div id="toolbar-search" class="pull-right" style="width:100%">
                                <div class="form" role="form">
                                    <div class="col-sm-6">
                                        <select class="form-control drop-select2" name="search_case_type" id="search_case_type">
                                            <option value="">-- {!!trans('messages.select_case_type')!!} --</option>
                                            @foreach ( $data['cases_types'] as $case_type)
                                                <option value="{{ $case_type->case_type_id}}">{{ $case_type->case_type_descr }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                                                                                                                                                                
                                    <div class="clr"></div>
                                    <div style="height:10px;"></div>
                                    <button id="btn-search" type="submit"
                                            class="btn btn-success">{!!trans('messages.search')!!}</button>
                                    <button id="btn-clear-search" type="submit"
                                            class="btn btn-danger">{!!trans('messages.clear')!!}</button>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="clr"></div>

                <table id="table"
                       class="table table-condensed table-striped cases-table"
                       data-toolbar="#toolbar"
                       data-show-refresh="false"
                       data-show-toggle="false"
                       data-show-columns="true"
                       data-show-export="true"
                       data-detail-formatter="detailFormatter"
                       data-minimum-count-columns="1"
                       data-pagination="true"
                       data-id-field="id"
                       data-page-list="[25, 50, 100, ALL]"
                       data-show-footer="false"
                       data-side-pagination="server"
                       data-url="{{ url('/') }}/data"
                       data-query-params="queryParams"
                       data-cookie="true"
                       data-cookie-id-table="case-id"
                       data-response-handler="responseHandler">
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footerScript')
    <script>
        var $table = $('#table');
        var $remove = $('#remove');
        var selections = [];

        function initTable() {
            $table.bootstrapTable({
                height: getHeight(),
                pageSize: 25,
                columns: [
                    {
                        field: 'operate',
                        title: '{!!trans('messages.operation')!!}',
                        align: 'center',
                        events: operateEvents,
                        formatter: operateFormatter
                    },                    
                    {
                        field: 'id',
                        title: 'ID',
                        visible: true,
                        align: 'center',
                        valign: 'middle',
                        sortable: true
                    },
                    {
                        field: 'case_descr',
                        title: '{!!trans('messages.title_case_descr')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'case_type_descr',
                        title: '{!!trans('messages.title_case_type_descr')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'case_year',
                        title: '{!!trans('messages.title_case_year')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'case_date',
                        title: '{!!trans('messages.title_case_date')!!}',
                        sortable: true,
                        align: 'center'
                    },
                ]
            });
            setTimeout(function () {
                $table.bootstrapTable('resetView');
            }, 200);

            $table.on('check.bs.table uncheck.bs.table ' +
                    'check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                selections = getIdSelections();
            });
            $table.on('all.bs.table', function (e, name, args) {
                if (name == 'load-success.bs.table') {
                }
            });
            $remove.click(function () {
                var ids = getIdSelections();
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: ids
                });
                $remove.prop('disabled', true);
            });
            $(window).resize(function () {
                $table.bootstrapTable('resetView', {
                    height: getHeight()
                });
            });
            $('[data-toggle="tooltip"]').tooltip();
        }
        function getIdSelections() {
            return $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
        }
        function responseHandler(res) {
            $.each(res.rows, function (i, row) {
                row.state = $.inArray(row.id, selections) !== -1;
            });
            return res;
        }
        function operateFormatter(value, row, index) {
            @if (Auth::user()->group_id == 1 ) 
                return [
                    '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.tooltip_stations_view')!!}">',
                    '<i class="glyphicon glyphicon-edit"></i>',
                    '</a>',
                    '<span class="operate-seperator">|</span>',
                    '<a class="send" href="javascript:void(0)" title="{!!trans('messages.tooltip_send')!!}">',
                    '<i class="glyphicon glyphicon-send"></i>',
                    '</a> ',
                ].join('');
            @else 
                return [
                    '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.tooltip_stations_view')!!}">',
                    '<i class="glyphicon glyphicon-edit"></i>',
                ].join('');
            @endif
        }

        
        function detailFormatter(index, row) {
            return;
            var html = [];
            $.each(row, function (key, value) {
                html.push('<p><b>' + key + ':</b> ' + value + '</p>');
            });
            return html.join('');
        }

        window.operateEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '{{ url('/') }}\/' + row.id + '/stations';
            },
            'click .send': function (e, value, row, index) {
                window.location.href = '{{ url('/') }}\/' + row.id + '/send';
            },
            'click .remove': function (e, value, row, index) {
                var r = confirm("{!!trans('messages.confirm_delete_case')!!}");
                if (r == true) {
                    $.ajax({
                        url: '{{ url('/') }}'+ row.id + '/destroy',
                        type: 'POST',
                        data: {id: row.id},
                        success: function (result) {
                            $table.bootstrapTable('remove', {
                                field: 'id',
                                values: [row.id]
                            });
                        },
                        error: function (result) {

                        }
                    });
                }
            },

        };

        $('#btn-search').click(function () {
            $table.bootstrapTable('refresh');
        });

        function queryParams(params) {
            if ($('select[name=search_case_type]').val() >0) {
             params['search_case_type'] = $('select[name=search_case_type]').val();  
            } 
            return params;
        }
        $('#btn-clear-search').click(function () {
            $('input[name=search_case_type]').val('');
            $table.bootstrapTable('refresh');
        });

        function getHeight() {
            return $(window).height() - $('h1').outerHeight(true);
        }

        $(function () {

            // getPreferences();
            initTable();
           // $('.drop-select2').select2();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });



    </script>
@endsection

