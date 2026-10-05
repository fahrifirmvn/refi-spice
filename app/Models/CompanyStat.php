<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasOrder;
use Database\Factories\CompanyStatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyStat extends Model
{
    /** @use HasFactory<CompanyStatFactory> */
    use HasActiveScope, HasFactory, HasOrder;

    protected $fillable = [
        'label',
        'code_label',
        'value',
        'suffix',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
