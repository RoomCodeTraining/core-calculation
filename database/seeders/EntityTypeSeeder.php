<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\EntityType;
use Illuminate\Database\Seeder;

class EntityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        EntityType::create([
            'code' => \App\Enums\EntityTypeEnum::MAIN_ORGANIZATION,
            'label' => "Organisation principale",
            'description' => "Organisation principale",
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        EntityType::create([
            'code' => \App\Enums\EntityTypeEnum::ORGANIZATION,
            'label' => "Organisation",
            'description' => "Organisation",
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        EntityType::create([
            'code' => \App\Enums\EntityTypeEnum::OFFICE,
            'label' => "Bureau",
            'description' => "Bureau",
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);
    }
}
