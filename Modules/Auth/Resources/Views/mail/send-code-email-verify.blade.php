<x-mail::message>
# Your account verification code in {{ config('app.name') }}

This email is at your request to verify account on the site {{ config('app.name') }} Sent to you.

<x-mail::panel>
    Your verification code: {{ $code }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
