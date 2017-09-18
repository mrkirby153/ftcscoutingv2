@extends('layouts.app')

@section('content')
    <div class="ui grid">
        <div class="ui row">
            <div class="three wide column"></div>
            <div class="ten wide column">
                <div class="ui segment">
                    <h2>Reset Password</h2>
                    <form class="ui form {{session('status')? 'success' : ''}}" method="POST" action="{{route('password.email')}}">
                        {{csrf_field()}}
                        <div class="ui success message">
                            <div class="header">Success!</div>
                            {{ session('status') }}
                        </div>
                        <div class="field {{$errors->has('email')? 'error' : ''}}">
                            <label for="email">E-Mail Address</label>
                            <input id="email" type="email" name="email" required autofocus value="{{old('email')}}"/>
                            <div class="form-error">
                                <p>{{$errors->first('email')}}</p>
                            </div>
                        </div>

                        <input type="submit" value="Send Password Reset Link" class="ui button"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
