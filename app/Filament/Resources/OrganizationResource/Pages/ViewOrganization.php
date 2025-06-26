<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return "Registro Nº {$this->record->registration_id}";
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Editar organización')
                ->icon('fas-pencil'),
        ];
    }
}
