<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared "order" column scope for content models managed via drag-sortable
 * lists in the admin panel (PRD Section 5.5).
 */
trait HasOrder
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
