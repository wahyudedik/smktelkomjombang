<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Superadmin bypass all permissions
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Check if user has permission using Spatie (also supports custom permissions)
        // ⚠️ hasPermissionTo() throws PermissionDoesNotExist when the permission is not
        // seeded yet — catch it and deny (403) instead of letting it bubble into a 500.
        try {
            if (!$user->hasPermissionTo($permission)) {
                abort(403, 'Insufficient permissions.');
            }
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist) {
            abort(403, 'Insufficient permissions.');
        }

        return $next($request);
    }
}
