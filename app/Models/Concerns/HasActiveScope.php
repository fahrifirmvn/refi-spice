<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared "is_active" column scope for content models that can be
 * toggled on/off from the admin panel without deleting the record
 * (PRD Section 5.5).
 */
trait HasActiveScope
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
