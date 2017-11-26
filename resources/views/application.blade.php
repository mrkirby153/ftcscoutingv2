@extends('layouts.app')

@section('content')
    @impersonating
    <div class="ui warning message">
        <div class="header">
            Impersonation active
        </div>
        <p>You are currently impersonating a user. <a href="{{ route('impersonate.leave') }}">Leave impersonation</a></p>
    </div>
    @endImpersonating
    <router-view></router-view>
@endsection