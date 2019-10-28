@extends('layouts.app')
@section('content')
<!-- modal  -->      
<div class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <p>One fine body…</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
    <div class="container-fluid">                                    
                <form id="part-form" action="{{ url('/')}}/{{$data['case_id']}}/stations/store" method="POST">
                    <input type="hidden" id="id" name="id" value="{{ $data['station_id'] }}"/>
                    <input type="hidden" id="case_id" name="case_id" value="{{ $data['case_id'] }}"/>
                    <input type="hidden" id="case_type_id" name="case_type_id" value="{{ $data['case_type_id'] }}"/>
                    <input type="hidden" id="case_region_id" name="case_region_id" value="{{ $data['case_region_id'] }}"/>
                    <input type="hidden" id="action_id" name="action_id" value="{{ $data['action_id'] }}"/>
                    {{ csrf_field() }}
                    
                    <div class="col-md-4">
                    <div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">{!!trans('messages.label_station')!!}{{': '}}@if ($data['station_id']>0) {{$data['station_id']}}@else  @endif{{' ('}}@if ($data['action_id']===2) {!!trans('messages.action_edit')!!}@else {!!trans('messages.action_add')!!}@endif{{')'}}</div>
                            <div class="panel-body">
                            @foreach ( $data['station'] as $station)
                                <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="status_descr" class="control-label col-sm-6">{!!trans('messages.label_status_descr')!!}</label>
                                            <div class="col-sm-6">
                                                <input type="text"   name="status_descr" value="{{$station->status_descr }}" class="form-control"΄ readonly>
                                                <input type="hidden" id="status_id" name="status_id" value="{{ $station->status_id}}"/>
                                            </div>
                                        </div>
                                </div>
                                <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="case_region" class="control-label col-sm-6">{!!trans('messages.label_case_region')!!}</label>
                                            <div class="col-sm-6">
                                                <!-- <input type="text"   id="case_region" name="case_region" value="{{$station->case_region_name }}" class="form-control drop-select2" readonly> -->
                                                <select class="form-control drop-select" name="case_region" id="case_region" required>
                                                    <option value="">-- {!!trans('messages.select_case_region')!!} --</option>
                                                    @foreach ($data['case_regions'] as $caseregion)
                                                       
                                                            @if (($station->case_region_id >0) and ($caseregion->case_region_id == $station->case_region_id)) {
                                                               
                                                                <option value="{{ $caseregion->case_region_id }}" selected>{{ $caseregion->case_region_name }}</option>
                                                            }    
                                                            @else {
                                                             @if (old('case_region') == $caseregion->case_region_id)
                                                                    <option value="{{ $caseregion->case_region_id }}" selected>{{ $caseregion->case_region_name }}</option>
                                                                @else
                                                                    <option value="{{ $caseregion->case_region_id }}">{{ $caseregion->case_region_name }}</option>
                                                                @endif
                                                                
                                                            }
                                                            @endif    
                                                       
                                                    @endforeach

                                                </select>     
                                            </div>
                                        </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                    <!-- <div class="col-sm-6"> -->
                                        <label  name="label_station_id" class="control-label col-sm-6">{!!trans('messages.label_station')!!}</label>
                                        <div class="col-sm-6">
                                            <!-- <input type="number" from="label_station_id" id="station_id" name="station_id" value="{{ $data['station_id']}}" class=" form-control"
                                                    required> -->
                                            <select class="form-control drop-select2" name="station_id" id="station_id" required>
                                                <option value="">-- {!!trans('messages.select_station')!!} --</option>
                                                
                                            </select>              
                                        </div>
                                    </div>
                                </div>
                                 
                                    
                                    
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="municipality" class="control-label col-sm-6">{!!trans('messages.label_municipality')!!}</label>                               
                                            <div class="col-sm-6">
                                            @if (old('municipality_id') !='' and old('municipality_id') !=null )
                                                <input type="text"   id="municipality" name="municipality" value="{{ old('municipality') }}" class="form-control" readonly>
                                                <input type="hidden" id="municipality_id" name="municipality_id" value="{{ old('municipality_id')}}"/>
                                                <input type="hidden" id="old_municipality_id" name="old_municipality_id" value="{{ old('municipality_id')}}"/>
                                            @else
                                                <input type="text"   id="municipality" name="municipality" value="{{ $station->municipality_name }}" class="form-control" readonly>
                                                <input type="hidden" id="municipality_id" name="municipality_id" value="{{$station->municipality_id }}"/>
                                                <input type="hidden" id="old_municipality_id" name="old_municipality_id" value="{{ $station->municipality_id }}"/>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-row">
                                        <div class="form-group col-sm-12 sep-20">
                                             <div class="sep-20"></div>
                                            </div> 
                                        </div> 
                                    </div> -->
                                    
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="registered" class="control-label col-sm-8" >{!!trans('messages.label_registered')!!}</label>
                                            <div class="col-sm-4">
                                                    @if (old('registered') == '' )   
                                                        <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="registered" name="registered" class="form-control" value="{{$station->registered}}" size="5" maxlenght="5"  required>
                                                    @else                                    
                                                        <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="registered" name="registered" class="form-control" value="@if(old('registered')==null)0 @else{{old('registered')}}@endif" size="5" maxlenght="5" required>
                                                    @endif        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="voted" class="control-label col-sm-8">{!!trans('messages.label_voted')!!}</label>
                                            <div class="col-sm-4">
                                                @if (old('voted') == '' )   
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="voted" name="voted" class="form-control" value="{{$station->voted}}" size="5" maxlenght="5" min="0" required>
                                                @else 
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="voted" name="voted" class="form-control" value="@if(old('voted')==null)0 @else{{old('voted')}}@endif" size="5" maxlenght="5" min="0" required>
                                                @endif       
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                        <label for="votes_invalid" class="control-label col-sm-8" >{!!trans('messages.label_votes_invalid')!!}</label>
                                            <div  class="control-label col-sm-4">
                                                @if (old('votes_invalid') == '' )   
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="votes_invalid" name="votes_invalid" class="form-control" value="{{$station->votes_invalid}}" size="5" maxlenght="5" required>
                                                @else 
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="votes_invalid" name="votes_invalid" class="form-control" value="@if(old('votes_invalid')==null)0 @else{{old('votes_invalid')}}@endif" size="5" maxlenght="5" required>
                                                @endif 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="votes_blank" class="control-label col-sm-8">{!!trans('messages.label_votes_blank')!!}</label>
                                            <div class="col-sm-4">
                                                @if (old('votes_blank') == '' )
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="votes_blank" name="votes_blank" class="form-control" value="{{$station->votes_blank}}" size="5" maxlenght="5" required>
                                                @else 
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');"  id="votes_blank" name="votes_blank" class="form-control" value="@if(old('votes_blank')==null)0 @else{{old('votes_blank')}}@endif" size="5" maxlenght="5" required>
                                                @endif 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="votes_invalid_blank" class="control-label col-sm-8">{!!trans('messages.label_votes_invalid_blank')!!}<?php echo ":";?></label>
                                            <div class="col-sm-4">
                                                @if (old('votes_invalid_blank') == '' )
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="votes_invalid_blank" name="votes_invalid_blank" class="form-control" value="{{$station->votes_invalid_blank}}" size="5" maxlenght="5" required>
                                                @else 
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');"  id="votes_invalid_blank" name="votes_invalid_blank" class="form-control" value="@if(old('votes_invalid_blank')==null)0 @else{{old('votes_invalid_blank')}}@endif" size="5" maxlenght="5" required>
                                                @endif 
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="votes_valid" class="control-label col-sm-8">{!!trans('messages.label_votes_valid')!!}</label>
                                            <div class="col-sm-4"> 
                                            @if (old('votes_valid') == '' )     
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');" id="votes_valid" name="votes_valid" class="form-control" value="{{$station->votes_valid}}" size="5" maxlenght="5" required>
                                                @else 
                                                    <input type="number" min="0" step="1" oninput="validity.valid||(value='');"  id="votes_valid" name="votes_valid" class="form-control" value="@if(old('votes_valid')==null)0 @else{{old('votes_valid')}}@endif" size="5" maxlenght="5" required>
                                                @endif 
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <div class="sep-20"></div>
                                        </div>
                                    </div> -->
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <label for="station_officer_name" class="control-label col-sm-5" >{!!trans('messages.label_station_officer')!!}</label>
                                            <div class="col-sm-7"> 
                                            @if (old('station_officer_name') !='' and old('station_officer_name') !=null )
                                                <input type="text"  id="station_officer_name" name="station_officer_name" value="{{old('station_officer_name')}}" class="form-control" size="30" maxlenght="30" readonly >
                                            @else
                                                <input type="text"  id="station_officer_name" name="station_officer_name" value="{{$station->station_officer_name}}" class="form-control" size="30" maxlenght="30" readonly >
                                            @endif
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-sm-12">        
                                            <label for="station_officer_phone" class="control-label col-sm-4">{!!trans('messages.label_station_officer_phone')!!}</label>     
                                            <div class="col-sm-8">
                                            @if (old('station_officer_phone') !='' and old('station_officer_phone') !=null )
                                                <input type="text" id="station_officer_phone" name="station_officer_phone" value="{{old('station_officer_phone')}}" class="form-control" size="30" maxlenght="30" readonly>
                                            @else                                            
                                                <input type="text" id="station_officer_phone" name="station_officer_phone" value="{{$station->station_officer_phone}}" class="form-control" size="30" maxlenght="30" readonly>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                     
                                @endforeach            
                            </div>
                        </div>
                    </div>

                    <!-- {{ csrf_field() }} -->
                    <!-- class="form-group col-sm-12" -->
                    <div>
                        @if ($data['user']->group_id != config('app.USER_GROUP_GUEST'))
                        <input type="submit" id="btn-submit" value="@if($data['action_id']==1){!!trans('messages.save')!!}@else{!!trans('messages.save_and_return')!!}@endif" class="btn btn-success">
                        @endif
                        <a href="{{ url('/') }}/{{$data['case_id']}}/stations" class="btn btn-danger">{!!trans('messages.cancel')!!}</a>
                    </div>  
                 </div>   
                    {{ csrf_field() }}
                        
                 <div id="caseitems-panel" name="caseitems-panel" class="caseitems-panel">
                 </div>
                    <!-- <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">{!!trans('messages.station')!!}{{$data['station_id']}}@if ($data['action_id']===2) {!!trans('messages.action_edit')!!} @else {!!trans('messages.action_add')!!}@endif</div>
                            <div class="panel-body">
                    <div class="form-row">
                                        <div class="form-group col-sm-12">
                                            <div class="clr"></div>
                                        </div>
                                    </div>     -->                                 
                    <!-- <div class="sep-20"></div>
                    <div class="form-group col-sm-12"> -->
                                               
                 
                
                   
                </form>
            
        
    </div>
@endsection
@section('footerScript')
    <script type="text/javascript">
          $(function () { 
            var user;
            var flag_is_checked = false;
            var caseitems_bkp= new Array();
            var station_set_flag=false;
            var station_id_bkp=this;
            $('.drop-select2').select2();           
            
            var votesInvalid = document.getElementById("votes_invalid");
            var votesblank = document.getElementById("votes_blank");
            var votesInvalidBlanc = document.getElementById("votes_invalid_blank");
            var caseitems = @json($data['case_items']);
            var case_regions = @json($data['case_regions']);

            var title_info= {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_INFO')!!}{!!trans("'")!!} ;
            var title_primary= {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_PRIMARY')!!}{!!trans("'")!!};
            var title_success={!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_SUCCESS')!!}{!!trans("'")!!};
            var title_warning={!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_WARNING')!!}{!!trans("'")!!};
            var title_danger={!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_DANGER')!!}{!!trans("'")!!};
            var title_Ok={!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_OK')!!}{!!trans("'")!!};
            var title_Cancel={!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_CANCEL')!!}{!!trans("'")!!};
            var title_Confirm= {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_CONFIRM')!!}{!!trans("'")!!};


            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_INFO] = title_info;
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_PRIMARY] = title_primary;
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_SUCCESS] = title_success;
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_WARNING] = title_warning;
            BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DANGER] = title_danger;
            BootstrapDialog.DEFAULT_TEXTS['OK'] = title_Ok;
            BootstrapDialog.DEFAULT_TEXTS['CANCEL'] = title_Cancel;
            BootstrapDialog.DEFAULT_TEXTS['CONFIRM'] = title_Confirm;
            

            function RefreshVotesFields(){
                var  votes_invalid_blank = 0;
                var votes_valid=0;
            
                if ($("#votes_invalid").val() != null) {
                    votes_invalid_blank = parseInt($("#votes_invalid").val());
                }

                if ($("#votes_blank").val() != null) {
                    votes_invalid_blank = votes_invalid_blank  + parseInt($("#votes_blank").val());
                }
                $("#votes_invalid_blank").val(votes_invalid_blank);

                
                if ($("#voted").val() != null && (!$("#votes_valid").val()>0) ) {
                    votes_valid = parseInt($("#voted").val());
                    votes_valid = votes_valid - votes_invalid_blank;
                    $("#votes_valid").val(votes_valid);
                }
                
            }

            $("#voted").keyup(function() { 
                 $("#votes_invalid_blank").val( parseInt($("#votes_invalid").val()) +  parseInt($("#votes_blank").val()));
                 $("#votes_valid").val(parseInt($(this).val()) - parseInt($("#votes_invalid_blank").val()));
                RefreshVotesFields();
            });

            $("#votes_invalid").keyup(function() { 
                 $("#votes_invalid_blank").val( parseInt($(this).val()) +  parseInt($("#votes_blank").val()));
                 $("#votes_valid").val(parseInt($("#voted").val()) - parseInt($("#votes_invalid_blank").val()));
                RefreshVotesFields();
            });

            $("#votes_blank").keyup( function () {
                 $("#votes_invalid_blank").val( parseInt($(this).val()) +  parseInt($("#votes_invalid").val()));
                 $("#votes_valid").val(parseInt($("#voted").val()) - parseInt($("#votes_invalid_blank").val()));
                RefreshVotesFields();
            });

            $("#station_id").select2({
                language: {
                    noResults: function (params) {
                    return config('app.ERROR_MESSAGE_INVALID_STATION');
                    }
                }
            });

            $("#part-form").submit( function() {
                var i;
                var total_votes;
                var voted;
                var msg;
                var mismatch;
                
                if (flag_is_checked==true) {
                    clearPartiesVotes();
                    return true;
                }    
        
                action_id = parseInt(document.getElementById('action_id').value); 
                if (action_id != {{config('app.ACTION_ADD_ID')}} && action_id != {{config('app.ACTION_MODIFY_ID')}}) {
                    alert('Λανθασμένη ενέργεια (action)!!');
                    return false;
                }
                station_id = parseInt(document.getElementById('station_id').value); 
                if (!(station_id>0)) {
                   alert({!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_STATION')!!}{!!trans("'")!!});
                    return false;
                }
                municipality_id = parseInt(document.getElementById('municipality_id').value); 
                if (!(municipality_id>0)) {
                    //alert('invalid municipality (municipality_id)!!');
                   alert({!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_MUNICIPALITY')!!}{!!trans("'")!!}+'('+municipality_id+')');
                    return false;
                }
                old_municipality_id = parseInt(document.getElementById('old_municipality_id').value); 
                
                if (!(old_municipality_id>=0)) {
                    //alert('invalid municipality (old_municipality_id)!!');
                    alert({!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_MUNICIPALITY')!!}{!!trans("'")!!} +  '(' + old_municipality_id +')');
                    return false;
                }

                status_id = parseInt(document.getElementById('status_id').value); 
                if (status_id != {{config('app.STATION_STATUS_UNREGISTERED')}}
                    && status_id != {{config('app.STATION_STATUS_REGISTERED')}}
                    && status_id != {{config('app.STATION_STATUS_PROBLEMATIC')}}
                    && status_id != {{config('app.STATION_STATUS_SENT')}}
                    && status_id != {{config('app.STATION_STATUS_EDITED_FOR_RESEND')}}
                    && status_id != {{config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC')}} ) {
                    alert('Μη αποδεκτή κατάσταση!!');
                    return false;
                }
                msg='';
                mismatch=false;
                registered= parseInt(document.getElementById('registered').value);

                if (!(registered>=0)) {
                    // alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_REGISTERED'));
                    //mismatch=true;                  
                    //msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_REGISTERED')!!}{!!trans("'")!!} + '\n';
                }
                voted= parseInt(document.getElementById('voted').value); 
                if (!(voted>=0)) {
                    // alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTED'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTED')!!}{!!trans("'")!!} + '\n';
                }
                votes_invalid = parseInt(document.getElementById('votes_invalid').value); 
                if (!(votes_invalid>=0)) {
                    // alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTES_INVALID'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTES_INVALID')!!}{!!trans("'")!!}  + '\n';
                }
                votes_blank = parseInt(document.getElementById('votes_blank').value);
                if (!(votes_blank>=0)) {
                    // alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTES_BLANC'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTES_BLANC')!!}{!!trans("'")!!} + '\n';
                }
                votes_invalid_blank = parseInt(document.getElementById('votes_invalid_blank').value);
                if (!(votes_invalid_blank>=0)) {
                    // alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTES_INVALID_BLANC'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTES_INVALID_BLANC')!!}{!!trans("'")!!}  + '\n';
                }
                votes_valid = parseInt(document.getElementById('votes_valid').value); 
                if (!(votes_valid>=0)) {
                    //alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTES_VALID'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTES_VALID')!!}{!!trans("'")!!}   + '\n';
                }
                votes_voted = parseInt(document.getElementById('voted').value); 
                if (!(votes_voted>=0)) {
                    //alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTED'));
                    mismatch=true;                  
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTED')!!}{!!trans("'")!!}  + '\n';
                }
                
                
                if (mismatch) {
                    msg = msg.substr(0, msg.length-1); 
                    //BootstrapDialog.alert(msg);
                    BootstrapDialog.show({
                        type: BootstrapDialog.TYPE_DANGER,
                        message: msg,
                        buttons: [{
                        label: {!!trans("'")!!}{!!trans('messages.DIALOG_BUTTON_TITLE_CLOSE')!!}{!!trans("'")!!} ,
                        action: function(dialogRef){    
                        dialogRef.close();
                        }
                    }],
                    });
        
                    return false;
               }

                if (votes_voted != votes_valid + votes_invalid + votes_blank) {
                    mismatch=true;
                    //alert(config('app.ERROR_MESSAGE_INVALID_NUMBER_VOTED'));
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_MESSAGE_INVALID_NUMBER_VOTED')!!}{!!trans("'")!!} + '\n';
                     //return false;
                }
                if (votes_invalid_blank !=votes_invalid + votes_blank) {
                    mismatch=true;
                    //alert(config('app.ERROR_INVALID_PLUS_BLANC_MISMATCH_TOTAL_INVALID_BLANC'));
                    msg= msg + {!!trans("'")!!}{!!trans('messages.ERROR_INVALID_PLUS_BLANC_MISMATCH_TOTAL_INVALID_BLANC')!!}{!!trans("'")!!} +'\n';
                     //return false;
                }

                //caseitems= document.getElementsByClassName('caseitem-field');
                if (caseitems_length == 0) {
                    alert(config('app.ERROR_NO_CASEITEMS_FOUND') + '\n');
                    return false;
                }
                total_votes=0;
                
                for (i=0; i<caseitems_length; i++){
                    total_votes = total_votes + parseInt(document.getElementById('caseitem_'+caseitems[i].case_item_code).value);
                }
                // voted = parseInt($("voted").val()); 
                if (votes_valid==null) {
                        votes_valid=0;
                }
                if (total_votes != votes_valid) {
                    //alert(config('app.ERROR_CASEITEMS_TOTAL_VOTES_MISMATCH_VALID'));
                    msg = msg+ {!!trans("'")!!}{!!trans('messages.ERROR_CASEITEMS_TOTAL_VOTES_MISMATCH_VALID')!!}{!!trans("'")!!} + '\n';
                    mismatch=true;                  

                }

               
               if (mismatch) {
                    msg = msg + {!!trans("'")!!}{!!trans('messages.WARNING_DATA_WILL_BE_SAVED_AS_PROBLEMATIC')!!}{!!trans("'")!!}  + '\n';
                    //alert(msg);
                    msg = msg + {!!trans("'")!!}{!!trans('messages.MESSAGE_PRESS_YES_NO_BUTTON')!!}{!!trans("'")!!};
                    BootstrapDialog.confirm({
                                title: {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_WARNING')!!}{!!trans("'")!!} ,
                                message: msg,
                                type: BootstrapDialog.TYPE_PRIMARY, // <--  TYPE_WARNING Default value is BootstrapDialog.TYPE_PRIMARY
                                closable: false, // <-- Default value is false --> itan true
                                draggable: true, // <-- Default value is false
                                btnCancelLabel: {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_NO')!!}{!!trans("'")!!} , // <-- Default value is 'Cancel',
                                btnOKLabel: {!!trans("'")!!}{!!trans('messages.BUTTON_TITLE_YES')!!}{!!trans("'")!!} , // <-- Default value is 'OK',
                                btnOKClass: 'btn-warning', // <-- If you didn't specify it, dialog type will be used,
                                callback: function(result) {
                                    // result will be true if button was click, while it will be false if users close the dialog directly.
                                    if(result) {  
                                        flag_is_checked=true; 
                                        

                                       $('#part-form').submit();
                                       return true;
                                    }else {
                                        return false;
                                    }
                                }
                            });
                    return false;
               } else {
               //alert('mismatch found to be false!!!');
              // store temprarily the caaseitems votes
               //clearcaseitemsVotes();
                return true;
               } 
            });

            $("#case_region").change(function () {
                // $("#station_id").prop("disabled", true);
                // $("#station_id").val('');
                // $("#").prop("disabled", true);
                // $("#").val('0');
 
                if($("#case_region").val()>0) {
                    var q=$("#case_id").val()+ '^^' + $("#case_region").val();
                    var id=$("#id").val();
                    // "{{ url('/') }}/{{$data['case_id']}}/stations/getStations/" +  Number($(this).val()),
                    $.ajax({
                        url: "{{ url('/') }}/stations/getStations2/" + q,
                        cache: false,
                        success: function (res) {
                            var data = JSON.parse(res);
                            var id;
                            //console.log(data);
                            if (station_set_flag=true) {
                                //id = parseInt($("#id").val);
                                var id = parseInt(document.getElementById('id').value);
                                
                            } else{
                                id=0;
                            }
                            var temp= {!!trans("'")!!}{!!trans('messages.title_choose_station')!!}{!!trans("'")!!};
                            var tpl = '<option value="">-- '+ temp + '--</option>';
                            var tpl = '<option value="">-- '+ temp + '--</option>';
                            for (var i = 0; i < data.length; i++) {
                                if (id==data[i].station_id ) { 
                                tpl += '<option value="' + data[i].station_id + '" selected>' + data[i].station_id + '</option>';
                                } else {
                                    tpl += '<option value="' + data[i].station_id + '">' + data[i].station_id + '</option>';
                                }
                            }
                            
                            $("#station_id").html(tpl);
                            $("#station_id").prop("disabled", false);
                        },
                        fail: function(){

                                BootstrapDialog.alert( {!!trans("'")!!}{!!trans('messages.ERROR_PROBLEM_DURING_STATION_FETCHING')!!}{!!trans("'")!!});
                        }
                    });
                }
            });    

            
            $("#station_id").change(function () {
               // alert('BLUR enabled'); 
                if ( Number($(this).val()) > 0) {  
                    //console.log($("#case_region").val());                                
                    var q =  $("#action_id").val()+'^^'+$("#case_id").val()+'^^'+$("#case_region").val()+'^^'+ $("#station_id").val();
                    
                        $.ajax({                            
                            url: "{{ url('/') }}/stations/isStationValid2/"+ encodeURIComponent(q),
                            cache: false,
                            success: function (res) {
                                var data = JSON.parse(res);
                                //console.log('isStationValid2');
                                
                                if (data.length>0) {
                                    
                                        var p =  $("#case_id").val()+'^^'+$("#case_region").val() +'^^'+ $("#station_id").val();
                                        $.ajax({
                                            url: "{{ url('/') }}/stations/hasStationBeenRegistered/"+ p,
                                            cache: false,
                                            success: function (res) {
                                                var data = JSON.parse(res);
                                                //console.log('hasStationBeenRegistered');
                                                
                                                if (data.length>0) {
                                                    //console.log('already exists');
                                                    // section <begin>
                                                    // note: do not clear municipality& station_offficers because
                                                    // i set the old value as station_id value
                                                    //$("#municipality_id").val(null);
                                                    //$("#municipality").val('');
                                                    //$("#station_officer_name").val('');
                                                    //$("#station_officer_phone").val('');
                                                    // section  <end>

                                                    
                                                    BootstrapDialog.alert('Station <' + $("#station_id").val() +'> of Case Region <'+ $("#case_region option:selected").text() + '> has been registered!!');
                                                    $("#station_id").val(String(station_id_bkp));
                                                    $("#station_id").trigger('change.select2');
                                                } else {
                                                    var q = $("#case_id").val()+'^^'+$("#case_region").val()+'^^'+ $("#station_id").val();
                                                    $.ajax({
                                                    url: "{{ url('/') }}/stations/getStationDetails2/"+ q,
                                                    cache: false,
                                                    success: function (res) {
                                                        var data = JSON.parse(res);
                                                        //console.log('getStationDetails2');
                                                        if (data.length>0) {
                                                            //alert('ajax succeed with data');  
                                                            //$("#case_region_id").val(data[0].case_region_id);
                                                            //$("#case_region").val(data[0].case_region_name);
                                                            $("#municipality_id").val(data[0].municipality_id);
                                                            $("#municipality").val(data[0].municipality_name);
                                                            $("#station_officer_name").val(data[0].station_officer_name).trigger('change');
                                                            $("#station_officer_phone").val(data[0].station_officer_phone).trigger('change');
                                                            station_id_bkp = parseInt($("#station_id").val());
                                                            if ($("#case_type_id").val() == 1) {
                                                                var q=$("#case_id").val()+'^^'+ $("#case_region").val()+'^^'+ $("#station_id").val();
                                                                $.ajax({
                                                                    url: "{{ url('/') }}/stations/getCaseItems/"+ q,
                                                                    cache: false,
                                                                    success: function (res) {
                                                                        var data = JSON.parse(res);
                                                                        console.log(data);
                                                                        if (data.length>0) {
                                                                            caseitems_length=data.length;
                                                                            populate_casesitems(data);
                                                                            if (station_set_flag) {
                                                                                @if ($data['user']->group_id  == config('app.USER_GROUP_GUEST')) 
                                                                                    $.find(':input:not(:disabled)').prop('disabled',true);
                                                                               @endif
                                                                                station_set_flag=false;
                                                                            }
                                                                        } else {
                                                                            //var el= document.getElementById("caseitems-panel");
                                                                            //el.InnerHtml='No stations found!!';
                                                                            caseitems_bkp.length=0; //?
                                                                            caseitems_length=0;
                                                                            $('#caseitems-panel').html('No case items found for this Municipality!!');

                                                                            BootstrapDialog.alert('No case items found for this Municipality!!');
                                                                        }
                                                                    },
                                                                    fail: function(){
                                                                        BootstrapDialog.alert('An error has been occured during searching case items for this station!!');
                                                                    }
                                                                }); 
                                                            }   
                                                        } else {

                                                            BootstrapDialog.alert('There is no station with this code!!');
                                                            $("#municipality_id").val(null);
                                                            $("#municipality").val('').trigger('change');
                                                            $("#station_officer_name").val('').trigger('change');
                                                            $("#station_officer_phone").val('').trigger('change');
                                                        }
                                                    },
                                                    fail: function(){

                                                        BootstrapDialog.alert('An error has been occured during searching of station data!!');
                                                    }
                                                    });                                
                                                    
                                                }
                                            },
                                            fail: function(){
                                                BootstrapDialog.alert('There is a problem during checking this station details!!');
                                            }
                                        });  
                                    
                                       

                                    //console.log('IsStationValid2 succcedd');
                                    if (data[0].case_region_id>0) {
                                      //  console.log('2');
                                    
                                    }

                             }        
                            },
                            fail: function(){

                                BootstrapDialog.alert('There is a problem during checking this station details!!');
                            }
                    }); 
                }
                    if ( Number($(this).val()) > 0) {                                  
                        
                } else {
                    
                }

            });

            function saveCaseItemsVotes() {
                var caseitemsvotes = {};
                    for (var i=0;i<caseitems.length;i++) {
                    //caseitemsvotes['caseitem_'+ i] = $('input[name=caseitem_'+i+1+']').val();
                    k = i+1;
                    caseitemsvotes['caseitem_'+ i] = $('#caseitem_'+k).val();
                    //settingsEvents.search_station = $('input[name=search_station]').val();
                    }
                localStorage.setItem('caseitemsvotes', JSON.stringify(caseitemsvotes));
            }
            function clearCaseItemsVotes() {
                var caseitemsvotes = {};
                    localStorage.setItem('caseitemsvotes', JSON.stringify(caseitemsvotes));
            }        
            function getCaseItemsVotes() {
                var obj = (JSON.parse(localStorage.getItem('caseitemsvotes')));
                if (obj) {
                    var caseitemsvotes = Object.keys(obj).map(k => obj[k]);
                    for (var i=0;i<caseitemsvotes.length;i++) {
                        // if (caseitemsvotes['caseitem_'+i]) {
                        // caseitems_bkp[i]=caseitemsvotes['caseitem_'+i];
                        // }
                        if (caseitemsvotes[i]) {
                            caseitems_bkp[i]=caseitemsvotes[i];
                        }
                    }    
                }
            }
            function populate_caseitems(caseitems) {
                var i,j,k;
                var html; 
                var temp;
                var max_caseitems_per_page;
                                
                j=0;k=0;
                max_caseitems_per_page= {!!config('app.CASE_ITEMS_PER_PAGE') !!};
                html='';
               // console.log(caseitems.length);
                for (i=0; i<=caseitems.length-1;i++){

                    if (j == 0) {
                      k=k+1;
                      html+= '<div class="col-md-4">';
                      html+='<div class="panel panel-primary">';
                      html+='<div class="panel-heading">{!!trans('messages.label_items')!!}'+' ('+(i+1)+'-';
                      if (k*max_caseitems_per_page<=caseitems.length) {
                         html += k * max_caseitems_per_page;
                      } else {
                         html+= caseitems.length;
                      }
                      html +=')'+'</div>';

                      html += '<div class="panel-body">';
                  }   
                      
                  j=j+1;

                  html += '<div class="form-row">';
                  html +='<div class="form-group col-sm-12">';
                  html+='<label for="caseitem_'+caseitems[i].case_item_id +'" id="label_caseseitem_'+ caseitems[i].case_item_code +'" class="control-label col-sm-9">'+ caseitems[i].case_item_code+'. '+ caseitems[i].case_item_name+'</label>';
                  html+='                        <div class="col-sm-3">';
                    
                    temp=caseitems_bkp[i];
                                       
                     if ( temp == undefined ) {
                         html+= '<input type="number"  min="0" step="1" oninput="validity.valid||(value=\'\');" id="caseitem_'+caseitems[i].case_item_code+'" name="caseitems['+caseitems[i].case_item_id+']" class="form-control" value="'+caseitems[i].votes+'" size="5" maxlenght="5" required>';
                     } else {
                         html+='<input type="number"  min="0" step="1" oninput="validity.valid||(value=\'\');" id="caseitem_'+caseitems[i].case_item_code+'" name="caseitems['+caseitems[i].case_item_id+']" class="form-control caseitem-field" value="';
                         if (temp ==null) {
                             html += '0';
                         } else {
                             html+= temp; // caseitems[i].votes.toString();
                         }   
                         html += '" size="5" maxlenght="5" required>'
                     } 


                  html+='                        </div>';
                  html+='                    </div>';           
                  html+='                </div>';           
                         //CASE_ITEMS_PER_PAGE
                    /* <?php echo config('app.CASE_ITEMS_PER_PAGE'); ?>  */
                      if (j == {!!config('app.CASE_ITEMS_PER_PAGE') !!}) {
                              html+='</div>';
                          html+='</div>';
                          html+='</div>';
                          j=0;

                      }
                          
              }           
                
              // CASEITEMS_PER_PAGE

                if (j != <?php echo config('app.CASE_ITEMS_PER_PAGE'); ?> && j !=0 ) {
                    html+='        </div>';
                    html+='    </div>';
                    html+='</div>';        
                }               
                html+='    </div>';
                //html+='}';    

                
                $("#caseitems-panel").html(html);
                
             return true;   
            }

            function initialize() {
            
            //console.log(case_regions);
            caseitems_length = caseitems.lenght;
            votes_invalid = parseInt(document.getElementById('votes_invalid').value);
            votes_blank = parseInt(document.getElementById('votes_blank').value);
            votes_invalid_blank = votes_invalid + votes_blank;
            $('#votes_invalid_blank').val(votes_invalid_blank);
             
             // if there is no case_region selected then se tby default the 1st case region from the corresponding array of case regions
            // if (input::old('case_region') !='' && input::old('case_region') != null ) {
            //     $('#case_region').val(input::old('case_region')); // Select the option with a value of ...
            //     $('#case_region').trigger('change'); // Notify any JS components that the value changed   
            // } else {
                case_region_id = parseInt(document.getElementById('case_region_id').value);
                case_region_list_id = parseInt(document.getElementById('case_region').value);
                station_id_bkp = parseInt(document.getElementById('id').value);
                 
                station_set_flag = true;
                if (!(case_region_list_id>0)) {
                    if (case_region_id>0) {
                        $('#case_region').val(case_region_id); // Select the option with a value of ...
                        $('#case_region').trigger('change');
                    } else {
                        if (case_regions.length==1) {
                            $('#case_region').val(case_regions[0].case_region_id); // Select the option with a value of ...
                            $('#case_region').trigger('change'); // Notify any JS components that the value changed   
                        }
                    }
                } else {
                    $('#case_region').trigger('change');
                }

            // }
            
            // select the current station------> in comments because i do not want ajax validation to be performed
            //station_id = document.getElementById('station_id').value;
            
            //  if (!(station_id>0)) {
            // if (id >0) {
            //       $('#station_id').val(id).trigger('change');
            //       station_id = $('#station_id').val;
            //       console.log(station_id);
            //       }
            
            //  } 
           }
           
           initialize()
           getCaseItemsVotes();
           caseitems_length = caseitems.length;
           populate_caseitems(caseitems);
           
        });   
    </script>
@endsection