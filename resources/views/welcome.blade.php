<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
    </head>
    <body>
    <div class="container-fluid bg-blue">
                <h1 class="text-center">Renginių kalendorius</h1>
                <h5 class="text-center">Kompiuterių tinklai ir informacinės technologijos</h5>
                <h5 class="text-center">IT projektas</h5>
            </div>
        <nav class="navbar navbar-default">
            @if (Route::has('login'))
            <ul class="nav navbar-nav">
                    @auth
                        <li><a href="{{ url('/namai') }}">Namai</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Prisijungti</a></li>

                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">Registruotis</a></li>
                        @endif
                        <li><a href="{{ url('/renginiai') }}">Peržiūrėti renginius</a></li>
                    @endauth
                </ul>
            @endif
        </nav>
        @guest
            <div class="container">
                <h1 class="text-center">Jūs lankotės kaip svečias</h1>
            </div>
        @endguest
    </body>
</html>
