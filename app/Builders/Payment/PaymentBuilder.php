<?php

namespace App\Builders\Payment;

use App\Models\User;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class PaymentBuilder extends Builder
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
            return $this->whereHas('transaction', fn ($q) => $q->where('entity_id', $user->entity_id));
        }

        return $this;
    }
}
