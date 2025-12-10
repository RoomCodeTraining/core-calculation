<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsageResource\Pages;
use App\Filament\Resources\UsageResource\RelationManagers;
use App\Models\Usage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsageResource extends Resource
{
    protected static ?string $model = Usage::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Usages';

    protected static ?string $modelLabel = 'Usage';

    protected static ?string $pluralModelLabel = 'Usages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('genre_id')
                    ->label('Genre')
                    ->relationship('genre', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('genre.name')
                    ->label('Genre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
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
                //
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
            'index' => Pages\ListUsages::route('/'),
            'create' => Pages\CreateUsage::route('/create'),
            'edit' => Pages\EditUsage::route('/{record}/edit'),
        ];
    }
}
