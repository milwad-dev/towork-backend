<x-mail::message>
# {{ $purpose->subject() }}

{{ $intro }}

<x-mail::panel>
<div style="font-size: 28px; font-weight: 700; letter-spacing: 8px; text-align: center;">{{ $code }}</div>
</x-mail::panel>

This code expires in {{ $minutes }} minutes. If you didn't request it, you can safely ignore this e-mail.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
