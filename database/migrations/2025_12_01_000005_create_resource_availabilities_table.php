<?php
// File: database/migrations/2024_12_01_000005_create_resource_availabilities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Oppretter resource_availabilities tabell for å lagre åpningstider per ukedag
     * for hver ressurs.
     */
    public function up(): void
    {
        Schema::create('resource_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                  ->constrained('resources')
                  ->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday, ..., 6=Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            // Index for performance when querying availability by resource and day
            $table->index(['resource_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_availabilities');
    }
};

// Migration for resource_availabilities table - stores opening hours per weekday for each resource
