<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'module',
        'action',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to filter by module
     */
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to filter by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to search permissions
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('display_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('module', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%");
        });
    }

    /**
     * Get formatted permission name
     */
    public function getFormattedNameAttribute()
    {
        return $this->display_name ?: $this->name;
    }

    /**
     * Get permission badge color based on action
     */
    public function getBadgeColorAttribute()
    {
        $colors = [
            'view' => 'bg-blue-100 text-blue-800',
            'create' => 'bg-green-100 text-green-800',
            'edit' => 'bg-yellow-100 text-yellow-800',
            'delete' => 'bg-red-100 text-red-800',
            'export' => 'bg-purple-100 text-purple-800',
            'import' => 'bg-indigo-100 text-indigo-800',
            'manage' => 'bg-gray-100 text-gray-800',
            'approve' => 'bg-emerald-100 text-emerald-800',
            'reject' => 'bg-orange-100 text-orange-800',
            'publish' => 'bg-cyan-100 text-cyan-800',
            'unpublish' => 'bg-pink-100 text-pink-800',
            'archive' => 'bg-slate-100 text-slate-800',
            'restore' => 'bg-teal-100 text-teal-800',
            'force_delete' => 'bg-rose-100 text-rose-800',
        ];

        return $colors[$this->action] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get module badge color
     */
    public function getModuleBadgeColorAttribute()
    {
        $colors = [
            'guru' => 'bg-blue-100 text-blue-800',
            'siswa' => 'bg-green-100 text-green-800',
            'osis' => 'bg-purple-100 text-purple-800',
            'lulus' => 'bg-yellow-100 text-yellow-800',
            'sarpras' => 'bg-orange-100 text-orange-800',
            'pages' => 'bg-indigo-100 text-indigo-800',
            'instagram' => 'bg-pink-100 text-pink-800',
            'users' => 'bg-gray-100 text-gray-800',
            'roles' => 'bg-red-100 text-red-800',
            'permissions' => 'bg-cyan-100 text-cyan-800',
            'settings' => 'bg-emerald-100 text-emerald-800',
            'reports' => 'bg-teal-100 text-teal-800',
            'audit' => 'bg-slate-100 text-slate-800',
            'dashboard' => 'bg-violet-100 text-violet-800',
            'profile' => 'bg-rose-100 text-rose-800',
        ];

        return $colors[$this->module] ?? 'bg-gray-100 text-gray-800';
    }
}
