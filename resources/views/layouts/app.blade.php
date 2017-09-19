<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css"
          integrity="sha256-5+W3JHnvGYIJkVxUBsw+jBi9+pOlu9enPX3vZapXj5M=" crossorigin="anonymous"/>

    <script>
        window.User = {!!  Auth::guest()? "" : json_encode(Auth::user()) !!};
    </script>
</head>
<body>
<div id="app">
    <div class="ui fixed menu">
        <div class="ui container">
            <a href="{{url('/')}}" class="header item">
                {{config('app.name', 'Laravel')}}
            </a>
            <router-link to="/test" class="header item">Test</router-link>
            <div class="right menu">
                @guest
                    <a href="{{route('register')}}" class="item">Register</a>
                    <a href="{{route('login')}}" class="item">Log In</a>
                @endguest
                @auth
                    <div class="ui dropdown item">
                        {{Auth::user()->name}}
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();" class="item">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="ui container">
        <div class="ui grid">
            @yield('content')
        </div>
    </div>
</div>

{{-- Ziggy Blade Directive for Named Routes --}}
@routes

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"
        integrity="sha256-97Q90i72uoJfYtVnO2lQcLjbjBySZjLHx50DYhCBuJo=" crossorigin="anonymous"></script>
</body>
</html>
