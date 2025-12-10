<?php

namespace App\Filament\Resources\QuotaRechargeResource\Pages;

use App\Filament\Resources\QuotaRechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotaRecharges extends ListRecords
{
    protected static string $resource = QuotaRechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
