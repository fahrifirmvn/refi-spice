<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessStepFactory> */
    use HasActiveScope, HasFactory, HasOrder;

    protected $fillable = [
        'step_number',
        'title',
        'category_label',
        'description',
        'image_path',
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
}
