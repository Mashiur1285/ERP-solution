<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::all();

        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['description' => 'Full access', 'is_active' => true]
        );
        $admin->syncPermissions($allPermissions);

        $managerPermissions = $allPermissions->filter(fn ($permission) => ! str_contains($permission->name, '.delete'));
        $manager = Role::firstOrCreate(
            ['name' => 'manager', 'guard_name' => 'web'],
            ['description' => 'Operational access without delete', 'is_active' => true]
        );
        $manager->syncPermissions($managerPermissions);

        $viewerPermissions = $allPermissions->filter(fn ($permission) => str_ends_with($permission->name, '.view'));
        $viewer = Role::firstOrCreate(
            ['name' => 'viewer', 'guard_name' => 'web'],
            ['description' => 'Read only access', 'is_active' => true]
        );
        $viewer->syncPermissions($viewerPermissions);
    }
}
