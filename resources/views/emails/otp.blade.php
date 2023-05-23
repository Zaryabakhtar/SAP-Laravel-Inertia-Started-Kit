@component('mail::message')
# Introduction

You OTP Is: {{ $otp }}

    

Thanks,<br>
{{ config('app.name') }}
@endcomponent
