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

    <script>
        window.User = {!!  Auth::guest()? "null" : json_encode(Auth::user()) !!};
    </script>
</head>
<body>
<div id="app">
    <div class="ui fixed menu">
        <div class="ui container">
            <router-link to="/" exact-active-class="" class="header item">{{config('app.name', 'Laravel')}}</router-link>
            @include('layouts.nav')
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
                            <router-link :to="{'name':'oauth.authorized'}" exact-active-class="" class="item">OAuth2</router-link>
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
        @if (session('status'))
            <div class="ui success message">
                {{ session('status') }}
            </div>
        @endif
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
