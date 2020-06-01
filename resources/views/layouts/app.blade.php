<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('pandawa/js/mix-all.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('pandawa/css/mix-all.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/home') }}">
                <img src="{{ asset('images/logo.png') }}" height="100%">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="@isset($curMenu) @if($curMenu == 'dashboard') active @endif @endisset"><a href="{{ url('/home') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                @if($menus)
                    @foreach($menus as $key => $val)
                        <li class="@isset($curMenu) @if($curMenu == $val->ctrl_url) active @endif @endisset"><a href="{{ url($val->ctrl_url) }}">{!! $val->ctrl_icon !!}&nbsp;{{ $val->ctrl_label }}</a></li>
                    @endforeach
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a class="" href="{{ url('user/'.Auth::user()->id) }}">{{ Auth::user()->name }}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a onclick="$('#logout-form').submit();return false" href="{{ route('logout') }}">Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div style="padding-top:70px">
    @yield('content')
</div>

<div class="footer">
    Copyright Pandawa {{ date('Y') }}
</div>
</body>
</html>
