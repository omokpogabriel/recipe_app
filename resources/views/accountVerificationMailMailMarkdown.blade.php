@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

your verification email is : {{$user->verification_token}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
