<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionMap = [
            'dashboard' => ['view'],
            'supplier' => ['view', 'add', 'update'],
            'deposit' => ['view', 'add', 'update'],
            'category' => ['view', 'add', 'update', 'delete'],
            'brand' => ['view', 'add', 'update', 'delete'],
            'lift' => ['view', 'add', 'update'],
            'shop' => ['view', 'add', 'update', 'delete'],
            'sales' => ['view', 'add', 'update'],
            'expense' => ['view', 'add', 'update'],
            'inventory' => ['view'],
            'role' => ['view', 'add', 'update', 'delete'],
            'user' => ['view', 'add', 'update'],
        ];

        foreach ($permissionMap as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
