<?php

namespace App\Policies;

use App\Models\User;
use App\Support\AdminPermissions;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function create(User $user): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can(AdminPermissions::MANAGE_ACCESS_CONTROL);
    }
}
