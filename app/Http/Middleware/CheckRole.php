<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Map route role requirements to equivalent permissions.
     * Custom roles with these permissions can bypass role checks.
     */
    protected array $rolePermissionMap = [
        'guru'     => ['guru.view', 'guru.read', 'siswa.view', 'siswa.read', 'jadwal.view', 'jadwal.read', 'attendance.view'],
        'admin'    => ['users.view', 'users.create', 'pages.view', 'events.view', 'berita.view'],
        'sarpras'  => ['sarpras.view', 'sarpras.read', 'sarpras.create'],
        'osis'     => ['osis.view', 'osis.read', 'osis.create'],
    ];

    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Superadmin bypass all role checks
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Support multiple roles separated by |
        $allowedRoles = array_map('trim', explode('|', $role));

        // 1. Check if user has one of the required roles directly
        foreach ($allowedRoles as $allowedRole) {
            if ($user->hasRole($allowedRole)) {
                return $next($request);
            }
        }

        // 2. Fallback: check if user has any permission associated with the required roles
        // This allows custom roles (e.g. "media", "taianjing") to access routes
        // if they have the relevant permissions assigned
        foreach ($allowedRoles as $allowedRole) {
            $mappedPermissions = $this->rolePermissionMap[$allowedRole] ?? [];
            foreach ($mappedPermissions as $permission) {
                if ($user->hasPermissionTo($permission)) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
