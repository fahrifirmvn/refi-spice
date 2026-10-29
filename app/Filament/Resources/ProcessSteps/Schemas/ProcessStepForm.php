<?php

namespace App\Filament\Resources\ProcessSteps\Schemas;

use App\Filament\Support\ContentImageUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProcessStepForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('step_number')
                    ->label('Nomor Tahap')
                    ->helperText('Mis. 1, 2, 3, 4 sesuai urutan alur "Dari Kebun ke Dunia".')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->label('Judul Tahap')
                    ->helperText('Mis. "Ditanam Lestari", "Dipanen Tangan".')
                    ->required(),
                TextInput::make('category_label')
                    ->label('Label Kategori')
                    ->helperText('Teks kecil di atas judul, mis. "Agroforestri Tradisional".')
                    ->default(null),
                Textarea::make('description')
                    ->label('Penjelasan Tahap')
                    ->required()
                    ->columnSpanFull(),
                ContentImageUpload::make('illustration', 'Foto Ilustrasi'),
                TextInput::make('image_caption')
                    ->label('Keterangan Foto')
                    ->default(null),
                TextInput::make('data_label')
                    ->label('Label Angka Pendukung')
                    ->helperText('Mis. "Minyak Atsiri Optimal".')
                    ->default(null),
                TextInput::make('data_value')
                    ->label('Nilai Angka Pendukung')
                    ->helperText('Mis. "> 11.5% Essence".')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, tahap ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
