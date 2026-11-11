<?php

use Illuminate\Support\Carbon;

if (!function_exists('client_timezone')) {
    /**
     * The timezone sent by the client (X-Timezone header), or the app timezone.
     */
    function client_timezone(): string
    {
        return request()?->attributes->get('timezone') ?? config('app.timezone');
    }
}

if (!function_exists('client_now')) {
    /**
     * "Now" in the client's timezone.
     */
    function client_now(): Carbon
    {
        return now(client_timezone());
    }
}
