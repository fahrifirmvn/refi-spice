<?php

namespace App\Models\Concerns;

use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;

/** Mencatat siapa mengubah apa pada konten yang diedit lewat panel admin. */
trait RecordsRevisions
{
    /** @var list<string> */
    private static array $revisionIgnoredColumns = ['created_at', 'updated_at', 'deleted_at', 'order'];

    protected static function bootRecordsRevisions(): void
    {
        static::created(fn (Model $model) => $model->recordRevision(ContentRevision::EVENT_CREATED));

        static::updated(function (Model $model): void {
            $changes = array_diff_key($model->getChanges(), array_flip(self::$revisionIgnoredColumns));

            if ($changes === []) {
                return;
            }

            $model->recordRevision(
                ContentRevision::EVENT_UPDATED,
                array_intersect_key($model->getRawOriginal(), $changes),
                $changes,
            );
        });

        static::deleted(fn (Model $model) => $model->recordRevision(ContentRevision::EVENT_DELETED));
    }

    public function revisionLabel(): string
    {
        foreach (['name', 'title', 'label', 'full_name'] as $attribute) {
            if (filled($this->getAttribute($attribute))) {
                return (string) $this->getAttribute($attribute);
            }
        }

        return class_basename($this).' #'.$this->getKey();
    }

    /**
     * @param  array<string, mixed>|null  $old
     * @param  array<string, mixed>|null  $new
     */
    private function recordRevision(string $event, ?array $old = null, ?array $new = null): void
    {
        // Hanya perubahan oleh pengguna yang login (bukan seeder/proses sistem).
        if (! auth()->check()) {
            return;
        }

        ContentRevision::create([
            'user_id' => auth()->id(),
            'subject_type' => static::class,
            'subject_id' => $this->getKey(),
            'subject_label' => $this->revisionLabel(),
            'event' => $event,
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }
}
