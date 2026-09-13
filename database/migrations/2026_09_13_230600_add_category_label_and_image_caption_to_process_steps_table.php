<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('process_steps', function (Blueprint $table) {
            $table->string('category_label')->nullable()->after('title');
            $table->string('image_caption')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('process_steps', function (Blueprint $table) {
            $table->dropColumn(['category_label', 'image_caption']);
        });
    }
};
