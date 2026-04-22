<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'email_verification_token',
        'is_verified_by_admin',
        'email_verification_sent_at',
        'locale',
        'currency',
        'timezone',
        'unit_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_sent_at' => 'datetime',
            'is_verified_by_admin' => 'boolean',
            'password' => 'hashed',
        ];
    }


    /**
     * Get the module access for the user.
     */
    public function moduleAccess(): HasMany
    {
        return $this->hasMany(ModuleAccess::class);
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get the push subscriptions for the user
     */
    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    /**
     * Check if user has specific permission using Spatie.
     */
    public function hasPermission(string $permission): bool
    {
        // Superadmin bypass all permissions
        if ($this->hasRole('superadmin')) {
            return true;
        }

        return $this->hasPermissionTo($permission);
    }

    /**
     * Check if user has module access.
     */
    public function hasModuleAccess(string $module): bool
    {
        // Superadmin bypass all module access
        if ($this->hasRole('superadmin')) {
            return true;
        }

        return $this->moduleAccess()
            ->where('module_name', $module)
            ->where('can_access', true)
            ->exists();
    }

    /**
     * Check if user can perform specific action on module.
     */
    public function canPerform(string $action, string $module): bool
    {
        // Superadmin bypass all actions
        if ($this->hasRole('superadmin')) {
            return true;
        }

        // Check Spatie permission first
        $permission = "{$module}.{$action}";
        if ($this->hasPermissionTo($permission)) {
            return true;
        }

        // Fallback to module access system
        return $this->moduleAccess()
            ->where('module_name', $module)
            ->where('can_access', true)
            ->where("can_{$action}", true)
            ->exists();
    }

    /**
     * Check if user is superadmin.
     */
    public function isSuperadmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is guru.
     */
    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    /**
     * Check if user is siswa.
     */
    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
    }

    /**
     * Check if user is sarpras.
     */
    public function isSarpras(): bool
    {
        return $this->hasRole('sarpras');
    }

    /**
     * Get user's accessible modules.
     */
    public function getAccessibleModules(): array
    {
        if ($this->isSuperadmin()) {
            return ['instagram', 'pages', 'guru', 'siswa', 'osis', 'lulus', 'sarpras', 'users'];
        }

        return $this->moduleAccess()
            ->where('can_access', true)
            ->pluck('module_name')
            ->toArray();
    }

    /**
     * Check if email is verified.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'email_verification_token' => null,
        ])->save();
    }

    /**
     * Check if user was verified by admin.
     */
    public function isVerifiedByAdmin(): bool
    {
        return $this->is_verified_by_admin;
    }

    /**
     * Mark user as verified by admin.
     */
    public function markAsVerifiedByAdmin(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'is_verified_by_admin' => true,
            'email_verification_token' => null,
        ])->save();
    }

    /**
     * Generate email verification token.
     */
    public function generateEmailVerificationToken(): string
    {
        $token = Str::random(64);

        $this->forceFill([
            'email_verification_token' => $token,
            'email_verification_sent_at' => $this->freshTimestamp(),
        ])->save();

        return $token;
    }

    /**
     * Verify email with token.
     */
    public function verifyEmailWithToken(string $token): bool
    {
        if ($this->email_verification_token !== $token) {
            return false;
        }

        return $this->markEmailAsVerified();
    }

    /**
     * Check if user needs email verification.
     */
    public function needsEmailVerification(): bool
    {
        // Superadmin created users don't need verification
        if ($this->isVerifiedByAdmin()) {
            return false;
        }

        // Already verified users don't need verification
        return !$this->hasVerifiedEmail();
    }

    /**
     * Get the email verification URL.
     */
    public function getEmailVerificationUrl(): string
    {
        return route('verification.verify', [
            'id' => $this->getKey(),
            'hash' => sha1($this->getEmailForVerification()),
            'token' => $this->email_verification_token,
        ]);
    }

    /**
     * Get the email for verification.
     */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }
}
