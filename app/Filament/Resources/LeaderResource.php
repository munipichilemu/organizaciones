<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderResource\Pages;
use App\Models\Leader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Laragear\Rut\Rut;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class LeaderResource extends Resource
{
    protected static ?string $model = Leader::class;

    protected static ?string $modelLabel = 'dirigente';

    protected static ?string $navigationIcon = 'fas-person';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre completo')
                    ->placeholder('Slarti Bartfast')
                    ->required(),
                Forms\Components\TextInput::make('rut')
                    ->label('RUT')
                    ->placeholder('2.852.747-0')
                    ->rules(['rut'])
                    ->rules(
                        ['rut_unique:leaders,rut_num,rut_vd'],
                        fn (?string $context): bool => $context === 'create'
                    )
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state): string => strlen($state) > 3
                        ? $set('rut', Rut::parse($state)->format())
                        : $state
                    )
                    ->formatStateUsing(fn (?string $state): string => $state ?? '')
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->validationAttribute('rut')
                    ->required(),
                PhoneInput::make('phone')
                    ->label('Teléfono')
                    ->defaultCountry('CL')
                    ->initialCountry('CL')
                    ->disallowDropdown()
                    ->inputNumberFormat(PhoneInputNumberType::E164)
                    ->separateDialCode(),
                Forms\Components\TextInput::make('email')
                    ->label('Correo electrónico')
                    ->placeholder('slarti@bistromath.space')
                    ->email(),
                Forms\Components\Textarea::make('address')
                    ->label('Dirección')
                    ->columnSpanFull()
                    ->rows(6)
                    ->placeholder(function () {
                        return <<<'TEXT'
Alnitak 33, Of. B
Condominio Nebulosa Hipocampo
Magratea
TEXT;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre completo')
                    ->description(fn (Leader $record): ?string => $record->rut ?? 'RUT no informado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->url(fn ($state) => $state ?? "tel:{$state}")
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->url(fn ($state) => $state ?? "mailto:{$state}")
                    ->searchable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hidden(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /*public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
            ]);
    }*/

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLeaders::route('/'),
            'view' => Pages\ViewLeader::route('/{record}'),
        ];
    }
}
