<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body >

    <div id="app">
        <nav class="w100 px_4 py_1 bc_milky flex_row justify_between item_center">
                <!-- Left Side Of Navbar -->
            <div>
                <a href="{{ url('/') }}" class="no_decoration text_xl">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

                <!-- Right Side Of Navbar -->
            <div>
                <ul class="flex_row no_list_style">
                    <!-- Authentication Links -->
                    @guest
                        <li class="mx_1">
                            <a class="no_decoration" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="mx_1">
                            <a class="no_decoration" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="mx_1">
                            <a class="no_decoration mr_2" href="/projects">
                                projects
                            </a>
                            <a class="no_decoration mr_2" href="{{route('home')}}">
                                {{ __(Auth::user()->name) }}
                            </a>
                        
                            <a class="no_decoration" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
