<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\ContentImageUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Mitra/Klien')
                    ->helperText('Mis. "Nordic Spice Co. — Kopenhagen, Denmark".')
                    ->required(),
                ContentImageUpload::make('logo', 'Logo Mitra'),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, logo mitra ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
