<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasContentImage
{
    use InteractsWithMedia;

    abstract protected function contentImageCollectionName(): string;

    abstract protected function legacyContentImageAttribute(): string;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->contentImageCollectionName())
            ->useDisk((string) config('media-library.disk_name', 'public'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->withResponsiveImages()
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $collection = $this->contentImageCollectionName();

        $this->addMediaConversion('thumbnail')
            ->performOnCollections($collection)
            ->width(400)
            ->format('webp')
            ->quality(82)
            ->withResponsiveImages()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->performOnCollections($collection)
            ->width(800)
            ->format('webp')
            ->quality(82)
            ->withResponsiveImages()
            ->nonQueued();

        $this->addMediaConversion('large')
            ->performOnCollections($collection)
            ->width(1600)
            ->format('webp')
            ->quality(82)
            ->withResponsiveImages()
            ->nonQueued();
    }

    public function contentImageUrl(string $conversion = 'medium'): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl($this->contentImageCollectionName(), $conversion);

        if ($mediaUrl !== '') {
            return $mediaUrl;
        }

        $legacyPath = $this->getAttribute($this->legacyContentImageAttribute());

        return filled($legacyPath)
            ? Storage::disk((string) config('media-library.disk_name', 'public'))->url($legacyPath)
            : null;
    }

    public function contentImageSrcset(string $conversion = 'medium'): ?string
    {
        $media = $this->getFirstMedia($this->contentImageCollectionName());
        $srcset = $media?->getSrcset($conversion);

        return filled($srcset) ? $srcset : null;
    }

    public function scopeMissingContentImage(Builder $query): Builder
    {
        $collection = $this->contentImageCollectionName();
        $legacyAttribute = $this->legacyContentImageAttribute();

        return $query
            ->whereDoesntHave('media', fn (Builder $mediaQuery): Builder => $mediaQuery->where('collection_name', $collection))
            ->where(function (Builder $legacyQuery) use ($legacyAttribute): void {
                $legacyQuery
                    ->whereNull($legacyAttribute)
                    ->orWhere($legacyAttribute, '');
            });
    }

    public function scopeWithContentImage(Builder $query): Builder
    {
        $collection = $this->contentImageCollectionName();
        $legacyAttribute = $this->legacyContentImageAttribute();

        return $query->where(function (Builder $imageQuery) use ($collection, $legacyAttribute): void {
            $imageQuery
                ->whereHas('media', fn (Builder $mediaQuery): Builder => $mediaQuery->where('collection_name', $collection))
                ->orWhere(function (Builder $legacyQuery) use ($legacyAttribute): void {
                    $legacyQuery
                        ->whereNotNull($legacyAttribute)
                        ->where($legacyAttribute, '!=', '');
                });
        });
    }
}
