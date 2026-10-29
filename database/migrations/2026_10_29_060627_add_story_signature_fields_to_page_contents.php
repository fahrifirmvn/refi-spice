<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ROWS = [
        'signature_name' => 'PT Refi Rempah Nusantara',
        'signature_tagline' => 'Dedikasi untuk Bumi Nusantara — Single-Origin Exporter',
        'spec_label' => 'Spesifikasi Komoditas',
        'spec_value' => 'Grade A • Handpicked Sorted',
    ];

    public function up(): void
    {
        foreach (self::ROWS as $field => $value) {
            DB::table('page_contents')->insertOrIgnore([
                'section_key' => 'cerita_kami',
                'field_key' => $field,
                'field_value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('page_contents')
            ->where('section_key', 'cerita_kami')
            ->whereIn('field_key', array_keys(self::ROWS))
            ->delete();
    }
};
