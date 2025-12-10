<?php

namespace App\Filament\Resources\DepreciationTableResource\Pages;

use App\Filament\Resources\DepreciationTableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepreciationTables extends ListRecords
{
    protected static string $resource = DepreciationTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

