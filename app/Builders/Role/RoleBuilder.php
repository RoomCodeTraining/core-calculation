<?php

namespace App\Builders\Role;

use App\Models\Role;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;

class RoleBuilder extends Builder
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
            return $this->where('name', RoleEnum::ADMIN_ORGANIZATION->value);
        }

        if ($user->isAdminOrganization()) {
            return $this->where('name', RoleEnum::ADMIN_ORGANIZATION->value);
        }

        return $this;
    }
}
