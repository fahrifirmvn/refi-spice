<?php

namespace App\Filament\Resources\CompanyStats\Pages;

use App\Filament\Resources\CompanyStats\CompanyStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyStats extends ManageRecords
{
    protected static string $resource = CompanyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
