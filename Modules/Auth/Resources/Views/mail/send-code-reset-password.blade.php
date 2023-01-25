<x-mail::message>
# Your account password recovery code in {{ config('app.name') }}

This email is at your request to recover the password on the site {{ config('app.name') }} Sent to you.

<x-mail::panel>
    Your password recovery code: {{ $code }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
