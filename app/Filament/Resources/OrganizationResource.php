<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers\LeadersRelationManager;
use App\InformationSource;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laragear\Rut\Rut;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'fas-sitemap';

    protected static ?string $modelLabel = 'organización';

    protected static ?string $pluralModelLabel = 'organizaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Antecedentes')
                            ->columns(2)
                            ->icon('fas-id-card-clip')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre de la Organización')
                                    ->columnSpanFull()
                                    ->required(),

                                Forms\Components\TextInput::make('registration_id')
                                    ->label('Nº de Registro')
                                    ->columnStart(1)
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('rut')
                                    ->label('RUT')
                                    ->rules(['rut'])
                                    ->rules(
                                        ['rut_unique:organizations,rut_num,rut_vd'],
                                        fn (?string $context): bool => $context === 'create'
                                    )
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state): string => strlen($state) > 3
                                        ? $set('rut', Rut::parse($state)->format())
                                        : $state
                                    )
                                    ->formatStateUsing(fn (?string $state): string => $state ?? '')
                                    ->validationAttribute('rut'),

                                Forms\Components\Select::make('organization_type_id')
                                    ->label('Tipo de organización')
                                    ->relationship('type', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Clasificación')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Detalles')
                            ->columns(3)
                            ->icon('fas-info')
                            ->schema([
                                Forms\Components\Select::make('information_source')
                                    ->label('Origen de inscripción')
                                    ->hintIcon(
                                        icon: 'fas-circle-question',
                                        tooltip: 'Dónde se originó la inscripción de la organización en el registro.'
                                    )
                                    ->columnSpan(2)
                                    ->native(false)
                                    ->options(InformationSource::class)
                                    ->default(InformationSource::MUNICIPAL)
                                    ->required(),

                                Forms\Components\DatePicker::make('registered_at')
                                    ->label('Inscripción del registro')
                                    ->hintIcon(
                                        icon: 'fas-circle-question',
                                        tooltip: 'Fecha de inscripción del registro, o su solicitud de inscripción'
                                    )
                                    ->columnStart(1)
                                    ->required(),
                                Forms\Components\DatePicker::make('confirmed_at')
                                    ->label('Concesión del registro')
                                    ->hintIcon(
                                        icon: 'fas-circle-question',
                                        tooltip: 'Fecha de otorgamiento del registro de la organización'
                                    )
                                    ->required(),
                                Forms\Components\Select::make('organization_state_id')
                                    ->label('Estado')
                                    ->hintIcon(
                                        icon: 'fas-circle-question',
                                        tooltip: 'Estado de vigencia de la organización'
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->relationship('state', 'name')
                                    ->required(),

                                Forms\Components\Textarea::make('address')
                                    ->label('Domicilio')
                                    ->columnSpanFull()
                                    ->rows(5),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Nº Registro')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Organización')
                    ->description(fn (?Organization $record): ?string => $record->rut ?? 'RUT no informado')
                    ->searchable(['name', 'rut_num'])
                    ->limit(48),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Clasificación')
                    ->badge()
                    ->color(Color::Slate)
                    ->icon(fn (?Organization $record): ?string => $record->category->icon)
                    ->description(fn (?Organization $record): ?string => $record->type->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Estado')
                    ->badge()
                    ->icon(fn (?Organization $record): string => $record->state->icon ?? 'fas-circle')
                    ->color(fn (?Organization $record): array|string => $record->state->color
                        ? Color::rgb("{$record->state->color['type']}({$record->state->color['value']})")
                        : 'primary'
                    )
                    ->description(fn (?Organization $record): ?string => $record->trashed()
                        ? "Eliminado el {$record->deleted_at->format('d-m-Y')}"
                        : null
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Oficializada')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersTriggerAction(fn (Action $action) => $action->button()->label('Filtros'))
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filters([
                Tables\Filters\SelectFilter::make('state')
                    ->label('Estado')
                    ->relationship('state', 'name')
                    ->default('1'), // Visualiza las organizaciones vigentes por omisión
                Tables\Filters\SelectFilter::make('organization_type_id')
                    ->label('Tipo de organización')
                    ->relationship('type', 'name'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Clasificación')
                    ->relationship('category', 'name'),
                /* Tables\Filters\TrashedFilter::make(), */
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color(Color::Sky),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make()->color(Color::Amber),
                    Tables\Actions\ForceDeleteAction::make(),
                ])->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrganizations::route('/'),
            'edit' => Pages\EditOrganization::route('/{record}/editar'),
            'view' => Pages\ViewOrganization::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LeadersRelationManager::class,
        ];
    }
}
