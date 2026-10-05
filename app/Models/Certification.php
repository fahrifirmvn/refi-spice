<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasContentImage;
use App\Models\Concerns\HasOrder;
use Database\Factories\CertificationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Certification extends Model implements HasMedia
{
    /** @use HasFactory<CertificationFactory> */
    use HasActiveScope, HasContentImage, HasFactory, HasOrder;

    protected $fillable = [
        'name',
        'logo_path',
        'is_active',
        'is_verified',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'order' => 'integer',
    ];

    protected function contentImageCollectionName(): string
    {
        return 'logo';
    }

    public function scopeApprovedForPublic(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    protected function legacyContentImageAttribute(): string
    {
        return 'logo_path';
    }
}
