<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\OrganizationStateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatuses extends ManageRecords
{
    protected static string $resource = OrganizationStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
