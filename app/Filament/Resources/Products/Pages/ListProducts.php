<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\ProductTags\ProductTagResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageTags')
                ->label('Kelola Label Produk')
                ->icon('heroicon-o-tag')
                ->color('gray')
                ->url(ProductTagResource::getUrl('index')),
            CreateAction::make()->label('Tambah Produk'),
        ];
    }
}
