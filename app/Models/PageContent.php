<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    /** @use HasFactory<\Database\Factories\PageContentFactory> */
    use HasFactory;

    protected $fillable = [
        'section_key',
        'field_key',
        'field_value',
    ];

    /**
     * Convenience lookup for a single editable content field, e.g.
     * PageContent::get('hero', 'headline').
     */
    public static function get(string $section, string $field, ?string $default = null): ?string
    {
        return static::query()
            ->where('section_key', $section)
            ->where('field_key', $field)
            ->value('field_value') ?? $default;
    }
}
