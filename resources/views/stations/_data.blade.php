@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">

                <div>
                    @foreach ( $data['elections'] as $election)
                    <h3 class="list-title pull-left alert alert-info col-sm-8" >{{ $election->election_descr }} / {{ trans('messages.pollingstations_list')}}</h3>
                    @endforeach
                </div>
                <div class="col-sm-4">
                    <a href="{{ url('/') }}" class="btn btn-danger">{!!trans('messages.return')!!}</a>
                </div>
                <div class="col-sm-12">
                        <!-- <label>{!!trans('messages.user_quick_information')!!}</label> -->
                        <h4>
                        <label>{!!trans('messages.total_stations')!!}</label>
                        @foreach ( $data['user_total_stations'] as $row)
                            <label>{{ $row->total_rows }}</label>
                            <label><?php echo ' (';?></label>
                            @foreach ( $data['user_stations_analysis'] as $row)
                            <label>{{ $row->electoral_region_name}}<?php echo "(";?>{{$row->polling_station_id_from}}<?php echo "-";?>{{$row->polling_station_id_to}}<?php echo ") "?></label>
                            @endforeach
                        <label><?php echo ' )';?></label>
                        @endforeach
                        </h4>
                <!--<div class="clr"></div>         -->      
                <div class="panel-group panel-group-trns">
                    <div class="panel panel-default panel-trns">
                        <div id="collapseSearch" class="panel-collapse collapse in">
                            <div id="toolbar-search" class="pull-right" style="width:100%">
                                <div class="form" role="form">                                   
                                    <div class="col-sm-2">
                                        <label>{!!trans('messages.pollingstations_not_registered')!!}</label>
                                        @foreach ( $data['polling_stations_not_registered'] as $station)
                                        @if ($station->status_id== env('POLLING_STATION_STATUS_UNREGISTERED'))
                                            <a  name="label_not_registered" id="label_not_registered" href="javascript:void(0);">({{ $station->total_rows }})</a>
                                        @endif
                                        @endforeach
                                    </div>
                                    @foreach ( $data['polling_stations'] as $station)
                                        @if ( ($station->status_id== config('app.POLLING_STATION_STATUS_PROBLEMATIC')) )
                                            <div class="col-sm-2">
                                               <h4> 
                                                <label>{!!trans('messages.pollingstations_problematic')!!}</label>
                                                <a name="label_problematic" id="label_problematic"  href="javascript:void(0);">({{ $station->total_rows }})</a>
                                               </h4>                                                
                                            </div>
                                        @elseif ($station->status_id== config('app.POLLING_STATION_STATUS_REGISTERED') )
                                            <div class="col-sm-2">
                                                <h4>
                                                <label>{!!trans('messages.pollingstations_register')!!}</label>
                                                <a name="label_registered" id="label_registered"  href="javascript:void(0);">({{ $station->total_rows }})</a>
                                                </h4>
                                            </div> 
                                        @elseif ($station->status_id== config('app.POLLING_STATION_STATUS_SENT') )
                                            <div class="col-sm-2">
                                                <h4>
                                                <label>{!!trans('messages.pollingstations_sent')!!}</label>
                                                <a name="label_sent" id="label_sent"  href="javascript:void(0);">({{ $station->total_rows }})</a>
                                                </h4>
                                            </div>    
                                        @elseif ($station->status_id== config('app.POLLING_STATION_STATUS_EDITED_FOR_RESEND') )
                                            <div class="col-sm-2">
                                                <h4>
                                                <label>{!!trans('messages.pollingstations_resent')!!}</label>
                                                <a name="label_resend" id="label_resend"  href="javascript:void(0);">({{ $station->total_rows }})</a>
                                                </h4>
                                            </div>
                                        @elseif ($station->status_id== config('app.POLLING_STATION_STATUS_SENT_BUT_PROBLEMATIC'))
                                            <div class="col-sm-2">
                                                <h4>
                                                <label>{!!trans('messages.pollingstations_sent_but_problematic')!!}</label>
                                                <a name="label_sent_but_problematic" id="label_sent_but_problematic"  href="javascript:void(0);">({{ $station->total_rows }})</a>
                                                </h4>
                                            </div>    
                                        @endif
                                    @endforeach
                                    
                                    <div class="col-sm-4">                                        
                                           <!--  <label>{!!trans('messages.select_electoral_regions')!!}</label> -->
                                            <select class="form-control drop-select2" name="search_electoral_region" id="search_electoral_region">
                                                <option value="">-- {!!trans('messages.select_electoral_regions')!!} --</option>
                                                @foreach ( $data['electoral_regions'] as $electoral_region)
                                                    <option value="{{ $electoral_region->electoral_region_id }}">{{ $electoral_region->electoral_region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>    
                                    <div class="col-sm-4">
                                        <!-- <label>{!!trans('messages.select_municipality')!!}</label> -->
                                        <select class="form-control drop-select2" name="search_municipality" id="search_municipality">
                                            <option value="">-- {!!trans('messages.select_municipality')!!} --</option>
                                            @foreach ( $data['municipalities'] as $municipality)
                                               <option value="{{ $municipality->municipality_id }}">{{ $municipality->municipality_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                         <!-- <label>{!!trans('messages.select_status')!!}</label>  -->
                                        <select class="form-control drop-select2" name="search_status" id="search_status">
                                            <option value="">-- {!!trans('messages.select_status')!!} --</option>
                                            @foreach ( $data['pollingstations_statuses'] as $status)
                                               <option value="{{ $status->status_id }}">{{ $status->status_descr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                     <!-- <div class="col-sm-12">  -->
                                    <div class="col-sm-6">
                                       <!--  <label>{!!trans('messages.select_pollingstation')!!}</label>     -->
                                       <input type="number" name="search_station" class="form-control"
                                        maxlength="5"   placeholder="{!!trans('messages.placeholder_station')!!}">
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


                <table id="table"
                       class="table table-condensed table-striped pollingstartions-table"
                       data-toolbar="#toolbar"
                       data-show-refresh="false"
                       data-show-toggle="false"
                       data-show-columns="false"
                       data-show-export="false"
                       data-detail-formatter="detailFormatter"
                       data-minimum-count-columns="1"
                       data-pagination="true"
                       data-id-field="id"
                       data-page-list="[25, 50, 100, ALL]"
                       data-show-footer="false"
                       data-side-pagination="server"
                       data-url="{{ url('/') }}/{{$data['cases']->election_id}}/stations/data"
                       data-query-params="queryParams"
                       data-cookie="false"
                       data-cookie-id-table="id"
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
        var ordersCompleted = [];
        var options = $table.bootstrapTable('getOptions');


        //Get a reference to the link on the page
        // with an id of "mylink"
        var labelNotRegistered = document.getElementById("label_not_registered");
        var labelRegistered = document.getElementById("label_registered");
        var labelProblematic = document.getElementById("label_problematic");
        var labelSent = document.getElementById("label_sent");
        var labelResend = document.getElementById("label_resend");

        function initTable() {
            $table.bootstrapTable({
                height: getHeight(),
                pageSize: 25,
                columns: [
                    @if(Auth::user()->group_id == 1)
                    {
                        field: 'operateRemove',
                        title: '{!!trans('messages.operateRemove')!!}',
                        align: 'center',
                        events: operateEvents,
                        formatter: statusFormatter
                    },
                    @endif
                    {
                        field: 'status_id',
                        title: '{!!trans('messages.οperate')!!}',
                        sortable: true,
                        formatter: statusFormatter,
                        align: 'center'
                    },                    
                    {
                        field: 'action_id',
                        title: '{!!trans('messages.action')!!}',
                        visible: false
                    },
                    {
                        field: 'electoral_region_id',
                        title: '{!!trans('messages.electoral_region_id')!!}',
                        visible: false
                    },
                        {
                        field: 'electoral_region_name',
                        title: '{!!trans('messages.title_electoral_region')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'municipality_name',
                        title: '{!!trans('messages.title_municipality')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        field: 'id',
                        title: '{!!trans('messages.title_polling_station')!!}',
                        align: 'center',
                        valign: 'middle',
                        sortable: true
                    },
                    {
                        field: 'status_descr',
                        title: '{!!trans('messages.title_status')!!}',
                        sortable: true,
                        align: 'center'
                    },
                    @if(Auth::user()->group_id == 1)
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
        function operateFormatterInitial(value, row, index) {

                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a>  ',
                '<span class="operate-seperator">|</span>',
                '<a class="remove" href="javascript:void(0)" title="{!!trans('messages.remove')!!}">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');

        }

       

        function detailFormatter(index, row) {
            return;
            var html = [];
            $.each(row, function (key, value) {
                html.push('<p><b>' + key + ':</b> ' + value + '</p>');
            });
            return html.join('');
        }

        function statusFormatterTest(value, row, index) {
            if  ((row.status_id==2) || (row.status_id=4)  ) {
                @if ( Auth::user()->group_id == 1 ) 
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            '<span class="operate-seperator">|</span>',
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.send')!!}">',
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
                
            } else if ( (value ==1) || (value==5))  {
                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a> ',
                ].join('');
            } else if ( value ==-1 ) {
                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.add')!!}">',
                '<i class="glyphicon glyphicon-plus"></i>',
                '</a> ',
                ].join('');
            } else {
                return [ ].join('');
            };    
                
        }

        function statusFormatter(value, row, index) {
            
            if  ( (value== {{$data['polling_station_status_registered']}} ) || (value== {{$data['polling_station_status_edited_for_resend']}} ) ) {
                @if (Auth::user()->group_id == 1 ) 
                    return [
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                            '<i class="glyphicon glyphicon-edit"></i>',
                            '</a> ',
                            '<span class="operate-seperator">|</span>',
                            '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.send')!!}">',
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
                
            } else if ( (value == {{$data['polling_station_status_problematic']}} ) || (value=={{$data['polling_station_status_sent_but_problematic']}})) {
                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.edit')!!}">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a> ',
                ].join('');
            } else if ( value == {{$data['polling_station_status_unregistered']}}) {
                return [
                '<a class="edit" href="javascript:void(0)" title="{!!trans('messages.add')!!}">',
                '<i class="glyphicon glyphicon-plus"></i>',
                '</a> ',
                ].join('');
            } else {
                return [ ].join('');
            };    

        window.operateEvents = {
            'click .edit': function (e, value, row, index) {
              window.location.href = "{{ url('/') }}/{{$data['election_id']}}/pollingstations/"+ row.electoral_region_id +'/' + row.id + '/edit';
            },
            'click .remove': function (e, value, row, index) {
                var r = confirm("{!!trans('messages.confirm_delete_polling_station')!!}");
                if (r == true) {
                    $.ajax({
                        url: "{{ url('/') }}/{{$data['election_id']}}/pollingstations/destroy",
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


         function ClearForm() {
            $('select[name=search_case_region]').val('').trigger('change');
            $('select[name=search_municipality]').val('').trigger('change');
            $('select[name=search_status]').val('').trigger('change');
            $('input[name=search_station]').val('');
        }

        $('#label_not_registered').click (function() {

            // Your code here...

            ClearForm();
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_UNREGISTERED')}});  

            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

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
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_REGISTERED')}});  

            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

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
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_PROBLEMATIC')}});  
            
            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

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
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_SENT_BUT_PROBLEMATIC')}});  

            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

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
            // Your code here...
            ClearForm();
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_SENT')}});  

            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

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
            // Your code here...
            call ClearForm();
            $('select[name=search_status]').val({{config('app.POLLING_STATION_STATUS_EDITED_FOR_RESEND')}});  

            // document.getElementByName("search_status").value = "1";
            $table.bootstrapTable('refresh');

            //If you don't want the link to actually 
            // redirect the browser to another page,
            // "google.com" in our example here, then
            // return false at the end of this block.
            // Note that this also prevents event bubbling,
            // which is probably what we want here, but won't 
            // always be the case.
            return false;
        });

        $('#btn-search').click(function () {
           // console.log('table.url :' + $data['elections']->election_id );
            $table.bootstrapTable('refresh');
        });

        function queryParams(params) {
            console.log('dta/query paraamas');
            if($('select[name=search_case_region]').var()>0) {
                params['search_case_region'] = $('select[name=search_case_region]').val();
            }    
           // console.log($('select[name=search_polling_station]').val());
            if($('input[name=search_polling_station]').val() >0) {
                    
                    params['search_polling_station'] = $('input[name=search_polling_station]').val();
            }        
            if($('select[name=search_municipality]').val() >0) {
                params['search_municipality'] = $('select[name=search_municipality]').val();
            }
            if ( $('select[name=search_status]').val()>0 ) {
                params['search_status'] = $('select[name=search_status]').val();  
            }
            else if ( $('select[name=search_status]').val() ==-1 )
                params['search_status'] = $('select[name=search_status]').val();  
            else {
            };    
            return params;
        }

        $('#btn-clear-search').click(function () {
            $('select[name=search_case_region]').val('');
            $('input[name=search_station]').val('');
            $('select[name=search_municipality]').val('');
            $('select[name=search_status]').val('');
            $table.bootstrapTable('refresh');
        });

        function getHeight() {
            return $(window).height() - $('h1').outerHeight(true);
        }

        $(function () {

           // getPreferences();
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