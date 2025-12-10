<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Administrateur',
                        'user' => 'Utilisateur',
                    ])
                    ->required()
                    ->default('user'),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required(fn ($livewire) => $livewire->mountedTableActionRecord === null)
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->helperText('Laissez vide pour ne pas modifier le mot de passe lors de l\'édition'),
                Forms\Components\TextInput::make('api_token')
                    ->label('Token API')
                    ->disabled()
                    ->dehydrated()
                    ->default(fn () => Str::random(60))
                    ->helperText('Généré automatiquement si vide'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'user' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrateur',
                        'user' => 'Utilisateur',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('api_token')
                    ->label('Token API')
                    ->limit(20)
                    ->copyable()
                    ->copyMessage('Token copié !')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Administrateur',
                        'user' => 'Utilisateur',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Définir l'organisation automatiquement depuis le parent
                        $data['organization_id'] = $this->getOwnerRecord()->id;
                        // Générer un token API si non fourni
                        if (empty($data['api_token'])) {
                            $data['api_token'] = Str::random(60);
                        }
                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
