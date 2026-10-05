<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\FileUpload;
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
                FileUpload::make('logo_path')
                    ->label('Logo Mitra')
                    ->image()
                    ->directory('partners')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, logo mitra ini disembunyikan dari halaman publik.')
                    ->required()
                    ->default(true),
                TextInput::make('order')
                    ->label('Urutan Tampil')
                    ->helperText('Angka lebih kecil tampil lebih dulu.')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
