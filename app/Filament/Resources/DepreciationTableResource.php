<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepreciationTableResource\Pages;
use App\Filament\Resources\DepreciationTableResource\RelationManagers;
use App\Models\DepreciationTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepreciationTableResource extends Resource
{
    protected static ?string $model = DepreciationTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Tables de Dépréciation';

    protected static ?string $modelLabel = 'Table de Dépréciation';

    protected static ?string $pluralModelLabel = 'Tables de Dépréciation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('usage_id')
                    ->label('Genre de Véhicule')
                    ->relationship('genre', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label ?? $record->name ?? 'N/A')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('vehicle_age_id')
                    ->label('Âge du Véhicule')
                    ->relationship('vehicleAge', 'label')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label ?? ($record->value ? $record->value . ' mois' : 'N/A'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('value')
                    ->label('Valeur de dépréciation')
                    ->required()
                    ->numeric()
                    ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('genre.label')
                    ->label('Genre de Véhicule')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genre.code')
                    ->label('Code Genre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicleAge.label')
                    ->label('Âge du Véhicule')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicleAge.value')
                    ->label('Valeur (mois)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valeur de dépréciation')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('usage_id')
                    ->label('Genre de Véhicule')
                    ->relationship('genre', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label ?? $record->name ?? 'N/A')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('vehicle_age_id')
                    ->label('Âge du Véhicule')
                    ->relationship('vehicleAge', 'label')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label ?? ($record->value ? $record->value . ' mois' : 'N/A'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepreciationTables::route('/'),
            'create' => Pages\CreateDepreciationTable::route('/create'),
            'edit' => Pages\EditDepreciationTable::route('/{record}/edit'),
        ];
    }
}

