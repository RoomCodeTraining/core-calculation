<?php

namespace App\Builders\User;

use App\Models\User;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function isSuperAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::SYSTEM_ADMIN->value;
    }

    public function isAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::ADMIN->value;
    }

    public function isAdminOrganization(): bool
    {
        return $this->model->currentRole->name == RoleEnum::ADMIN_ORGANIZATION->value;
    }

    public function accessibleBy(?User $user)
    {
        if (empty($user)) {
            return $this;
        }

        if ($user->isSuperAdmin()) {
            return $this;
        }

        if ($user->isAdmin()) {
            $roles = Role::whereIn('name', [RoleEnum::ADMIN->value, RoleEnum::ADMIN_ORGANIZATION->value, RoleEnum::UNASSIGNED->value])->pluck('id');
            return $this->whereIn('current_role_id', $roles);
        }

        if ($user->isAdminOrganization()) {
            $roles = Role::whereIn('name', [RoleEnum::ADMIN_ORGANIZATION->value, RoleEnum::UNASSIGNED->value])->pluck('id');
            return $this->whereIn('current_role_id', $roles)
                    ->where('entity_id', $user->entity_id);
        }

        return $this->where('current_role_id', $user->current_role_id)
                    ->where('entity_id', $user->entity_id);
    }
}
