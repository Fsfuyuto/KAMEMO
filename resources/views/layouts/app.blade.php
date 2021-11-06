<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kamemo') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="/css/layout.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                <img src="logo.jpg" class="logo" alt="KAMEMO"width="200" height="100">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- 3カラムに変更 --}}
        <main class="container p-4">
            <div class="row">
                <div class="col-sm-12 col-md-2 p-1">
                    <div class="card">
                        <!-- 左カラム -->
                        <div class="card-header">Tags</div>
                        <div class="card-body my-card-body">
                          <a href="/" class="card-text d-block text-secondary mb-2">Show All</a>
                    @foreach($tags as $tag)
                          <a href="/?tag={{$tag['id']}}" class="card-text text-success d-block elipsis mb-2">{{ $tag['name'] }}</a>
                    @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 p-1">
                    <div class="card">
                        <!-- 中央カラム -->
                        <div class="card-header d-flex justify-content-between">Memos<a href="{{ route('home') }}"><i class="fas fa-plus-circle" id="plus"></i></a></div>
                        <div class="card-body my-card-body">
                        <!-- 配列の数だけ表示 $memos(メモの塊)を1つずつ分解したのが$memo -->
                        @foreach($memos as $memo)
                        <!-- メモ一個一個のコンテンツを表示 -->
                            <a href="/edit/{{$memo['id']}}" class="card-text text-dark d-block">{{ $memo['content'] }}</a>
                        @endforeach
                        </div>
                    </div>
                </div>
                <!-- 右カラム -->
                <div class="col-sm-12 col-md-4 p-1">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>