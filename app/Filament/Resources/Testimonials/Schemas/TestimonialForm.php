<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('quote')
                    ->label('Kutipan Testimoni')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('author_name')
                    ->label('Nama Pemberi Testimoni')
                    ->required(),
                TextInput::make('author_title')
                    ->label('Jabatan')
                    ->default(null),
                TextInput::make('author_company')
                    ->label('Perusahaan')
                    ->default(null),
                TextInput::make('standard_badge')
                    ->label('Lencana Standar (Opsional)')
                    ->helperText('Mis. "DIN EN ISO 22000 / HACCP".')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, testimoni ini disembunyikan dari halaman publik.')
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
