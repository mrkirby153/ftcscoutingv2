@component('mail::message')
# Hello,

You have been invited by **{{$inviter->name}}** to join the FTC Scouting team `{{$team->team_number}}: {{$team->name}}`

{{--If you do not have an account, please <a href="{{route('register').'?email='.$invite->user_email.'&invite='.$invite->id}}">Click Here</a> to create one.--}}

@component('mail::button', ['url' => route('invite.accept', ['member' => $invite->id])])
Accept Invite
@endcomponent

@endcomponent
