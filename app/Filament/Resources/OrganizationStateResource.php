<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Models\OrganizationState;
use Awcodes\Palette\Forms\Components\ColorPickerSelect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;

class OrganizationStateResource extends Resource
{
    protected static ?string $model = OrganizationState::class;

    protected static ?string $navigationIcon = 'fas-square-check';

    protected static ?string $modelLabel = 'estado';

    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                ColorPickerSelect::make('color')
                    ->colors(Color::all()),
                IconPicker::make('icon')
                    ->sets(['fontawesome-solid'])
                    ->columns(3)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Estado')
                    ->badge()
                    ->icon(fn (?OrganizationState $record): string => $record->icon ?? 'fas-circle')
                    ->color(fn (?OrganizationState $record): array|string => $record->color
                        ? Color::rgb("{$record->color['type']}({$record->color['value']})")
                        : 'primary'
                    )
                    ->searchable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageStatuses::route('/'),
        ];
    }
}
