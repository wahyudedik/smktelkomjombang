<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    protected static function bootAuditable()
    {
        // Log create events
        static::created(function ($model) {
            $model->auditLog('create', null, $model->getAuditableAttributes());
        });

        // Log update events
        static::updated(function ($model) {
            $model->auditLog('update', $model->getOriginal(), $model->getAuditableAttributes());
        });

        // Log delete events
        static::deleted(function ($model) {
            $model->auditLog('delete', $model->getAuditableAttributes(), null);
        });
    }

    /**
     * Create audit log entry.
     */
    protected function auditLog(string $action, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Skip if user is not authenticated (for seeders, etc)
        if (!Auth::check()) {
            return;
        }

        // Get request data
        $request = request();

        AuditLog::createLog(
            action: $action,
            userId: Auth::id(),
            modelType: get_class($this),
            modelId: $this->id ?? null,
            oldValues: $oldValues ? $this->filterAuditableFields($oldValues) : null,
            newValues: $newValues ? $this->filterAuditableFields($newValues) : null,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );
    }

    /**
     * Get auditable attributes (override in model if needed).
     */
    protected function getAuditableAttributes(): array
    {
        // Get all attributes except timestamps and sensitive data
        $attributes = $this->getAttributes();

        // Remove common non-auditable fields
        $exclude = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

        return array_diff_key($attributes, array_flip($exclude));
    }

    /**
     * Filter auditable fields (override in model for custom filtering).
     */
    protected function filterAuditableFields(array $attributes): array
    {
        // Remove password and other sensitive fields
        $exclude = ['password', 'remember_token', 'api_token'];

        return array_diff_key($attributes, array_flip($exclude));
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return AuditLog::forModel(get_class($this), $this->id)
            ->with('user')
            ->latest()
            ->get();
    }
}
