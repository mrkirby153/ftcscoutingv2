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
        window.commit_hash = @if($git_hash != null)"{{$git_hash}}";
        @else null @endif;
        window.env = "{{App::environment()}}";
        @if(env('SENTRY_JS'))
        window.sentryJs = "{{env('SENTRY_JS')}}"
        @endif
    </script>
</head>
<body>
<div id="app">
    <div class="ui fixed menu">
        <div class="ui container">
            <router-link to="/" exact-active-class=""
                         class="header item">{{config('app.name', 'Laravel')}}</router-link>
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
                            <router-link :to="{'name':'oauth.authorized'}" exact-active-class="" class="item">OAuth2
                            </router-link>

                            <router-link :to="{name:'admin.users'}" class="item" v-if="user && user.admin">User List</router-link>
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
            <div class="ui {{session('status-type', 'success')}} message">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </div>

    <div class="ui footer" id="footer">
        <div class="ui container">
            <p>
                FTC Scouting @if($git_hash != null)- {{$git_hash}}@endif<br/>
                Copyright &copy; 2016-2018<br/>
                Made with <i class="heart icon"></i> by <a href="https://www.mrkirby153.com">Austin Whyte</a><br/>
                <a href="https://github.com/mrkirby153/ftcscoutingv2" target="_blank"><i class="github icon"></i></a>
            </p>
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
