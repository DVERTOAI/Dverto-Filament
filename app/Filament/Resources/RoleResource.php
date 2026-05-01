<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

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
                'Role Details',
                '',
                [
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label('Role Name')
                                ->placeholder('e.g. Operations Manager')
                                ->prefixIcon(Heroicon::OutlinedIdentification)
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('guard_name')
                                ->label('Guard')
                                ->placeholder('web')
                                ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                                ->default('web')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columnSpanFull(),
                    CheckboxList::make('permissions')
                        ->label('Available Permissions')
                        ->relationship(
                            'permissions',
                            'name',
                            modifyQueryUsing: fn (Builder $query): Builder => $query->orderBy('name'),
                        )
                        ->getOptionLabelFromRecordUsing(static fn (Permission $record): string => static::formatPermissionLabel($record->name))
                        ->bulkToggleable()
                        ->searchable()
                        ->columns([
                            'default' => 1,
                            'md' => 2,
                        ])
                        ->gridDirection(GridDirection::Column)
                        ->extraAttributes([
                            'class' => 'ac-permissions-checkbox-tree',
                        ])
                        ->columnSpanFull(),
                    SchemaActions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Create Role')
                            ->submit('create')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('gray')
                            ->url(fn ($livewire): string => $livewire->getResource()::getUrl('index')),
                    ])
                        ->alignEnd()
                        ->extraAttributes([
                            'class' => 'ac-card-actions',
                        ])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedShieldCheck,
                'role',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'ac-compact-table ac-user-table'])
            ->searchPlaceholder('Search roles by name or guard')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5])
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->width('43%')
                    ->html()
                    ->formatStateUsing(static function (Role $record): string {
                        $initials = e(static::getRoleInitials($record->name));
                        $name = e($record->name);
                        $guard = e($record->guard_name);

                        return <<<HTML
                            <div class="ac-user-cell">
                                <span class="ac-user-avatar">{$initials}</span>
                                <span class="ac-user-meta">
                                    <span class="ac-user-name">{$name}</span>
                                    <span class="ac-user-email">{$guard}</span>
                                </span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('permissions')
                    ->label('Permissions')
                    ->width('32%')
                    ->view('filament.tables.access-badge-popover')
                    ->viewData(static fn (Role $record): array => [
                        'emptyLabel' => 'No permission',
                        'items' => $record->permissions
                            ->pluck('name')
                            ->map(static fn (string $permission): string => static::formatPermissionLabel($permission))
                            ->sort()
                            ->values()
                            ->all(),
                        'popoverTitle' => 'More permissions',
                    ]),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->width('15%')
                    ->badge()
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('permissions');
    }

    protected static function getRoleInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'R';
    }

    protected static function formatPermissionLabel(string $permission): string
    {
        return str($permission)
            ->replace(['_', '-'], ' ')
            ->squish()
            ->headline()
            ->toString();
    }
}
