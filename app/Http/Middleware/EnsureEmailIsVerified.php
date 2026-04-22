<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // If user is not authenticated, continue
        if (!$user) {
            return $next($request);
        }

        // Superadmin created users don't need email verification
        if ($user->isVerifiedByAdmin()) {
            return $next($request);
        }

        // If user has verified email, continue
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // If user needs email verification, redirect to verification notice
        if ($user->needsEmailVerification()) {
            // Don't redirect if already on verification pages
            if (!$request->is('email/verify*') && !$request->is('verification*')) {
                return redirect()->route('verification.notice');
            }
        }

        return $next($request);
    }
}
