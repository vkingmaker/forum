@component('mail::message')
# One last step

We just need you to confirm your email address to prove that you are a human. You get it right? Coo.

The body of your message.

@component('mail::button', ['url' => url('/register/confirm?token='.$user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
