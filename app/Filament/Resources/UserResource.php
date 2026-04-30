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
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
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
                'Profile & Access',
                'Keep the user profile simple and attach the right roles.',
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
            ->extraAttributes(['class' => 'ac-compact-table ac-user-table'])
            ->searchPlaceholder('Search users by name or email')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5])
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('User')
                    ->width('43%')
                    ->html()
                    ->formatStateUsing(static function (User $record): string {
                        $initials = e(static::getUserInitials($record->name));
                        $name = e($record->name);
                        $email = e($record->email);

                        return <<<HTML
                            <div class="ac-user-cell">
                                <span class="ac-user-avatar">{$initials}</span>
                                <span class="ac-user-meta">
                                    <span class="ac-user-name">{$name}</span>
                                    <span class="ac-user-email">{$email}</span>
                                </span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->width('32%')
                    ->badge()
                    ->separator(',')
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->placeholder('No role'),
                TextColumn::make('email_verified_at')
                    ->label('Status')
                    ->width('15%')
                    ->badge()
                    ->getStateUsing(static fn (User $record): string => $record->email_verified_at ? 'Verified' : 'Pending')
                    ->color(static fn (string $state): string => $state === 'Verified' ? 'success' : 'warning')
                    ->visibleFrom('md'),
            ])
            ->defaultSort('name')
            ->recordActionsColumnLabel('Edit')
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
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

    protected static function getUserInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'U';
    }
}
