<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?int $navigationSort = 3;

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
                'Permission Workspace',
                '',
                [
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
                    SchemaActions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Create')
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
                Heroicon::OutlinedKey,
                'permission',
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
            ->searchPlaceholder('Search permissions by name or guard')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5])
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('Permission')
                    ->width('43%')
                    ->html()
                    ->formatStateUsing(static function (Permission $record): string {
                        $initials = e(static::getPermissionInitials($record->name));
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
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->width('32%')
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
