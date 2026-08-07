<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Filament\Support\AdminListTable;
use App\Models\Category;
use App\Support\AdminPermissions;
use App\Support\Rooms;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $modelLabel = 'Category';

    protected static ?string $pluralModelLabel = 'Categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 6;

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
                'Category Information',
                'Enter the details for the category',
                [
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->placeholder('e.g., Referral, Website, Campaign')
                                ->prefixIcon(Heroicon::OutlinedTag)
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Select::make('type')
                                ->label('Type')
                                ->placeholder('Select type')
                                ->prefixIcon(Heroicon::OutlinedQueueList)
                                ->options([
                                    Category::TYPE_CATEGORY => 'Category',
                                    Category::TYPE_SUBCATEGORY => 'Subcategory',
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state !== Category::TYPE_SUBCATEGORY) {
                                        $set('parent_id', null);
                                    }
                                })
                                ->columnSpan(1),
                            Select::make('parent_id')
                                ->label('Parent Category')
                                ->placeholder('Select parent category')
                                ->prefixIcon(Heroicon::OutlinedFolder)
                                ->relationship(
                                    name: 'parent',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: static fn (Builder $query): Builder => $query
                                        ->where('type', Category::TYPE_CATEGORY)
                                        ->orderBy('name'),
                                    ignoreRecord: true,
                                )
                                ->searchable()
                                ->preload()
                                ->required(fn (Get $get): bool => $get('type') === Category::TYPE_SUBCATEGORY)
                                ->visible(fn (Get $get): bool => $get('type') === Category::TYPE_SUBCATEGORY)
                                ->columnSpan(1),
                            Select::make('rooms')
                                ->label('Rooms')
                                ->placeholder('Select rooms')
                                ->prefixIcon(Heroicon::OutlinedBuildingOffice)
                                ->options(Rooms::options())
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
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
                            'class' => 'ac-card-actions ac-card-actions--category',
                        ])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedTag,
                'user',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return AdminListTable::configure(
            $table,
            searchPlaceholder: 'Search Category',
            filtersFormColumns: 6,
        )
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->html()
                    ->formatStateUsing(static function (Category $record): string {
                        $initials = e(static::getCategoryInitials($record->name));
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
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(static fn (?string $state): string => match ($state) {
                        Category::TYPE_SUBCATEGORY => 'Subcategory',
                        default => 'Category',
                    })
                    ->color(static fn (?string $state): string => match ($state) {
                        Category::TYPE_SUBCATEGORY => 'warning',
                        default => 'info',
                    })
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('rooms')
                    ->label('Rooms')
                    ->badge()
                    ->getStateUsing(static fn (Category $record): array => Rooms::labels($record->rooms ?? []))
                    ->placeholder('—')
                    ->toggleable()
                    ->visibleFrom('lg'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(static fn (Category $record): string => $record->is_active ? 'Active' : 'Inactive')
                    ->color(static fn (string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable()
                    ->visibleFrom('md'),
            ])
            ->filters([
                Filter::make('search')
                    ->form([
                        TextInput::make('q')
                            ->label('Search')
                            ->placeholder('Search Category')
                            ->prefixIcon(Heroicon::OutlinedMagnifyingGlass)
                            ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                            ->extraInputAttributes(['class' => 'ac-filter-control']),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $q = trim((string) ($data['q'] ?? ''));

                        if ($q === '') {
                            return $query;
                        }

                        return $query->where('name', 'like', "%{$q}%");
                    })
                    ->indicateUsing(fn (array $data): ?string => filled($data['q'] ?? null) ? 'Search: '.$data['q'] : null),
                SelectFilter::make('type')
                    ->label('Type')
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All types')
                    ->options([
                        Category::TYPE_CATEGORY => 'Category',
                        Category::TYPE_SUBCATEGORY => 'Subcategory',
                    ])
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control'])),
                SelectFilter::make('rooms')
                    ->label('Room')
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All rooms')
                    ->options(Rooms::options())
                    ->modifyFormFieldUsing(fn (Select $field): Select => $field
                        ->extraFieldWrapperAttributes(['class' => 'ac-filter-field'])
                        ->extraInputAttributes(['class' => 'ac-filter-control']))
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if ($value === null || $value === '') {
                            return $query;
                        }

                        return $query->whereJsonContains('rooms', $value);
                    }),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('parent');
    }

    protected static function getCategoryInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'C';
    }
}
