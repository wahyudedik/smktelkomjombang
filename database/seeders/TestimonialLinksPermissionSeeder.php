<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestimonialLinksPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign testimonial-links permissions to admin and superadmin roles
        $adminRole = Role::where('name', 'admin')->first();
        $superadminRole = Role::where('name', 'superadmin')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo([
                'testimonial-links.view',
                'testimonial-links.create',
                'testimonial-links.edit',
                'testimonial-links.delete'
            ]);
            $this->command->info('Admin role permissions assigned successfully');
        }

        if ($superadminRole) {
            // Superadmin already has all permissions from PermissionSeeder
            // Just ensure testimonial-links permissions are included
            $superadminRole->givePermissionTo([
                'testimonial-links.view',
                'testimonial-links.create',
                'testimonial-links.edit',
                'testimonial-links.delete'
            ]);
            $this->command->info('Superadmin role permissions assigned successfully');
        }
    }
}
