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

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'admin' => AdminPermissions::all(),
            'manager' => [
                AdminPermissions::VIEW_DASHBOARD,
                AdminPermissions::VIEW_REPORTS,
                AdminPermissions::EXPORT_REPORTS,
                AdminPermissions::VIEW_CUSTOMERS,
                AdminPermissions::MANAGE_CUSTOMERS,
                AdminPermissions::VIEW_CONTENT,
                AdminPermissions::VIEW_USERS,
                AdminPermissions::VIEW_ACTIVITY_LOG,
            ],
            'editor' => [
                AdminPermissions::VIEW_DASHBOARD,
                AdminPermissions::VIEW_CUSTOMERS,
                AdminPermissions::VIEW_CONTENT,
                AdminPermissions::MANAGE_CONTENT,
                AdminPermissions::VIEW_REPORTS,
            ],
            'support' => [
                AdminPermissions::VIEW_DASHBOARD,
                AdminPermissions::VIEW_CUSTOMERS,
                AdminPermissions::MANAGE_CUSTOMERS,
                AdminPermissions::VIEW_ACTIVITY_LOG,
            ],
            'viewer' => [
                AdminPermissions::VIEW_DASHBOARD,
                AdminPermissions::VIEW_REPORTS,
                AdminPermissions::VIEW_CUSTOMERS,
                AdminPermissions::VIEW_CONTENT,
            ],
        ];

        $createdRoles = [];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);
            $createdRoles[$roleName] = $role;
        }

        $users = [
            [
                'email' => 'admin@example.com',
                'name' => 'Admin User',
                'role' => 'admin',
            ],
            [
                'email' => 'manager@example.com',
                'name' => 'Manager User',
                'role' => 'manager',
            ],
            [
                'email' => 'editor@example.com',
                'name' => 'Editor User',
                'role' => 'editor',
            ],
            [
                'email' => 'support@example.com',
                'name' => 'Support User',
                'role' => 'support',
            ],
            [
                'email' => 'viewer@example.com',
                'name' => 'Viewer User',
                'role' => 'viewer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::query()->firstOrCreate(
                [
                    'email' => $userData['email'],
                ],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $role = $createdRoles[$userData['role']];

            if (! $user->hasRole($role)) {
                $user->syncRoles([$role]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
