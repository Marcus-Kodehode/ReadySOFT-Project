<?php

// File: database/migrations/2024_12_01_000001_create_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Oppretter tenants tabell for multi-tenant systemet.
     * Hver tenant representerer en kunde som får sin egen bookingside.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('business_type', 100);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index('slug');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

// Migration for tenants table - core table for multi-tenancy system
