<h1>Hey {{$invited}}!!  Congrats🎆🎇✨🎉</h1>
<h3>You have been invited to join company {{$company}}</h3>
<br>
you can validate the invitation by click in the link below<br>
<a href="http://localhost:8000/register-employee?admin_id={{$user->id}}&company_id={{$company_id}}&name={{$invited}}&email={{$invitedMail}}&invite_id={{$invitation_id}}">Click here to accept</a><br><br>
Best Regards<br>
{{ $user->name }}
