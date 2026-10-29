<?php

namespace App\Filament\Resources\ProductTags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->helperText('Mis. "Single-Origin", "Handpicked", "Non-GMO".')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, kategori ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
