<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"  rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="{{ url('/') }}/resources/assets/css/app.css">
    <link rel="stylesheet" href="{{ url('/') }}/resources/assets/css/cases.css">
    <link rel="stylesheet" href="{{ url('/') }}/resources/assets/css/bootstrap-table.min.css"> -->
    <!--@php echo url('/'); @endphp; -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/app.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/cases.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap-table.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet"/>
</head>
@php
    $actionName = strtolower(request()->route()->getActionMethod());
    preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $matches);
    $controllerName = strtolower(explode('Controller',$matches[1])[0]);
    //var_dump($controllerName);
@endphp
<body class="ctr-{{$controllerName}} act-{{$actionName}}">
<div id="app">
    @if (!Auth::guest())
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse">
                        <span class="sr-only">{!!trans('messages.toggle_navigation')!!}</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">  <!--url('/cases') -->
                        <img src="{{ url('/img/logo.png') }}" style="margin-top: -10px">
                    </a>
                </div>
                
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <div>
                    <ul class="nav navbar-nav ">
                           
                        @if (Auth::user()->group_id == config('app.USER_GROUP_ADMIN')) 
                            @if (Request::root())
                            <li> 
                                <a  href="{{ url('/create') }}">{!!trans('messages.case_add_new')!!}</a>
                            </li>
                            @endif
                        @endif
                        <!-- (!Request::is('*/tations*/detail*'))  -->
                        @if (Request::is('*/stations*') && (Auth::user()->group_id != config('app.USER_GROUP_GUEST')))
                            <li>
                             <!-- "{{ url('/') }}/{{$data['case_id']}}/stations/0/0/detail?action_id=1';
                              --> 
                              <!-- class="btn btn-default"<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> -->
                                <a   href="{{ url('/') }}/{{$data['case_id']}}/stations/0/0/detail?action_id=1'">{!!trans('messages.station_add_new')!!}</a>
                            </li>
                        @endif                                                    
                        
                    </ul>
                    </div >
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}" class='logout-link'>
                                        {!!trans('messages.logout')!!}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>          
                </div>
            </div>
        </nav>
    @endif
    <div class="col-md-12">
        @include ('layouts.partials._notifications')
    </div>
    @yield('content')
</div>
<!-- Scripts
<script src="{{ url('/') }}/js/all.js?{{time()}}"></script> -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<?php /*<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>*/ ?>
<!-- <script src="{{ url('/') }}/resources/assets/js/bootstrap.min.js"></script>
<script src="{{ url('/') }}/resources/assets/js/bootstrap-table.min.js"></script>
<script src="{{ url('/') }}/resources/assets/js/bootstrap-table-export.js"></script>
<script src="{{ url('/') }}/resources/assets/js/tableExport.js"></script>
<script src="{{ url('resources/assets/js/bootstrap-table-cookie.js') }}"></script> -->
<script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
<script src="{{ url('/') }}/assets/js/bootstrap-table.min.js"></script>
<script src="{{ url('/') }}/assets/js/bootstrap-table-export.js"></script>
<script src="{{ url('/') }}/assets/js/tableExport.js"></script>
<script src="{{ url('/assets/js/bootstrap-table-cookie.js') }}"></script>

<!-- <script src="{{ url('/') }}/resources/assets/js/bootstrap-table-multiple-search.js"></script> -->
<script src="{{ url('/') }}/assets/js/bootstrap-table-multiple-search.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>

<script>
    $(function(){
        $('.logout-link').click(function(event){
            event.preventDefault();
            localStorage.setItem('settingsEvents', JSON.stringify({}));
            document.getElementById('logout-form').submit();
            return false;
        })

    });


</script>

@yield('footerScript')
</body>
</html>