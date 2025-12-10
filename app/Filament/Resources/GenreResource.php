<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenreResource\Pages;
use App\Filament\Resources\GenreResource\RelationManagers;
use App\Models\Genre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Genres';

    protected static ?string $modelLabel = 'Genre';

    protected static ?string $pluralModelLabel = 'Genres';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vehicle_model_id')
                    ->label('Modèle de véhicule')
                    ->relationship('vehicleModel', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('code')
                    ->label('Code')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->maxLength(255),
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
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('max_mileage_essence_per_year')
                    ->label('Kilométrage max essence par an')
                    ->numeric()
                    ->step(0.01),
                Forms\Components\TextInput::make('max_mileage_diesel_per_year')
                    ->label('Kilométrage max diesel par an')
                    ->numeric()
                    ->step(0.01),
                Forms\Components\DateTimePicker::make('disabled_at')
                    ->label('Désactivé le')
                    ->displayFormat('d/m/Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('vehicleModel.brand'))
            ->columns([
                Tables\Columns\TextColumn::make('vehicleModel.brand.name')
                    ->label('Marque')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('vehicleModel.name')
                    ->label('Modèle de véhicule')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('max_mileage_essence_per_year')
                    ->label('Km max essence/an')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('max_mileage_diesel_per_year')
                    ->label('Km max diesel/an')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('disabled_at')
                    ->label('Désactivé')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn ($record) => $record->disabled_at === null)
                    ->sortable()
                    ->toggleable(),
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
                Tables\Filters\SelectFilter::make('vehicle_model_id')
                    ->label('Modèle de véhicule')
                    ->relationship('vehicleModel', 'name')
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
            'index' => Pages\ListGenres::route('/'),
            'create' => Pages\CreateGenre::route('/create'),
            'edit' => Pages\EditGenre::route('/{record}/edit'),
        ];
    }
}
