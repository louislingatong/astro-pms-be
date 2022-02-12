@component('mail::message')
    Hi {{ $user->first_name }},<br/>
    <br/>
    You told us you forgot your password. If you really did, click the button below to update your password.<br/>

    @component('mail::button', ['url' => $url])
        Choose a New Password
    @endcomponent

    If you didn’t mean to reset your password, then you can just ignore this email; your password will not change.

    <br/>
    Best Regards,<br/>
    {{ config('mail.from.name') }}<br/>
@endcomponent
