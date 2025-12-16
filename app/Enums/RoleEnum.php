<?php

namespace App\Enums;

use App\Concerns\UsefulEnums;

enum RoleEnum: string
{
    use UsefulEnums;

    case SYSTEM_ADMIN = 'system_admin';
    case ADMIN = 'admin';
    case ADMIN_ORGANIZATION = 'admin_organization';
    case UNASSIGNED = 'unassigned';
}
