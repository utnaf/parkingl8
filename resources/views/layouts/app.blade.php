<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @if (isset($loadApp) && $loadApp)
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @if (isset($config))
    <script>
        window.config = @json($config);
    </script>
    @endif
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('issues') }}">
                                {{ __('general.issues_list') }}
                                @if (isset($issuesCount) && $issuesCount > 0)
                                    <span class="badge badge-danger">{{ $issuesCount }}</span>
                                @endif
                            </a>
                        </li>
                        @endif
                        @if (Auth::check() && Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.list') }}">
                                {{ __('general.users_list') }}
                            </a>
                        </li>
                        @endif
                    </ul>

                    @if (Auth::check())
                    <form class="form-inline my-2 my-lg-0"
                        onsubmit="event.preventDefault(); window.location.href='/dashboard#/entry/' + event.target.searchId.value">
                        <input class="form-control mr-sm-2" type="search" name="searchId" placeholder="{{ __('frontend.ticketId') }}" aria-label="{{ __('frontend.ticketId') }}">
                        <button class="btn btn-primary my-2 my-sm-0" type="submit">{{ __('general.search') }}</button>
                    </form>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-2">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('locale', App::getLocale() === 'en' ? 'it' : 'en') }}">
                                    {{ App::getLocale() === 'en' ? 'IT' : 'EN' }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
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
    </div>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119240810-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-119240810-4');
    </script>
</body>
</html>
