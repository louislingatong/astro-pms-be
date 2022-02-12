@component('mail::message')
    Hi {{ $user->first_name }},<br/>
    <br/>
    Your account has been successfully created.<br/>

    @component('mail::button', ['url' => $url])
        Activate Account
    @endcomponent

    <br/>
    Best Regards,<br/>
    {{ config('mail.from.name') }}<br/>
@endcomponent
