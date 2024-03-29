<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script>
        var APP_URLS = '{{ config('app.url') }}';
        var csrf_token = '{{ csrf_token() }}'
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/api.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            @guest
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" height="20px">
                </a>
            @else
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" height="20px">
                </a>
            @endguest
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mr-auto">
                    @if(Auth::check())
                        @if($menus)
                            @foreach($menus as $key => $menu)
                                @if($menu->ctrl_url=='admin-customer' && $menu->func_url == 'jenis-layanan')
                                    <li class="nav-item @if($curMenu == $menu->ctrl_url) active @endif">
                                        <a class="nav-link" href="{{ url($menu->ctrl_url) }}/jenis-layanan">{{ $menu->ctrl_label }}</a>
                                    </li>
                                @elseif($menu->ctrl_url=='admin-customer' && $menu->func_url == 'index')
                                    <li class="nav-item @if($curMenu == $menu->ctrl_url) active @endif">
                                        <a class="nav-link" href="{{ url($menu->ctrl_url) }}">{{ $menu->ctrl_label }}</a>
                                    </li>
                                @elseif($menu->ctrl_url == 'admin-kas')
                                    <li class="nav-item dropdown @if($curMenu == $menu->ctrl_url) active @endif">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ $menu->ctrl_label }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ url($menu->ctrl_url) }}">Data {{ $menu->ctrl_label }}</a>
                                            <a class="dropdown-item" href="{{ url('admin-kas/pengeluaran-rutin') }}">Pengeluaran Rutin</a>
                                        </div>
                                    </li>
                                @else
                                    <li class="nav-item @if($curMenu == $menu->ctrl_url) active @endif">
                                        <a class="nav-link" href="{{ url($menu->ctrl_url) }}">{{ $menu->ctrl_label }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </ul>

                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('user.profile') }}">Profil</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <div class="footer">
        {{ companyInfo()->site_name }}
        <div class="float-right text-right">
            App Version : {{ config('app.version') }}<br>
            DB Version : {{ appVersion() }}<br>
            Framework Version : {{ app()::VERSION }}
        </div>
    </div>
</div>
</body>
</html>
