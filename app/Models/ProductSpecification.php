<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecification extends Model
{
    /** @use HasFactory<\Database\Factories\ProductSpecificationFactory> */
    use HasFactory, HasOrder;

    protected $fillable = [
        'product_id',
        'spec_key',
        'spec_value',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
