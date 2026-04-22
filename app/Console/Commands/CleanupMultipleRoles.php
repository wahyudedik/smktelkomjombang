<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CleanupMultipleRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:cleanup-multiple 
                            {--dry-run : Show what would be changed without making changes}
                            {--fix : Actually fix users with multiple roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and fix users with multiple roles (ensure one user = one role)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $fix = $this->option('fix');

        if (!$dryRun && !$fix) {
            $this->error('Please specify --dry-run or --fix');
            return 1;
        }

        $this->info('Scanning users for multiple roles...');
        $this->newLine();

        $usersWithMultipleRoles = User::with('roles')
            ->get()
            ->filter(function ($user) {
                return $user->roles->count() > 1;
            });

        if ($usersWithMultipleRoles->isEmpty()) {
            $this->info('✅ No users with multiple roles found. System is clean!');
            return 0;
        }

        $this->warn("Found {$usersWithMultipleRoles->count()} user(s) with multiple roles:");
        $this->newLine();

        foreach ($usersWithMultipleRoles as $user) {
            $roles = $user->roles->pluck('name')->toArray();
            $this->line("User: {$user->name} ({$user->email})");
            $this->line("  Current Roles: " . implode(', ', $roles));

            if ($dryRun) {
                // Keep first role (primary role)
                $primaryRole = $user->roles->first();
                $this->line("  → Would keep: {$primaryRole->name}");
            } elseif ($fix) {
                // Keep first role (primary role)
                $primaryRole = $user->roles->first();
                $user->syncRoles([$primaryRole]);

                // Roles are managed by Spatie Permission - no need to sync user_type
                $this->line("  ✅ Fixed: Now has only role '{$primaryRole->name}'");
            }
            $this->newLine();
        }

        if ($dryRun) {
            $this->info('This was a dry run. Use --fix to actually apply changes.');
        } elseif ($fix) {
            $this->info('✅ All users with multiple roles have been fixed!');
        }

        return 0;
    }
}
