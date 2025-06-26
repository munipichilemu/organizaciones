<?php

namespace App\Filament\Resources\LeaderResource\Pages;

use App\Filament\Resources\LeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLeaders extends ManageRecords
{
    protected static string $resource = LeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar dirigente')
                ->icon('fas-plus')
                ->createAnother(false)
                ->modalHeading('Registrar dirigente')
                ->modalSubmitActionLabel('Registrar'),
        ];
    }
}
