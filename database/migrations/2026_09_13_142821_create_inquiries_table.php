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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company_name');
            $table->string('destination_country');
            $table->string('commodity_interest');
            $table->string('volume_estimate');
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['new', 'contacted', 'closed'])->default('new');
            $table->timestamp('email_sent_at')->nullable();
            $table->text('email_failed_reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
