<x-mail::message>
# Greetings {{ $user->name }},

<center>
    Use this OTP to continue and to not share to other. <br>
    {{ $user->otp }}
</center>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
