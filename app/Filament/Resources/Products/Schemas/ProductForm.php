<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi produk')
                    ->description('Mulai dari informasi yang akan dilihat calon buyer di kartu produk.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('latin_name')
                            ->label('Nama Latin')
                            ->helperText('Nama ilmiah komoditas, mis. "Myristica fragrans".')
                            ->default(null),
                        TextInput::make('export_readiness')
                            ->label('Status Kesiapan Ekspor')
                            ->helperText('Teks status, mis. "Ready to Export".')
                            ->default(null),
                        TextInput::make('origin_region')
                            ->label('Wilayah Asal')
                            ->default(null),
                        TextInput::make('origin_label')
                            ->label('Label Asal (Tampilan)')
                            ->helperText('Contoh: "Banda Neira, Maluku".')
                            ->default(null),
                        TextInput::make('lot_number')
                            ->label('Nomor Lot')
                            ->helperText('Kode lot atau batch yang tampil pada kartu produk.')
                            ->default(null),
                        Textarea::make('sensory_profile')
                            ->label('Profil Sensorik')
                            ->helperText('Deskripsi aroma dan rasa khas komoditas ini.')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Foto, label, dan spesifikasi')
                    ->description('Lengkapi bahan yang membantu buyer memahami produk dengan cepat.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('primary_image')
                            ->label('Foto Utama Produk')
                            ->image()
                            ->collection('primary_image')
                            ->conversion('thumbnail')
                            ->responsiveImages()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imageEditorAspectRatios(['4:3'])
                            ->helperText('Gunakan JPG, PNG, atau WebP maksimal 5 MB. Potongan 4:3 paling sesuai untuk kartu produk.'),
                        TextInput::make('primary_image_alt')
                            ->label('Deskripsi Foto untuk Aksesibilitas')
                            ->helperText('Contoh: "Biji pala Banda grade ekspor".')
                            ->maxLength(255),
                        Select::make('tags')
                            ->label('Label Produk')
                            ->helperText('Pilih label yang relevan, mis. Single-Origin atau Handpicked.')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                        Repeater::make('specifications')
                            ->label('Spesifikasi Produk')
                            ->relationship()
                            ->schema([
                                TextInput::make('spec_key')
                                    ->label('Nama Spesifikasi')
                                    ->required(),
                                TextInput::make('spec_value')
                                    ->label('Nilai')
                                    ->required(),
                            ])
                            ->orderColumn('order')
                            ->reorderableWithButtons()
                            ->addActionLabel('Tambah Spesifikasi')
                            ->columns(2)
                            ->columnSpanFull()
                            ->defaultItems(0),
                    ])
                    ->columns(2),
                Section::make('Pengaturan publikasi')
                    ->description('Pengaturan ini menentukan bagaimana produk tampil di website.')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Tampilkan di Website')
                            ->helperText('Nonaktifkan untuk menyembunyikan produk dari halaman publik.')
                            ->required()
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Produk Unggulan')
                            ->helperText('Produk unggulan muncul di barisan atas Koleksi Produk.')
                            ->required()
                            ->default(false),
                        TextInput::make('order')
                            ->label('Urutan Tampil')
                            ->helperText('Angka lebih kecil tampil lebih dulu.')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('slug')
                            ->label('Alamat Produk (URL)')
                            ->helperText('Gunakan huruf kecil dan tanda hubung, mis. "lada-hitam-lampung".')
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
