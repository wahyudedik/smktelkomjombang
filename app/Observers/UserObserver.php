<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    // Observer methods removed - no longer syncing user_type
    // All role management is now handled by Spatie Permission package
}
