<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (AdminPermissions::all() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::query()->firstOrCreate(
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ],
        );

        $missingPermissions = array_values(array_diff(
            AdminPermissions::all(),
            $adminRole->permissions()->pluck('name')->all(),
        ));

        if ($missingPermissions !== []) {
            $adminRole->givePermissionTo($missingPermissions);
        }

        $adminUser = User::query()->firstOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        if (! $adminUser->hasRole($adminRole)) {
            $adminUser->assignRole($adminRole);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
