<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Support\AccessControlFormCard;
use App\Filament\Support\AdminListTable;
use App\Models\Doctor;
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

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Doctors';

    protected static ?string $modelLabel = 'Doctor';

    protected static ?string $pluralModelLabel = 'Doctors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

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
                'Doctor Details',
                'Fill in the doctor information below',
                [
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label('Doctor Name')
                                ->placeholder('e.g., Dr. Sarah Patel')
                                ->prefixIcon(Heroicon::OutlinedUser)
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            TextInput::make('specialization')
                                ->label('Specialization')
                                ->placeholder('e.g., Cardiology, Orthopedics')
                                ->prefixIcon(Heroicon::OutlinedBeaker)
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            TextInput::make('email')
                                ->label('Email')
                                ->placeholder('doctor@example.com')
                                ->prefixIcon(Heroicon::OutlinedEnvelope)
                                ->email()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),
                            TextInput::make('phone')
                                ->label('Phone')
                                ->placeholder('e.g., +1 555 0100')
                                ->prefixIcon(Heroicon::OutlinedPhone)
                                ->tel()
                                ->maxLength(50)
                                ->columnSpan(1),
                            Select::make('department_id')
                                ->label('Department')
                                ->placeholder('Select department')
                                ->prefixIcon(Heroicon::OutlinedBuildingOffice2)
                                ->relationship(
                                    name: 'department',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: static fn (Builder $query): Builder => $query->orderBy('name'),
                                )
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->columnSpan(1),
                            TextInput::make('license_number')
                                ->label('License Number')
                                ->placeholder('e.g., MD-20481')
                                ->prefixIcon(Heroicon::OutlinedIdentification)
                                ->maxLength(100)
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
                            Textarea::make('bio')
                                ->label('Bio')
                                ->placeholder('Briefly describe this doctor’s background and focus')
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
                            'class' => 'ac-card-actions ac-card-actions--doctor',
                        ])
                        ->columnSpanFull(),
                ],
                Heroicon::OutlinedHeart,
                'user',
            )->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return AdminListTable::configure(
            $table,
            searchPlaceholder: 'Search Doctor',
            filtersFormColumns: 5,
        )
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->html()
                    ->formatStateUsing(static function (Doctor $record): string {
                        $initials = e(static::getDoctorInitials($record->name));
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
                TextColumn::make('specialization')
                    ->label('Specialization')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable()
                    ->visibleFrom('lg'),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(static fn (Doctor $record): string => $record->is_active ? 'Active' : 'Inactive')
                    ->color(static fn (string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable()
                    ->visibleFrom('md'),
            ])
            ->filters([
                Filter::make('search')
                    ->form([
                        TextInput::make('q')
                            ->label('Search')
                            ->placeholder('Search Doctor')
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
                                ->orWhere('email', 'like', "%{$q}%")
                                ->orWhere('phone', 'like', "%{$q}%")
                                ->orWhere('specialization', 'like', "%{$q}%")
                                ->orWhere('license_number', 'like', "%{$q}%");
                        });
                    })
                    ->indicateUsing(fn (array $data): ?string => filled($data['q'] ?? null) ? 'Search: '.$data['q'] : null),
                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->native(true)
                    ->selectablePlaceholder(true)
                    ->placeholder('All departments')
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('department');
    }

    protected static function getDoctorInitials(string $name): string
    {
        $clean = trim(preg_replace('/^(dr\.?|doctor)\s+/i', '', $name) ?? $name);
        $parts = preg_split('/\s+/', $clean) ?: [];

        $initials = collect($parts)
            ->filter()
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'D';
    }
}
