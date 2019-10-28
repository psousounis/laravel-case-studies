@extends('layouts.app')
@section('content')
  {{--{{ dd($data['cases'][0]) }}--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if ($data['user_cases_count'] > 1 )
                <div>
                    <a href="{{ url('/') }}" class="btn btn-default btn-lg">
                       <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> {!!trans('messages.return_cases')!!}</a>
                <div>
                @endif
                    @if ($data['case'])
                    <h3 class="cases-main-header">
                    <div
                            class="list-title pull-left alert alert-info col-sm-12" >{{ $data['case']->case_descr }}{{' / '}} {{ trans('messages.stations_list')}}
                    </div>
                    </h3>
                    @endif

                <div class="col-sm-12">
                    <!-- <label>{!!trans('messages.user_quick_information')!!}</label> -->
                    <h4>
                    <label>{!!trans('messages.total_stations')!!}</label>
                    @foreach ( $data['user_total_stations'] as $row)
                        <label class="total-rows">{{ $row->total_rows }}</label>
                        <span class='start parenthesis'>(</span>

                            @foreach($data['user_stations_analysis'] as $key=>$value)

                            <!-- {{$row->total_stations}}-->
                                @if ($key !=0) {{'/'}} @endif
                                <label class='user case-region'>{{ $value->case_region_name}}{{': '}}{{$value->total_stations}} ({{$value->station_id_from}}-{{$value->station_id_to}})</label>
                                <!--foreach ( $data['user_stations_analysis'] as $row) -->
                         <!--   <label class='user case-region'>{{ $row->case_region_name}}({{$row->station_id_from}}-{{$row->station_id_to}})</label>  -->
                          <!--  }   -->
                        @endforeach
                        <span class='end parenthesis'>)</span>
                    @endforeach
                    </h4>
                </div>

                <!--<div class="clr"></div>         -->
                {{--<div class="panel-group panel-group-trns">--}}
                    {{--<div class="panel panel-default panel-trns">--}}
                        {{--<div id="collapseSearch" class="panel-collapse collapse in">--}}
                            <div id="toolbar-search" class="pull-right">
                                <div class="form" role="form">
                                <div class='cases-buttons-block'>
                                        @foreach ( $data['stations_not_registered'] as $station)
                                            @if ($station->status_id== config('app.STATION_STATUS_UNREGISTERED') )
                                            <div class="col-sm-2 card p-3 mb-2 text-white btn bg-primary hovered"
                                                 id="label_not_registered">
                                                <div class="card-body">
                                                    <span class="card-title">{!!trans('messages.stations_not_registered')!!} ({{ $station->total_rows }})</span>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach

                                    @foreach ( $data['stations'] as $station)
                                        @if ( ($station->status_id== config('app.STATION_STATUS_PROBLEMATIC')) )
                                                    <div class="col-sm-2 card text-white btn bg-danger hovered"
                                                         id="label_problematic">
                                                        <div class="card-body ">
                                                            <span class="card-title">{!!trans('messages.stations_problematic')!!} ({{ $station->total_rows }})</span>
                                                        </div>
                                                    </div>
                                        @elseif ($station->status_id== config('app.STATION_STATUS_REGISTERED') )
                                                    <div class="col-sm-2 card text-white btn bg-warning hovered"
                                                         id="label_registered">
                                                        <div class="">
                                                            <div class="card-body">
                                                                <span class="card-title">{!!trans('messages.stations_register')!!} ({{ $station->total_rows }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                        @elseif ($station->status_id== config('app.STATION_STATUS_SENT') )
                                                    <div class="col-sm-2 card text-white btn bg-success hovered"
                                                         id="label_sent">
                                                        <div class="">
                                                            <div class="card-body">
                                                                <span class="card-title">{!!trans('messages.stations_sent')!!} ({{ $station->total_rows }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                        @elseif ($station->status_id== config('app.STATION_STATUS_EDITED_FOR_RESEND') )
                                                    <div class="col-sm-2 card text-info btn bg-info hovered"
                                                         id="label_resend">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <span class="card-title">{!!trans('messages.stations_resent')!!} ({{ $station->total_rows }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                        @elseif ($station->status_id== config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC'))
                                                    <div class="col-sm-2 card text-danger btn bg-danger hovered"
                                                         id="label_sent_but_problematic">
                                                        <div class="">
                                                            <div class="card-body">
                                                                <span class="card-title">{!!trans('messages.stations_sent_but_problematic')!!} ({{ $station->total_rows }})</span>
                                                                <!-- <a  name="label_sent_but_problematic" id="label_sent_but_problematic" href="javascript:void(0);">Προβολή</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                        @endif
                                    @endforeach
                                    </div>
                                    <div class='search-box'>
                                        <div class="col-sm-4">
                                        <!--  <label>{!!trans('messages.select_case_regions')!!}</label> -->
                                            <select class="form-control drop-select" name="search_case_region"
                                                    id="search_case_region">
                                                <option value="">-- {!!trans('messages.select_case_region')!!} -- </option>
                                                @foreach ( $data['case_regions'] as $case_region)
                                                    <option value="{{ $case_region->case_region_id }}">{{ $case_region->case_region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                        <!-- <label>{!!trans('messages.select_municipality')!!}</label> -->
                                            <select class="form-control drop-select2" name="search_municipality"
                                                    id="search_municipality">
                                                <option value="">-- {!!trans('messages.select_municipality')!!}--</option>
                                                @foreach ( $data['municipalities'] as $municipality)
                                                    <option value="{{ $municipality->municipality_id }}">{{ $municipality->municipality_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <!-- <label>{!!trans('messages.select_status')!!}</label>  -->
                                            <select class="form-control drop-select" name="search_status" id="search_status">
                                                <option value="">-- {!!trans('messages.select_status')!!} --</option>
                                                @foreach ( $data['stations_statuses'] as $status)
                                                    <option value="{{ $status->status_id }}">{{ $status->status_descr }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                        <!--  <label>{!!trans('messages.select_station')!!}</label>     -->
                                        <input type="number" name="search_station"  class="form-control"
                                            maxlength="5" size="5"  placeholder="{!!trans('messages.placeholder_station')!!}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4 search-buttons-box">
                                        <button id="btn-search" type="submit"
                                                class="btn btn-success">{!!trans('messages.search')!!}</button>
                                        <button id="btn-clear-search" type="submit"
                                                class="btn btn-danger">{!!trans('messages.clear')!!}</button>
                                    </div>

                                </div>
                                </div>
                            </div>

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}


                <table id="table"
                       class="table table-condensed table-striped stations-table"
                       data-toolbar="#toolbar"
                       data-show-refresh="false"
                       data-show-toggle="false"
                       data-show-columns="false"
                       data-show-export="true"
                       data-detail-formatter="detailFormatter"
                       data-minimum-count-columns="1"
                       data-pagination="true"
                       data-id-field="id"
                       data-page-list="[25, 50, 100, ALL]"
                       data-show-footer="false"
                       data-side-pagination="server"
                       data-url="{{ url('/') }}/{{$data['case_id']}}/stations/data"
                       data-query-params="queryParams"
                       data-search-on-enter-key="true"
                       data-striped ="true"
                       data-cookie="true"
                       data-cookie-id-table="station-id"
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
        var options= "";



        function initTable() {
            $table.bootstrapTable({
                height: getHeight(),
                pageSize: 25,
                columns: [
                    {
                        field: 'status_id',
                        title: '{!!trans('messages.operation')!!}',
                        sortable: true,
                        events: operateEvents,
                        formatter: statusFormatter,
                        align: 'center'
                    },
                    {
                        field: 'action_id',
                        title: '{!!trans('messages.action')!!}',
                        visible: false
                    },
                    {
                        field: 'case_region_id',
                        title: '{!!trans('messages.case_region_id')!!}',
                        visible: false
                    },
                    {
                        field: 'case_region_name',
                        title: '{!!trans('messages.title_case_region')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'municipality_name',
                        title: '{!!trans('messages.title_municipality')!!}',
                        sortable: true,
                        align: 'center',
                    },
                    {
                        field: 'id',
                        title: '{!!trans('messages.title_station')!!}',
                        align: 'center',
                        valign: 'middle',
                        sortable: true
                    },
                    {
                        field: 'status_descr',
                        title: '{!!trans('messages.title_status_descr')!!}',
                        sortable: true,
                        align: 'center'
                    },
                     @if(Auth::user()->group_id == config('app.USER_GROUP_ID_ADMIN') or Auth::user()->group_id == config('app.USER_GROUP_ID_SUPERVISOR') )
                    {
                        field: 'user_name',
                        title: '{!!trans('messages.title_user')!!}',
                        align: 'center'
                    },
                     @endif

                ]
            });
            setTimeout(function () {
                $table.bootstrapTable('resetView');
            }, 200);

            $table.on('check.bs.table uncheck.bs.table ' +
                    'check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                selections = getIdScases();
            });
            $table.on('all.bs.table', function (e, name, args) {
                if (name == 'load-success.bs.table') {
                }
            });
            $remove.click(function () {
                var ids = getIdScases();
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


        function statusFormatter(value, row, index) {
          // console.log(row.status_id);
          // console.log(row.case_region_id);
            if  ( (value== {{$data['station_status_registered']}} ) || (value== {{$data['station_status_edited_for_resend']}} ) ) {
                @if (Auth::user()->group_id == 1 )
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            '<span class="operate-seperator">|</span>',
                            '<a class="send" href="javascript:void(0)" title="{!!trans('messages.send')!!}">',
                            '<i class="glyphicon glyphicon-send"></i>',
                            '</a> ',
                            ].join('');
                @else
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            ].join('');
                @endif

            } else if ( (value == {{$data['station_status_problematic']}}) || (value=={{$data['station_status_sent_but_problematic']}})) {
                return [
                    '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                    '<i class="glyphicon glyphicon-edit"></i>',
                    '</a> ',
                ].join('');
            } else if ( value == <?php echo config('app.STATION_STATUS_UNREGISTERED'); ?> ) {
                return [
                    '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.add')!!}">',
                    '<i class="glyphicon glyphicon-plus"></i>',
                    '</a> ',
                ].join('');
            } else {
                return [ ].join('');
            };

        }

        function statusFormatterGood(value, row, index) {
            if ( value>0 ) {
                if   ( (value == {{$data['station_status_problematic']}}) || (value == 4) )  {
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            '<span class="operate-seperator">|</span>',
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.send')!!}">',
                            '<i class="glyphicon glyphicon-send"></i>',
                            '</a> ',
                            ].join('');
                } else {
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            ].join('');
                }
            } else if ( value =={{$data['station_status_unregistered']}} ) {
                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.add')!!}">',
                '<i class="glyphicon glyphicon-plus"></i>',
                '</a> ',
                ].join('');
            } else {
                return [ ].join('');
            };

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
            'click .create': function (e, value, row, index) {
                window.location.href = "{{ url('/') }}/{{$data['case_id']}}/stations/"+ row.case_region_id +'/' + row.id + '/detail?action_id=1';
            },
            'click .edit': function (e, value, row, index) {
                window.location.href = "{{ url('/') }}/{{$data['case_id']}}/stations/"+ row.case_region_id +'/' + row.id + '/detail?action_id='+ row.action_id;
            },
            'click .send': function (e, value, row, index) {
                window.location.href = "{{ url('/') }}/{{$data['case_id']}}/stations/"+ row.case_region_id +'/' + row.id +  + row.id + '/send';
            },
            'click .remove': function (e, value, row, index) {
                var r = confirm("{!!trans('messages.confirm_delete_station')!!}");
                if (r == true) {
                    $.ajax({
                        url: '{{ url('/') }}/stations/destroy',
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

            if ($('select[name=search_case_region]').val() >0) {
                params['search_case_region'] = $('select[name=search_case_region]').val();
            }
            if ($('input[name=search_station]').val() >0) {
                params['search_station'] = $('input[name=search_station]').val();
            }
            if ($('select[name=search_municipality]').val() >0) {
                params['search_municipality'] = $('select[name=search_municipality]').val();
            }
            //parseInt
            if ( parseInt($('select[name=search_status]').val())>0 || parseInt($('select[name=search_status]').val()) ==-1) {
                 params['search_status'] = $('select[name=search_status]').val();
            }

            return params;
        }


        function savePreferences() {

            var settingsEvents = {};

            settingsEvents.search_case_region = $('select[name=search_case_region]').val();
            settingsEvents.search_municipality = $('select[name=search_municipality]').val();
            settingsEvents.search_status = $('select[name=search_status]').val();
            if ($('input[name=search_station]').val()>0) {
                settingsEvents.search_station = $('input[name=search_station]').val();
            }
            localStorage.setItem('settingsEvents', JSON.stringify(settingsEvents));
        }

        function getPreferences() {

            var settingsEvents = JSON.parse(localStorage.getItem('settingsEvents'));
            if (settingsEvents) {
                if (settingsEvents.search_case_region)
                    $('select[name=search_case_region]').val(settingsEvents.search_case_region);
                if (settingsEvents.search_municipality)
                    $('select[name=search_municipality]').val(settingsEvents.search_municipality);
                if (settingsEvents.search_status)
                    $('select[name=search_status]').val(settingsEvents.search_status);
                if (settingsEvents.search_station>0) {
                    $('input[name=search_station]').val(settingsEvents.search_station);
                }

            }
        }
        $('#search_municipality, #search_case_region, #search_status').change(function(){
            refreshBootstrapTable();
        });

        $('#btn-clear-search').click(function () {
            $('select[name=search_case_region]').val('');
            $('input[name=search_station]').val('');
            $('select[name=search_municipality]').val('');
            $('select[name=search_status]').val('');
            //$table.bootstrapTable('refresh');
            refreshBootstrapTable();
        });

        function getHeight() {
            return $(window).height() - $('h1').outerHeight(true);
        }
        function ClearForm() {
            $('select[name=search_case_region]').val('');
            $('select[name=search_municipality]').val('').trigger('change');
            $('select[name=search_status]').val('');
            $('input[name=search_station]').val('');
            return true;
        }

        function refreshBootstrapTable() {
            document.cookie = 'station-id.bs.table.pageNumber=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            savePreferences();
            $table.bootstrapTable('selectPage', 1);
            $table.bootstrapTable('refresh');
        }

        $('#label_not_registered').click (function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_UNREGISTERED')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $('#label_registered').click ( function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_REGISTERED')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $('#label_problematic').click( function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_PROBLEMATIC')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $('#label_sent_problematic').click( function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $('#label_sent').click (function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_SENT')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $('#label_resend').click (function() {
            ClearForm();
            $('select[name=search_status]').val({{config('app.STATION_STATUS_EDITED_FOR_RESEND')}});
            refreshBootstrapTable();

            //If you don't want the link to actually
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't
            // always be the case.
            return false;
        });

        $(function () {
            // pansou vars
            var labelNotRegistered = document.getElementById("label_not_registered");
            var labelRegistered = document.getElementById("label_register");
            var labelProblematic = document.getElementById("label_problematic");
            var labelSent = document.getElementById("label_sent");
            var labelResend = document.getElementById("label_resend");

            getPreferences();
            initTable();
            $('.drop-select2').select2();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection