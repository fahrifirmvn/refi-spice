<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    /** @use HasFactory<\Database\Factories\CertificationFactory> */
    use HasActiveScope, HasFactory, HasOrder;

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
}
