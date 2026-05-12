<?php

namespace App\Builders\Recharge;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;

class RechargeBuilder extends Builder
{
    public function isSuperAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::SYSTEM_ADMIN->value;
    }

    public function isAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::ADMIN->value;
    }

    public function isAdminExpert(): bool
    {
        return $this->model->currentRole->name == RoleEnum::EXPERT_ADMIN->value;
    }

    public function isInsurerAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::INSURER_ADMIN->value;
    }

    public function isInsurerStandardUser(): bool
    {
        return $this->model->currentRole->name == RoleEnum::INSURER_STANDARD_USER->value;
    }

    public function isRepairerAdmin(): bool
    {
        return $this->model->currentRole->name == RoleEnum::REPAIRER_ADMIN->value;
    }

    public function isRepairerStandardUser(): bool
    {
        return $this->model->currentRole->name == RoleEnum::REPAIRER_STANDARD_USER->value;
    }

    public function isClient(): bool
    {
        return $this->model->currentRole->name == RoleEnum::CLIENT->value;
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

        if ($user->isAdminExpert()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        if ($user->isInsurerAdmin()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        if ($user->isInsurerStandardUser()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        if ($user->isRepairerAdmin()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        if ($user->isRepairerStandardUser()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        if ($user->isClient()) {
            return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
        }

        return $this->whereHas('transaction', fn ($q) => $q->where('transactions.entity_id', $user->entity_id));
    }
}
