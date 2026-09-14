<?php

namespace App\Filament\Resources\CompanyStats\Pages;

use App\Filament\Resources\CompanyStats\CompanyStatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyStat extends EditRecord
{
    protected static string $resource = CompanyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
