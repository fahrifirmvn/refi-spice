<?php

namespace App\Filament\Resources\PageContents\Pages;

use App\Filament\Resources\PageContents\PageContentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPageContent extends EditRecord
{
    protected static string $resource = PageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewWebsite')
                ->label('Lihat halaman depan')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(route('home'))
                ->openUrlInNewTab(),
        ];
    }
}
