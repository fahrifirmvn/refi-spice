<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentRevision extends Model
{
    public const EVENT_CREATED = 'created';

    public const EVENT_UPDATED = 'updated';

    public const EVENT_DELETED = 'deleted';

    protected $fillable = ['user_id', 'subject_type', 'subject_id', 'subject_label', 'event', 'old_values', 'new_values'];

    /** @var array<class-string<Model>, string> */
    public const TYPE_LABELS = [
        Product::class => 'Produk',
        Partner::class => 'Mitra & Klien',
        Certification::class => 'Sertifikasi',
        ProcessStep::class => 'Tahap Proses',
        Testimonial::class => 'Testimoni',
        CompanyStat::class => 'Angka Perusahaan',
        Milestone::class => 'Perjalanan Perusahaan',
        ProductTag::class => 'Label Produk',
        PageContent::class => 'Isi Halaman Depan',
    ];

    protected function casts(): array
    {
        return ['old_values' => 'array', 'new_values' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo()->withoutGlobalScopes();
    }

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->subject_type] ?? class_basename($this->subject_type);
    }

    public function canBeRestored(): bool
    {
        return $this->event === self::EVENT_UPDATED
            && filled($this->old_values)
            && class_exists($this->subject_type)
            && $this->subject_type::withoutGlobalScopes()->whereKey($this->subject_id)->exists();
    }

    /** Mengembalikan isi record ke nilai sebelum perubahan ini. */
    public function restore(): void
    {
        $this->subject_type::withoutGlobalScopes()->findOrFail($this->subject_id)
            ->forceFill($this->old_values)
            ->save();
    }
}
