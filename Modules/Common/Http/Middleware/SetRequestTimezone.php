<?php

namespace Modules\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reads the client's IANA timezone from the `X-Timezone` header so that
 * "today", "overdue" and statistics are computed in the user's local time.
 */
class SetRequestTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        $tz = $request->header('X-Timezone');

        $request->attributes->set(
            'timezone',
            is_string($tz) && in_array($tz, timezone_identifiers_list(), true) ? $tz : config('app.timezone'),
        );

        return $next($request);
    }
}
