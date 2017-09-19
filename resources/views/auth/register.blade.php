@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="three wide column">
        </div>
        <div class="ten wide column">
            <div class="ui segment">
                <h2>Register</h2>

                <form action="{{route('register')}}" class="ui form" method="POST">
                    {{csrf_field()}}
                    <div class="field {{$errors->has('name')? 'error':''}}">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="{{old('name')}}" required autofocus>
                        <div class="form-error">
                            <p>{{$errors->first('name')}}</p>
                        </div>
                    </div>

                    <div class="field {{$errors->has('email')? 'error':''}}">
                        <label for="email">E-Mail Address</label>
                        <input type="email" name="email" id="email" value="{{old('email')}}" required>
                        <div class="form-error">
                            <p>{{$errors->first('email')}}</p>
                        </div>
                    </div>

                    <div class="field {{$errors->has('password')? 'error':''}}">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <div class="form-error">
                            <p>{{$errors->first('password')}}</p>
                        </div>
                    </div>

                    <div class="field {{$errors->has('password_confirmation')? 'error':''}}">
                        <label for="password-confirm">Password</label>
                        <input type="password" id="password-confirm" name="password_confirmation" required>
                        <div class="form-error">
                            <p>{{$errors->first('password_confirmation')}}</p>
                        </div>
                    </div>

                    <input type="submit" class="ui button" value="Register"/>
                </form>
            </div>
        </div>
        <div class="three wide column"></div>
    </div>
@endsection
