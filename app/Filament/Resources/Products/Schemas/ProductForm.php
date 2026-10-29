<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\ContentImageUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Produk')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Informasi')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->helperText('Hanya ini yang wajib diisi, sisanya bisa dilengkapi nanti.')
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('latin_name')
                                    ->label('Nama Latin')
                                    ->helperText('Nama ilmiah komoditas, mis. "Myristica fragrans".'),
                                TextInput::make('origin_label')
                                    ->label('Asal Produk')
                                    ->helperText('Contoh: "Banda Neira, Maluku".'),
                                Textarea::make('sensory_profile')
                                    ->label('Profil Sensorik')
                                    ->helperText('Deskripsi aroma dan rasa khas komoditas ini.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Tab::make('Foto & Spesifikasi')
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                ContentImageUpload::make('primary_image', 'Foto Utama Produk', '4:3'),
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
                        Tab::make('Tampil di Website')
                            ->icon(Heroicon::OutlinedEye)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Tampilkan di Website')
                                    ->helperText('Nonaktifkan untuk menyembunyikan produk dari halaman publik.')
                                    ->default(true),
                                Toggle::make('is_featured')
                                    ->label('Tampilkan sebagai Produk Unggulan')
                                    ->helperText('Produk unggulan muncul di barisan atas Koleksi Produk.')
                                    ->default(false),
                                Section::make('Pengaturan lanjutan')
                                    ->description('Biasanya tidak perlu diubah. Urutan produk diatur dengan geser-urut di daftar produk.')
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('Alamat Produk (URL)')
                                            ->helperText('Kosongkan agar dibuat otomatis dari nama produk.')
                                            ->unique(ignoreRecord: true)
                                            ->alphaDash(),
                                        TextInput::make('export_readiness')
                                            ->label('Status Kesiapan Ekspor')
                                            ->helperText('Teks status, mis. "Ready to Export".'),
                                        TextInput::make('origin_region')
                                            ->label('Wilayah Asal')
                                            ->helperText('Wilayah umum yang dipakai sebagai teks cadangan.'),
                                        TextInput::make('lot_number')
                                            ->label('Nomor Lot')
                                            ->helperText('Kode lot atau batch pada kartu produk.'),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed(),
                            ]),
                    ]),
            ]);
    }
}
