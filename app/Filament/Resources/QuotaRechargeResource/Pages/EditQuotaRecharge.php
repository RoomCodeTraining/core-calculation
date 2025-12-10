<?php

namespace App\Filament\Resources\QuotaRechargeResource\Pages;

use App\Filament\Resources\QuotaRechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotaRecharge extends EditRecord
{
    protected static string $resource = QuotaRechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
