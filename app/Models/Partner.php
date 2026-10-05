<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasContentImage;
use App\Models\Concerns\HasOrder;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Partner extends Model implements HasMedia
{
    /** @use HasFactory<PartnerFactory> */
    use HasActiveScope, HasContentImage, HasFactory, HasOrder, SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected function contentImageCollectionName(): string
    {
        return 'logo';
    }

    protected function legacyContentImageAttribute(): string
    {
        return 'logo_path';
    }
}
