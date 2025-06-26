<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use App\Models\MemberPosition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Laragear\Rut\Rut;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class LeadersRelationManager extends RelationManager
{
    protected static string $relationship = 'leaders';

    protected static ?string $title = 'Dirigentes';

    protected static ?string $modelLabel = 'dirigente';

    public function form(Form $form): Form
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
                Forms\Components\Select::make('member_position_id')
                    ->label('Cargo')
                    ->searchable()
                    ->options(MemberPosition::all()->pluck('title', 'id')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'leaders.*',
                        'member_positions.title as position_title',
                        'pivot.member_position_id',
                    ])
                    ->join('leader_organization as pivot', 'leaders.id', '=', 'pivot.leader_id')
                    ->join('member_positions', 'pivot.member_position_id', '=', 'member_positions.id')
                    ->where('pivot.organization_id', $this->ownerRecord->id)
                    ->orderBy('member_positions.order', 'asc');
            })
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Integrante'),
                Tables\Columns\TextColumn::make('position_title')
                    ->label('Cargo'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear y vincular')
                    ->tooltip('Crea un nuevo dirigente y lo vincula a la organización')
                    ->icon('fas-plus')
                    ->color(Color::Emerald),
                Tables\Actions\AttachAction::make()
                    ->label('Vincular')
                    ->tooltip('Busca dentro de los dirigentes registrados y lo vincula a la organización')
                    ->icon('fas-link')
                    ->color(Color::Sky)
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('member_position_id')
                            ->label('Cargo')
                            ->searchable()
                            ->options(MemberPosition::all()->pluck('title', 'id')),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->icon('fas-link-slash'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
