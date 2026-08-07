<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Filament\Support\AdminListTable;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->can(AdminPermissions::MANAGE_ACCESS_CONTROL) ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::canAccess();
    }

    public static function canCreate(): bool
    {
        return static::canAccess();
    }

    public static function canEdit($record): bool
    {
        return static::canAccess();
    }

    public static function canDelete($record): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            AccessControlFormCard::make(
                'Permission Details',
                '',
                [
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label('Permission Name')
                                ->placeholder('e.g. manage users')
                                ->prefixIcon(Heroicon::OutlinedSparkles)
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
                    SchemaActions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Create Permission')
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
                Heroicon::OutlinedKey,
                'permission',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return AdminListTable::configure(
            $table,
            searchPlaceholder: 'Search Permission',
            filtersFormColumns: 1,
            compact: true,
        )
            ->columns([
                TextColumn::make('name')
                    ->label('Permission')
                    ->width('43%')
                    ->html()
                    ->formatStateUsing(static function (Permission $record): string {
                        $initials = e(static::getPermissionInitials($record->name));
                        $name = e($record->name);
                        $guard = e($record->guard_name);
                        $colors = ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8'];
                        $color = $colors[crc32($record->name) % count($colors)];

                        return <<<HTML
                            <div class="ac-user-cell">
                                <span class="ac-user-avatar" style="background:{$color};color:#fff;">{$initials}</span>
                                <span class="ac-user-meta">
                                    <span class="ac-user-name">{$name}</span>
                                    <span class="ac-user-email">{$guard}</span>
                                </span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->width('32%')
                    ->badge()
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->native(true)
                    ->placeholder('All guards')
                    ->options(fn () => Permission::query()->distinct()->orderBy('guard_name')->pluck('guard_name', 'guard_name')->all()),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->icon(Heroicon::OutlinedPencil)
                    ->color('gray')
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    protected static function getPermissionInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'P';
    }
}
