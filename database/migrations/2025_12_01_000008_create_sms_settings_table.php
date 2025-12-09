<?php
// File: database/migrations/2025_12_01_000008_create_sms_settings_table.php

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
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                  ->constrained('tenants')
                  ->onDelete('cascade');
            $table->text('api_key');
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            // Unique constraint: hver tenant kan kun ha én SMS settings rad
            $table->unique('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};

// Migration for SMS settings tabell - lagrer Teletopia API nøkkel per tenant
