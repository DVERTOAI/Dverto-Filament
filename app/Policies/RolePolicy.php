<?php

namespace App\Policies;

use App\Models\User;
use App\Support\AdminPermissions;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function create(User $user): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL)
            && $role->name !== 'Super Admin';
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL)
            && $role->name !== 'Super Admin';
    }
}
