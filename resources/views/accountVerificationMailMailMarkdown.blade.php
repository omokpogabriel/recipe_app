@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

your verification email is : http://127.0.0.1:8000/api/v1/verify_account/{{$user->verification_token}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
