<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationTypeResource\Pages;
use App\Models\OrganizationType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationTypeResource extends Resource
{
    protected static ?string $model = OrganizationType::class;

    protected static ?string $navigationIcon = 'fas-layer-group';

    protected static ?string $modelLabel = 'tipo';

    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tipo')
                    ->description(fn (?OrganizationType $record): string => \Str::words($record->description, 13) ?? 'Descripción no disponible')
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
            'index' => Pages\ManageOrganizationTypes::route('/'),
        ];
    }
}
