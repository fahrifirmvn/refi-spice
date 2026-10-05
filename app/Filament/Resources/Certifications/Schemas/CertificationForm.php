<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Sertifikasi')
                    ->helperText('Mis. "HACCP Certified", "ISO 22000".')
                    ->required(),
                FileUpload::make('logo_path')
                    ->label('Logo/Lencana')
                    ->image()
                    ->directory('certifications')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, badge ini disembunyikan dari halaman publik.')
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
