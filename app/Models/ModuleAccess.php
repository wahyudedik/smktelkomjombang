<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleAccess extends Model
{
    use HasFactory;

    protected $table = 'module_access';

    protected $fillable = [
        'user_id',
        'module_name',
        'can_access',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    protected $casts = [
        'can_access' => 'boolean',
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    /**
     * Get the user that owns the module access.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can perform specific action on module.
     */
    public function can(string $action): bool
    {
        return $this->can_access && $this->{"can_{$action}"};
    }

    /**
     * Scope to filter by module.
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module_name', $module);
    }

    /**
     * Scope to filter accessible modules.
     */
    public function scopeAccessible($query)
    {
        return $query->where('can_access', true);
    }
}
