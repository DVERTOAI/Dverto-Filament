<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
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
                    Grid::make([
                        'default' => 2,
                        'xl' => 2,
                    ])
                        ->schema([
                            TextInput::make('password')
                                ->placeholder('Create a strong password')
                                ->prefixIcon(Heroicon::OutlinedLockClosed)
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(static fn (string $state): string => Hash::make($state))
                                ->dehydrated(static fn (?string $state): bool => filled($state))
                                ->required(static fn (string $operation): bool => $operation === 'create')
                                ->minLength(8),
                            Select::make('roles')
                                ->label('Assigned Roles')
                                ->placeholder('Select roles')
                                ->prefixIcon(Heroicon::OutlinedUserGroup)
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->position('top'),
                        ])
                        ->columnSpanFull(),
                    SchemaActions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Save Changes')
                            ->submit('create')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('gray')
                            ->url(fn ($livewire): string => $livewire->getResource()::getUrl('index')),
                    ])
                        ->alignEnd()
                        ->columnSpanFull(),
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
            ->extraAttributes(['class' => 'ac-compact-table'])
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->width('30%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->width('34%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->width('22%')
                    ->badge()
                    ->separator(','),
                TextColumn::make('created_at')
                    ->width('14%')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActionsColumnLabel('Edit')
            ->actions([
                EditAction::make()
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->iconButton()
                    ->tooltip('Edit'),
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
