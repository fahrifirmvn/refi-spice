<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasContentImage;
use App\Models\Concerns\HasOrder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    /** @use HasFactory<ProductFactory> */
    use HasActiveScope, HasContentImage, HasFactory, HasOrder, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'latin_name',
        'lot_number',
        'origin_region',
        'origin_label',
        'sensory_profile',
        'export_readiness',
        'primary_image_path',
        'primary_image_alt',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class);
    }

    protected function contentImageCollectionName(): string
    {
        return 'primary_image';
    }

    protected function legacyContentImageAttribute(): string
    {
        return 'primary_image_path';
    }
}
