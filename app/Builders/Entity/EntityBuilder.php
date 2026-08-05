<?php

namespace App\Builders\Entity;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\EntityType;
use App\Enums\EntityTypeEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class EntityBuilder extends Builder
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
            $entityTypes = EntityType::whereIn('code', [EntityTypeEnum::ORGANIZATION->value, EntityTypeEnum::INSURER->value, EntityTypeEnum::REPAIRER->value])->pluck('id');
            return $this->whereIn('entity_type_id', $entityTypes);
        }

        if ($user->isAdminOrganization()) {
            $entityTypes = EntityType::whereIn('code', [EntityTypeEnum::ORGANIZATION->value, EntityTypeEnum::INSURER->value, EntityTypeEnum::REPAIRER->value, EntityTypeEnum::BROKER->value, EntityTypeEnum::AGENT->value])->pluck('id');
            return $this->whereIn('entity_type_id', $entityTypes);
        }

        return $this;
    }
}
