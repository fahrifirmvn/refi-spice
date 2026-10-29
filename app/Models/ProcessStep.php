<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasContentImage;
use App\Models\Concerns\HasOrder;
use App\Models\Concerns\RecordsRevisions;
use Database\Factories\ProcessStepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class ProcessStep extends Model implements HasMedia
{
    /** @use HasFactory<ProcessStepFactory> */
    use HasActiveScope, HasContentImage, HasFactory, HasOrder, RecordsRevisions;

    protected $fillable = [
        'step_number',
        'title',
        'category_label',
        'description',
        'image_path',
        'image_alt',
        'image_caption',
        'data_label',
        'data_value',
        'is_active',
        'order',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected function contentImageCollectionName(): string
    {
        return 'illustration';
    }

    protected function legacyContentImageAttribute(): string
    {
        return 'image_path';
    }
}
