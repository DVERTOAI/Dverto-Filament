<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Filament\Support\AdminListTable;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                static fn (string $operation): string => $operation === 'create'
                    ? 'Fill in the information below to add a new user.'
                    : 'Review and update this user’s profile, role, and billing details.',
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
        return AdminListTable::configure(
            $table,
            searchPlaceholder: 'Search User',
            filtersFormColumns: 5,
        )
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->html()
                    ->formatStateUsing(static function (User $record): string {
                        $initials = e(static::getUserInitials($record->name));
                        $name     = e($record->name);
                        $colors   = ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8'];
                        $color    = $colors[crc32($record->name) % count($colors)];

                        return <<<HTML
                            <div class="ac-user-cell ac-user-cell--single">
                                <span class="ac-user-avatar" style="background:color-mix(in srgb,{$color} 14%,#fff);color:{$color};">{$initials}</span>
                                <span class="ac-user-name">{$name}</span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
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
                Filter::make('search')
                    ->form([
                        TextInput::make('q')
                            ->label('Search')
                            ->placeholder('Search User')
                            ->prefixIcon(Heroicon::OutlinedMagnifyingGlass)
                            ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                            ->extraInputAttributes(['class' => 'ac-filter-control']),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $q = trim((string) ($data['q'] ?? ''));

                        if ($q === '') {
                            return $query;
                        }

                        return $query->where(function (Builder $query) use ($q): void {
                            $query->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                    })
                    ->indicateUsing(fn (array $data): ?string => filled($data['q'] ?? null) ? 'Search: '.$data['q'] : null),
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All roles')
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control'])),
                SelectFilter::make('plan')
                    ->label('Plan')
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All plans')
                    ->options([
                        'Basic'      => 'Basic',
                        'Team'       => 'Team',
                        'Enterprise' => 'Enterprise',
                    ])
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control'])),
                SelectFilter::make('billing')
                    ->label('Billing')
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All billing')
                    ->options([
                        'Auto Debit' => 'Auto Debit',
                        'Manual'     => 'Manual',
                        'Invoice'    => 'Invoice',
                    ])
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control'])),
                SelectFilter::make('email_verified_at')
                    ->label('Status')
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control']))
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === null || $value === '') {
                            return $query;
                        }

                        return $value === '1'
                            ? $query->whereNotNull('email_verified_at')
                            : $query->whereNull('email_verified_at');
                    }),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('gray')
                    ->tooltip('Edit'),
                DeleteAction::make()
                    ->iconButton()
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->tooltip('Delete'),
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
