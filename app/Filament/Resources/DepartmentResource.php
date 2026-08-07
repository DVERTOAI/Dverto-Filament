<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Filament\Support\AdminListTable;
use App\Models\Department;
use App\Support\AdminPermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?string $modelLabel = 'Department';

    protected static ?string $pluralModelLabel = 'Departments';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

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
                'Department Details',
                'Fill in the department information below',
                [
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label('Department Name')
                                ->placeholder('e.g., Emergency, Cardiology, Surgery')
                                ->prefixIcon(Heroicon::OutlinedBuildingOffice2)
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            TextInput::make('code')
                                ->label('Department Code')
                                ->placeholder('e.g., ER, CARD, SURG')
                                ->prefixIcon(Heroicon::OutlinedHashtag)
                                ->required()
                                ->maxLength(50)
                                ->unique(ignoreRecord: true)
                                ->dehydrateStateUsing(static fn (?string $state): ?string => filled($state) ? strtoupper(trim($state)) : $state)
                                ->columnSpan(1),
                            Select::make('parent_id')
                                ->label('Parent Department')
                                ->placeholder('None')
                                ->prefixIcon(Heroicon::OutlinedSquares2x2)
                                ->relationship(
                                    name: 'parent',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: static fn (Builder $query): Builder => $query->orderBy('name'),
                                    ignoreRecord: true,
                                )
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->columnSpan(1),
                            TextInput::make('room_number')
                                ->label('Room Number')
                                ->placeholder('e.g., Room 204')
                                ->prefixIcon(Heroicon::OutlinedMapPin)
                                ->maxLength(100)
                                ->columnSpan(1),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
                            Textarea::make('description')
                                ->label('Department Description')
                                ->placeholder('Briefly describe this department')
                                ->rows(4)
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                    SchemaActions::make([
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('gray')
                            ->url(fn ($livewire): string => $livewire->getResource()::getUrl('index')),
                        Action::make('save')
                            ->label('Save')
                            ->submit('save')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
                        Action::make('create')
                            ->label('Save')
                            ->submit('create')
                            ->color('primary')
                            ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
                    ])
                        ->alignEnd()
                        ->extraAttributes([
                            'class' => 'ac-card-actions ac-card-actions--department',
                        ])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedBuildingOffice2,
                'user',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return AdminListTable::configure(
            $table,
            searchPlaceholder: 'Search Department',
            filtersFormColumns: 5,
        )
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->html()
                    ->formatStateUsing(static function (Department $record): string {
                        $initials = e(static::getDepartmentInitials($record->name));
                        $name = e($record->name);
                        $colors = ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8'];
                        $color = $colors[crc32($record->name) % count($colors)];

                        return <<<HTML
                            <div class="ac-user-cell ac-user-cell--single">
                                <span class="ac-user-avatar" style="background:color-mix(in srgb,{$color} 14%,#fff);color:{$color};">{$initials}</span>
                                <span class="ac-user-name">{$name}</span>
                            </div>
                        HTML;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('room_number')
                    ->label('Room')
                    ->placeholder('—')
                    ->searchable()
                    ->visibleFrom('lg'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(static fn (Department $record): string => $record->is_active ? 'Active' : 'Inactive')
                    ->color(static fn (string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable()
                    ->visibleFrom('md'),
            ])
            ->filters([
                Filter::make('search')
                    ->form([
                        TextInput::make('q')
                            ->label('Search')
                            ->placeholder('Search Department')
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
                                ->orWhere('code', 'like', "%{$q}%")
                                ->orWhere('room_number', 'like', "%{$q}%");
                        });
                    })
                    ->indicateUsing(fn (array $data): ?string => filled($data['q'] ?? null) ? 'Search: '.$data['q'] : null),
                SelectFilter::make('parent_id')
                    ->label('Parent')
                    ->relationship('parent', 'name')
                    ->preload()
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All parents')
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control'])),
                SelectFilter::make('is_active')
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

                        return $query->where('is_active', $value === '1');
                    }),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('parent');
    }

    protected static function getDepartmentInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'D';
    }
}
