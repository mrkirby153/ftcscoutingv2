@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="three wide column">
        </div>
        <div class="ten wide column">
            <div class="ui segment">
                <h2>Log In</h2>
                <form class="ui form" method="POST" action="{{route('login')}}">
                    {{csrf_field()}}
                    <div class="field {{$errors->has('email')? 'error' : ''}}">
                        <label for="email">E-Mail Address</label>
                        <input type="email" id="email" name="email" required autofocus value="{{old('email')}}"/>
                        <div class="form-error">
                            <p>{{$errors->first('email')}}</p>
                        </div>
                    </div>
                    <div class="field {{$errors->has('password')? 'error' : ''}}">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required/>

                        <div class="form-error">
                            <p>{{$errors->first('password')}}</p>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="remember">
                            <label>Remember Me</label>
                        </div>
                    </div>
                    <input type="submit" value="Log In" class="ui button"/>
                </form>

                <a href="{{route('password.request')}}">Forgot your password?</a>
            </div>
        </div>
        <div class="three wide column"></div>
    </div>
@endsection
