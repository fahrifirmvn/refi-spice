<?php

namespace App\Filament\Resources\Certifications\Schemas;

use App\Filament\Support\ContentImageUpload;
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
                ContentImageUpload::make('logo', 'Logo/Lencana'),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, badge ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
