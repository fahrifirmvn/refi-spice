<?php

namespace App\Models;

use App\Filament\Support\PageContentCatalog;
use App\Models\Concerns\RecordsRevisions;
use Database\Factories\PageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PageContent extends Model implements HasMedia
{
    /** @use HasFactory<PageContentFactory> */
    use HasFactory, InteractsWithMedia, RecordsRevisions;

    public const OPEN_GRAPH_IMAGE_COLLECTION = 'open_graph_image';

    public const LOGO_COLLECTION = 'brand_logo';

    protected $fillable = [
        'section_key',
        'field_key',
        'field_value',
    ];

    /**
     * Convenience lookup for a single editable content field, e.g.
     * PageContent::get('hero', 'headline').
     */
    public static function get(string $section, string $field, ?string $default = null): ?string
    {
        return static::query()
            ->where('section_key', $section)
            ->where('field_key', $field)
            ->value('field_value') ?? $default;
    }

    public function revisionLabel(): string
    {
        return PageContentCatalog::sectionLabel($this->section_key).' — '.PageContentCatalog::fieldLabel($this->section_key, $this->field_key);
    }

    public function isOpenGraphImageField(): bool
    {
        return $this->section_key === 'seo' && $this->field_key === 'og_image_path';
    }

    public function isLogoField(): bool
    {
        return $this->section_key === 'brand' && $this->field_key === 'logo_path';
    }

    public function isMediaField(): bool
    {
        return $this->isOpenGraphImageField() || $this->isLogoField();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::LOGO_COLLECTION)
            ->useDisk((string) config('media-library.disk_name', 'public'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection(self::OPEN_GRAPH_IMAGE_COLLECTION)
            ->useDisk((string) config('media-library.disk_name', 'public'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('open_graph')
            ->performOnCollections(self::OPEN_GRAPH_IMAGE_COLLECTION)
            ->fit(Fit::Crop, 1200, 630)
            ->format('jpg')
            ->quality(85)
            ->nonQueued();

        $this->addMediaConversion('logo')
            ->performOnCollections(self::LOGO_COLLECTION)
            ->fit(Fit::Max, 256, 256)
            ->format('webp')
            ->nonQueued();
    }

    public function logoUrl(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl(self::LOGO_COLLECTION, 'logo');

        return $mediaUrl !== '' ? $mediaUrl : null;
    }

    public function openGraphImageUrl(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl(self::OPEN_GRAPH_IMAGE_COLLECTION, 'open_graph');

        if ($mediaUrl !== '') {
            return $mediaUrl;
        }

        $legacyPath = trim((string) $this->field_value);

        if ($legacyPath === '') {
            return null;
        }

        if (Str::startsWith($legacyPath, ['http://', 'https://', '/'])) {
            return $legacyPath;
        }

        return Storage::disk((string) config('media-library.disk_name', 'public'))->url($legacyPath);
    }
}
