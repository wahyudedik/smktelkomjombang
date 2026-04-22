<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

abstract class TestCase extends BaseTestCase
{
    /**
     * Helper method to get or create role with guard_name
     */
    protected function getOrCreateRole(string $name, string $guardName = 'web'): Role
    {
        return Role::updateOrCreate(
            ['name' => $name],
            ['guard_name' => $guardName]
        );
    }

    /**
     * Helper method to get or create permission with guard_name
     */
    protected function getOrCreatePermission(string $name, string $guardName = 'web'): Permission
    {
        return Permission::updateOrCreate(
            ['name' => $name],
            ['guard_name' => $guardName]
        );
    }
}
