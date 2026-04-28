<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->can(AdminPermissions::MANAGE_ACCESS_CONTROL) ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            AccessControlFormCard::make(
                'User Workspace',
                '',
                [
                    TextInput::make('name')
                        ->label('Full Name')
                        ->placeholder('Enter team member full name')
                        ->prefixIcon(Heroicon::OutlinedUser)
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->placeholder('name@company.com')
                        ->prefixIcon(Heroicon::OutlinedEnvelope)
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->placeholder('Create a strong password')
                        ->prefixIcon(Heroicon::OutlinedLockClosed)
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(static fn (string $state): string => Hash::make($state))
                        ->dehydrated(static fn (?string $state): bool => filled($state))
                        ->required(static fn (string $operation): bool => $operation === 'create')
                        ->minLength(8),
                    CheckboxList::make('roles')
                        ->label('Assigned Roles')
                        ->relationship('roles', 'name')
                        ->columns([
                            'md' => 2,
                            'xl' => 3,
                        ])
                        ->columnSpanFull()
                        ->searchable()
                        ->bulkToggleable()
                        ->extraAttributes([
                            'class' => 'ac-form-selector',
                        ]),
                ],
                Heroicon::OutlinedUserGroup,
                'user',
            )->columns([
                'default' => 1,
                'xl' => 2,
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(','),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('roles');
    }
}
