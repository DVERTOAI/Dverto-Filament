<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Roles';

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
                'Role Workspace',
                '',
                [
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
                    Select::make('permissions')
                        ->label('Permission Matrix')
                        ->relationship('permissions', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                    SchemaActions::make([
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
                        ->extraAttributes([
                            'class' => 'ac-card-actions',
                        ])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedShieldCheck,
                'role',
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
                    ->width('22%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->width('14%')
                    ->badge(),
                TextColumn::make('permissions.name')
                    ->label('Permissions')
                    ->width('46%')
                    ->badge()
                    ->separator(','),
                TextColumn::make('updated_at')
                    ->width('18%')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
