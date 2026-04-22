<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Barang;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelulusan;
use App\Models\Page;
use App\Models\AuditLog;
use App\Models\JadwalPelajaran;
use App\Policies\UserPolicy;
use App\Policies\SarprasPolicy;
use App\Policies\OSISPolicy;
use App\Policies\PemilihPolicy;
use App\Policies\SystemPolicy;
use App\Policies\SiswaPolicy;
use App\Policies\GuruPolicy;
use App\Policies\KelulusanPolicy;
use App\Policies\PagePolicy;
use App\Policies\AuditLogPolicy;
use App\Policies\JadwalPelajaranPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Barang::class => SarprasPolicy::class,
        Calon::class => OSISPolicy::class,
        Pemilih::class => PemilihPolicy::class,
        Siswa::class => SiswaPolicy::class,
        Guru::class => GuruPolicy::class,
        Kelulusan::class => KelulusanPolicy::class,
        Page::class => PagePolicy::class,
        AuditLog::class => AuditLogPolicy::class,
        JadwalPelajaran::class => JadwalPelajaranPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for system-level permissions
        Gate::define('accessAdminPanel', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('manageRolesAndPermissions', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('viewAnalytics', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('system.analytics');
        });

        Gate::define('viewSystemHealth', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('system.health');
        });

        Gate::define('viewNotifications', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('system.notifications');
        });

        Gate::define('manageUsers', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('manageSarpras', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('sarpras.view');
        });

        Gate::define('manageOSIS', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('osis.view');
        });

        Gate::define('managePages', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('pages.view');
        });

        Gate::define('manageInstagram', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('instagram.view');
        });

        Gate::define('manageSettings', function (User $user) {
            return $user->hasRole('superadmin') || $user->can('settings.view');
        });
    }
}
