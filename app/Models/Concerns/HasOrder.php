<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared "order" column scope for content models managed via drag-sortable
 * lists in the admin panel (PRD Section 5.5).
 */
trait HasOrder
{
    protected static function bootHasOrder(): void
    {
        static::creating(function ($model): void {
            if (! array_key_exists('order', $model->getAttributes())) {
                $model->order = ((int) static::query()->max('order')) + 1;
            }
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
