<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Renginių kaledorius') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web-animations/2.3.1/web-animations.min.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <script src="{{ asset('js/javascript.js') }}" defer></script>


</head>
<body>
    <div id="app">
        <div class="container-fluid bg-blue">
            <h1 class="text-center">Renginių kalendorius</h1>
            <h5 class="text-center">Kompiuterių tinklai ir informacinės technologijos</h5>
            <h5 class="text-center">IT projektas</h5>
        </div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Prisijungti') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Registruotis') }}</a></li>
                            <li><a class="nav-link" href="{{ url('/renginiai') }}">{{ __('Peržiūrėti renginius') }}</a></li>
                        @else
                        @if(!empty(Auth::user()->getRoleNames()))
                                    @foreach(Auth::user()->getRoleNames() as $v)
                                        @if($v == 'Administratorius')
                                        <li><a class="nav-link" href="{{ route('events.index') }}">{{ __('Peržiūrėti renginius') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.order') }}">{{ __('Užsisakyti pranešimą') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.orderInformation') }}">{{ __('Peržiūrėti užsakymą') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.create') }}">{{ __('Įkelti naują renginį') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('users.index') }}">{{ __('Administratoriaus sąsaja') }}</a></li>
                                        @endif

                                        @if($v == 'VIP vartotojas')
                                        <li><a class="nav-link" href="{{ route('events.index') }}">{{ __('Peržiūrėti renginius') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.order') }}">{{ __('Užsisakyti pranešimą') }}</a></li>
                                                <li><a class="nav-link" href="{{ route('events.orderInformation') }}">{{ __('Peržiūrėti užsakymą') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.create') }}">{{ __('Įkelti naują renginį') }}</a></li>
                                        @endif

                                        @if($v == 'Paprastas vartotojas')
                                        <li><a class="nav-link" href="{{ route('events.index') }}">{{ __('Peržiūrėti renginius') }}</a></li>
                                        <li><a class="nav-link" href="{{ route('events.order') }}">{{ __('Užsisakyti pranešimą') }}</a></li>
                                                <li><a class="nav-link" href="{{ route('events.orderInformation') }}">{{ __('Peržiūrėti užsakymą') }}</a></li>
                                            @endif
                                    @endforeach
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>


                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Atsijungti') }}
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
            <div class="container">
            @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
