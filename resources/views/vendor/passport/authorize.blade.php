<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Authorization</title>

    <!-- Styles -->
    <link href="{{mix('css/app.css')}}" rel="stylesheet">

    <style>
        .passport-authorize .container {
            margin-top: 30px;
        }

        .passport-authorize .scopes {
            margin-top: 20px;
        }

        .passport-authorize .buttons {
            margin-top: 25px;
            text-align: center;
        }

        .passport-authorize form {
            display: inline;
        }
    </style>
</head>
<body class="passport-authorize">

<div class="ui container">
    <div class="ui grid">
        <div class="row">
            <div class="five wide column">

            </div>
            <div class="six wide column">
                <div class="ui top attached header">
                    Authorization Request
                </div>
                <div class="ui attached segment">
                    <p><strong>{{$client->name}}</strong> is requesting permission to access your account.</p>

                    @if(count($scopes) > 0)
                        <div class="scopes">
                            <p><Strong>This application will be able to:</Strong></p>
                            <ul>
                                @foreach($scopes as $scope)
                                    <li>{{$scope->description}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="buttons">
                        <!-- Authorize Button -->
                        <form method="post" action="/oauth/authorize">
                            {{ csrf_field() }}

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <button type="submit" class="ui green button">Authorize</button>
                        </form>

                        <!-- Cancel Button -->
                        <form method="post" action="/oauth/authorize">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <button class="ui red button">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
