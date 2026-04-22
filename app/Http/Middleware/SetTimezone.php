<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: 1. Session, 2. User preference, 3. Default
        // Session MUST be prioritized over User preference to allow runtime switching
        $timezone = Session::get('timezone');

        // Only check user preference if no session timezone exists
        if (!$timezone && Auth::check() && Auth::user()) {
            $timezone = Auth::user()->timezone ?? null;
        }

        // Fallback to default if no session or user preference
        if (!$timezone) {
            $timezone = config('i18n.default_timezone', 'Asia/Jakarta');
        }

        // Validate timezone
        try {
            new \DateTimeZone($timezone);
            date_default_timezone_set($timezone);
            // Always update session to persist the selection
            Session::put('timezone', $timezone);
        } catch (\Exception $e) {
            // Use default if invalid
            $timezone = config('i18n.default_timezone', 'Asia/Jakarta');
            date_default_timezone_set($timezone);
            Session::put('timezone', $timezone);
        }

        return $next($request);
    }
}
