<?php

namespace App\Filament\Resources\QuotaUsageResource\Pages;

use App\Filament\Resources\QuotaUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotaUsage extends ViewRecord
{
    protected static string $resource = QuotaUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}



