<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Mail\ApiTokenGenerated;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Utilisateurs';

    protected static ?string $modelLabel = 'Utilisateur';

    protected static ?string $pluralModelLabel = 'Utilisateurs';

    protected static ?string $navigationGroup = 'Quota';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisation')
                    ->relationship('organization', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->helperText('Laissez vide pour ne pas modifier le mot de passe lors de l\'édition'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('role', '!=', 'admin'))
            ->columns([
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('organization_id')
                    ->label('Organisation')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'user' => 'Utilisateur',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_token')
                    ->label('Générer token API')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Générer un nouveau token API')
                    ->modalDescription('Un nouveau token API sera généré et envoyé par email à l\'utilisateur. L\'ancien token sera remplacé.')
                    ->action(function (User $record) {
                        // generer un bearer token depuis sanctum
                       $token = $record->createToken('auth_token')->plainTextToken;

                        $record->api_token = $token;

                        $record->save();

                        Mail::to($record->email)->send(new ApiTokenGenerated($record, $token));

                        Notification::make()
                            ->title('Token API généré')
                            ->body('Un nouveau token API a été généré et envoyé par email à ' . $record->email)
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
