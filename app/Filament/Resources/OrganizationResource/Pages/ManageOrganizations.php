<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrganizations extends ManageRecords
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar organización')
                ->icon('fas-plus')
                ->createAnother(false)
                ->modalHeading('Registrar organización')
                ->modalSubmitActionLabel('Registrar'),
        ];
    }
}
