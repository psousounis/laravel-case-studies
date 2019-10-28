@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{!!trans('messages.title_stations_send')!!}</h2>
                <form id="part-form" action="{{ url('/send') }}" method="get">
                    <input type="hidden" name="id" value="0"/>
                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="col-sm-12">
                                @if ($data['send_msg_id'] == config('app.CASE_STATION_SEND_ID_SUCCEED') )
                                <label>{!!trans('messages.case_station_send_success')!!}</label>
                                <label>{!!trans('messages.case_sent_to_main_server')!!}{{': ' }} {{$data['updated']}}{!!trans('messages.case_sent_to_main_server')!!}</label>
                                @elseif ($data['send_msg_id'] == config('app.CASE_STATION_SEND_ID_FAILED') )
                                <label>{!!trans('messages.case_station_send_failed')!!}</label>
                                @elseif ($data['send_msg_id'] == config('app.CASE_STATION_SEND_ID_UNDER_CONSTRUCTION') )
                                <label>{!!trans('messages.case_send_under_construction')!!}</label>
                                @else
                                <label>{!!trans('Unknown Message!!')!!}</label>
                                @endif
                        </div>
                    </div>

                    <div class="clr"></div>
                    <div class="sep-20"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="{{ url('/') }}" class="btn btn-danger">{!!trans('messages.return')!!}</a>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
@section('footerScript')
    <script type="text/javascript">
        $(function () {
        
        });
    </script>
@endsection