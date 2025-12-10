<?php

namespace App\Filament\Resources\QuotaUsageResource\Pages;

use App\Filament\Resources\QuotaUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotaUsage extends EditRecord
{
    protected static string $resource = QuotaUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
