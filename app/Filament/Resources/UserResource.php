<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
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
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->can(AdminPermissions::MANAGE_ACCESS_CONTROL) ?? false;
    }

    public static function canViewAny(): bool { return static::canAccess(); }
    public static function canCreate(): bool { return static::canAccess(); }
    public static function canEdit($record): bool { return static::canAccess(); }
    public static function canDelete($record): bool { return static::canAccess(); }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            AccessControlFormCard::make(
                'User Details',
                'Fill in the information below to add a new user.',
                [
                    Grid::make(['default' => 1, 'md' => 2])
                        ->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->placeholder('John Doe')
                                ->prefixIcon(Heroicon::OutlinedUser)
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->placeholder('john@example.com')
                                ->prefixIcon(Heroicon::OutlinedEnvelope)
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label('Password')
                                ->placeholder('Min. 8 characters')
                                ->prefixIcon(Heroicon::OutlinedLockClosed)
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(static fn (string $state): string => Hash::make($state))
                                ->dehydrated(static fn (?string $state): bool => filled($state))
                                ->required(static fn (string $operation): bool => $operation === 'create')
                                ->minLength(8),
                            Select::make('roles')
                                ->label('Role')
                                ->placeholder('Select role')
                                ->prefixIcon(Heroicon::OutlinedUserGroup)
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload(),
                            Select::make('plan')
                                ->label('Plan')
                                ->placeholder('Select plan')
                                ->prefixIcon(Heroicon::OutlinedCreditCard)
                                ->options([
                                    'Basic'      => 'Basic',
                                    'Team'       => 'Team',
                                    'Enterprise' => 'Enterprise',
                                ])
                                ->default('Basic'),
                            Select::make('billing')
                                ->label('Billing Method')
                                ->placeholder('Select billing method')
                                ->prefixIcon(Heroicon::OutlinedBanknotes)
                                ->options([
                                    'Auto Debit' => 'Auto Debit',
                                    'Manual'     => 'Manual',
                                    'Invoice'    => 'Invoice',
                                ])
                                ->default('Auto Debit'),
                        ])
                        ->columnSpanFull(),
                    SchemaActions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Create User')
                            ->submit('create')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('gray')
                            ->url(fn ($livewire): string => $livewire->getResource()::getUrl('index')),
                    ])
                        ->alignEnd()
                        ->extraAttributes(['class' => 'ac-card-actions ac-card-actions--user'])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedUserGroup,
                'user',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'ac-user-table'])
            ->searchPlaceholder('Search User')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50, 100])
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('User')
                    ->html()
                    ->formatStateUsing(static function (User $record): string {
                        $initials = e(static::getUserInitials($record->name));
                        $name     = e($record->name);
                        $email    = e($record->email);
                        $colors   = ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8'];
                        $color    = $colors[crc32($record->name) % count($colors)];

                        return <<<HTML
                            <div class="sn-user-cell">
                                <span class="sn-avatar" style="background:color-mix(in srgb,{$color} 11%,#fff);color:{$color};border:1.5px solid color-mix(in srgb,{$color} 24%,#fff);">{$initials}</span>
                                <span class="sn-user-meta">
                                    <span class="sn-user-name">{$name}</span>
                                    <span class="sn-user-email">{$email}</span>
                                </span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('roles')
                    ->label('Role')
                    ->view('filament.tables.access-badge-popover')
                    ->viewData(static fn (User $record): array => [
                        'emptyLabel'   => 'No role',
                        'items'        => $record->roles->pluck('name')->sort()->values()->all(),
                        'popoverTitle' => 'More roles',
                    ]),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->placeholder('—')
                    ->color(static fn (?string $state): string => match ($state) {
                        'Enterprise' => 'warning',
                        'Team'       => 'info',
                        default      => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('billing')
                    ->label('Billing')
                    ->placeholder('—')
                    ->sortable()
                    ->visibleFrom('lg'),
                TextColumn::make('email_verified_at')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(static fn (User $record): string => $record->email_verified_at ? 'Active' : 'Inactive')
                    ->color(static fn (string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('plan')
                    ->label('Plan')
                    ->searchable()
                    ->options([
                        'Basic'      => 'Basic',
                        'Team'       => 'Team',
                        'Enterprise' => 'Enterprise',
                    ]),
                SelectFilter::make('billing')
                    ->label('Billing')
                    ->searchable()
                    ->options([
                        'Auto Debit' => 'Auto Debit',
                        'Manual'     => 'Manual',
                        'Invoice'    => 'Invoice',
                    ]),
                TernaryFilter::make('email_verified_at')
                    ->label('Status')
                    ->nullable()
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(['default' => 1, 'sm' => 2, 'lg' => 4])
            ->defaultSort('name')
            ->recordActionsColumnLabel('Actions')
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->tooltip('Delete'),
                EditAction::make()
                    ->iconButton()
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('gray')
                    ->tooltip('Edit'),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
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
