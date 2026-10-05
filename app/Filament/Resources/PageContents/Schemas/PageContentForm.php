<?php

namespace App\Filament\Resources\PageContents\Schemas;

use App\Filament\Support\PageContentCatalog;
use App\Models\PageContent;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bagian halaman')
                    ->schema([
                        Placeholder::make('section_context')
                            ->label('Anda sedang mengubah')
                            ->content(fn (?PageContent $record): string => $record
                                ? PageContentCatalog::sectionLabel($record->section_key).' — '.PageContentCatalog::sectionDescription($record->section_key)
                                : '')
                            ->columnSpanFull(),
                    ]),
                Section::make('Isi yang tampil di website')
                    ->description('Perubahan akan tampil di halaman depan setelah disimpan.')
                    ->schema([
                        Textarea::make('field_value')
                            ->label(fn (?PageContent $record): string => PageContentCatalog::fieldLabel($record?->section_key, $record?->field_key))
                            ->helperText(fn (?PageContent $record): ?string => PageContentCatalog::fieldHelper($record?->section_key, $record?->field_key))
                            ->rows(5)
                            ->default(null)
                            ->visible(fn (?PageContent $record): bool => ! $record?->isOpenGraphImageField())
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('open_graph_image')
                            ->label('Gambar Pratinjau Media Sosial')
                            ->helperText('Unggah JPG, PNG, atau WebP maksimal 5 MB. Rasio 1200 × 630 paling aman untuk WhatsApp, LinkedIn, dan Facebook.')
                            ->image()
                            ->collection(PageContent::OPEN_GRAPH_IMAGE_COLLECTION)
                            ->conversion('open_graph')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1200:630'])
                            ->visible(fn (?PageContent $record): bool => (bool) $record?->isOpenGraphImageField())
                            ->columnSpanFull(),
                    ]),
                Section::make('Periksa hasilnya')
                    ->schema([
                        Placeholder::make('preview_hint')
                            ->label('Langkah berikutnya')
                            ->content('Simpan perubahan, lalu klik tombol “Lihat halaman depan” di bagian atas untuk memeriksa hasilnya di website.')
                            ->columnSpanFull(),
                    ])
                    ->compact(),
            ]);
    }
}
