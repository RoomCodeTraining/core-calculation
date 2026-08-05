<?php

namespace App\Builders\Order;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;

class OrderBuilder extends Builder
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
            return $this;
        }

        if ($user->isAdminOrganization()) {
            return $this->where('orders.entity_id', $user->entity_id);
        }

        return $this->where('orders.entity_id', $user->entity_id);
    }
}
