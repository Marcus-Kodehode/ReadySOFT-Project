<?php
// File: database/migrations/2024_12_01_000004_create_resources_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Oppretter resources tabell for å lagre bookbare ressurser (hytter, stoler, rom, etc.)
     * som tilhører en tenant.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                  ->constrained('tenants')
                  ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type', 100);
            $table->integer('capacity')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index('tenant_id');
            $table->index(['tenant_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};

// Migration for resources table - stores bookable resources (cabins, chairs, rooms, etc.) belonging to a tenant
