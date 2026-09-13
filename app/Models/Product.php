<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasActiveScope, HasFactory, HasOrder, SoftDeletes;

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
}
