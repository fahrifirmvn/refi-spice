<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use App\Models\Concerns\HasOrder;
use App\Models\Concerns\RecordsRevisions;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use HasActiveScope, HasFactory, HasOrder, RecordsRevisions, SoftDeletes;

    protected $fillable = [
        'quote',
        'author_name',
        'author_title',
        'author_company',
        'standard_badge',
        'is_active',
        'is_verified',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeApprovedForPublic(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }
}
