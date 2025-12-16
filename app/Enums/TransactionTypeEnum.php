<?php

namespace App\Enums;

use App\Concerns\UsefulEnums;

enum TransactionTypeEnum: string
{
    use UsefulEnums;

    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
}
