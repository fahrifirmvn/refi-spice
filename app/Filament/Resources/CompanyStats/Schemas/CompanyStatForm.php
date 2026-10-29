<?php

namespace App\Filament\Resources\CompanyStats\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyStatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Judul Statistik')
                    ->helperText('Teks di bawah angka, mis. "Negara Destinasi Ekspor".')
                    ->required(),
                TextInput::make('code_label')
                    ->label('Kode Arsip (Dekoratif)')
                    ->helperText('Kode kecil dekoratif di atas angka, mis. "Global Jurisdiction".')
                    ->default(null),
                TextInput::make('value')
                    ->label('Angka')
                    ->helperText('Nilai angka yang ditampilkan besar, mis. "32".')
                    ->required(),
                TextInput::make('suffix')
                    ->label('Simbol Tambahan')
                    ->helperText('Simbol di belakang angka, mis. "+" atau "%".')
                    ->default(null),
                Textarea::make('description')
                    ->label('Penjelasan Singkat')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, statistik ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
