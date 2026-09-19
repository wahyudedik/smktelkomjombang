<?php

namespace App\Traits;

/**
 * Trait untuk authorization common di semua attendance controllers.
 *
 * Menyediakan method `requireAdminOrPermission()` yang mengizinkan akses
 * jika user memiliki role admin/superadmin ATAU memiliki permission spesifik.
 */
trait AttendanceAuthorization
{
    /**
     * Cek apakah user adalah admin/superadmin atau memiliki permission tertentu.
     * Abort 403 jika tidak memenuhi.
     */
    protected function requireAdminOrPermission(string $permission): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($user->hasAnyRole(['admin', 'superadmin'])) {
            return;
        }

        if ($user->hasRole('guru') && in_array($permission, ['attendance.view'])) {
            return;
        }

        if ($user->can($permission)) {
            return;
        }

        abort(403);
    }
}
