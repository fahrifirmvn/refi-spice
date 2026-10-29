<?php

namespace App\Filament\Support;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\ImageColumn;

/** Satu pengaturan upload gambar untuk semua resource konten (format, ukuran, editor potong). */
class ContentImageUpload
{
    public static function make(string $collection, string $label, ?string $aspectRatio = null): SpatieMediaLibraryFileUpload
    {
        $upload = SpatieMediaLibraryFileUpload::make($collection)
            ->label($label)
            ->image()
            ->collection($collection)
            ->conversion('thumbnail')
            ->responsiveImages()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize(5120)
            ->imageEditor()
            ->helperText('JPG, PNG, atau WebP, maksimal 5 MB.');

        return $aspectRatio === null
            ? $upload
            : $upload->imageEditorAspectRatios([$aspectRatio])->helperText("JPG, PNG, atau WebP, maksimal 5 MB. Potongan {$aspectRatio} paling sesuai.");
    }

    public static function column(string $label = 'Foto'): ImageColumn
    {
        return ImageColumn::make('content_image')
            ->label($label)
            ->state(fn ($record): ?string => $record->contentImageUrl('thumbnail'));
    }
}
