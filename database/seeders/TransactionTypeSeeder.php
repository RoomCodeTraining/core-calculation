<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use App\Enums\TransactionTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Status;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        TransactionType::create([
            'code' => TransactionTypeEnum::DEPOSIT->value,
            'label' => 'Attribution',
            'description' => 'Attribution',
            'status_id' => Status::firstWhere('code', StatusEnum::ACTIVE)->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        TransactionType::create([
            'code' => TransactionTypeEnum::WITHDRAWAL->value,
            'label' => 'Retrait',
            'description' => 'Retrait',
            'status_id' => Status::firstWhere('code', StatusEnum::ACTIVE)->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
