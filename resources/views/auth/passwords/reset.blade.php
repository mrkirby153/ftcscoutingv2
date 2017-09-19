@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="three wide column"></div>
        <div class="ten wide column">
            <div class="ui segment">
                <h2>Reset Password</h2>

                <form class="ui form" method="POST" action="{{route('password.request')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="token" value="{{$token}}"/>

                    <div class="field {{$errors->has('email')? 'error' : ''}}">
                        <label for="email">E-Mail Address</label>
                        <input type="email" id="email" name="email" value="{{$email or old('email')}}" required
                               autofocus/>
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

                    <div class="field {{$errors->has('password_confirmation')? 'has-error': ''}}">
                        <label for="password-confirm">Confirm Password</label>
                        <input type="password" id="password-confirm" name="password_confirmation" required>

                        <div class="form-errors">
                            <p>{{$errors->first('password_confirmation')}}</p>
                        </div>
                    </div>

                    <input type="submit" class="ui button" value="Reset Password">
                </form>
            </div>
        </div>
    </div>
@endsection
