<?php

namespace App\Filament\Resources\DepreciationTableResource\Pages;

use App\Filament\Resources\DepreciationTableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepreciationTable extends EditRecord
{
    protected static string $resource = DepreciationTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

