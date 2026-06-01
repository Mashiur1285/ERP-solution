<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'shamim@admin.com'],
            [
                'name' => 'Shamim Ahmed',
                'password' => bcrypt('sifatsara84'),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        User::query()
            ->with('roles')
            ->get()
            ->filter(fn (User $user) => $user->roles->isEmpty())
            ->each(function (User $user) {
                $user->assignRole('admin');
            });
    }
}
