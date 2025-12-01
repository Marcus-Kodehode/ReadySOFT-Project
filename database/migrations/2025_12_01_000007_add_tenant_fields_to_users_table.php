<?php

// File: database/migrations/2024_12_01_000007_add_tenant_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Legger til tenant_id og role kolonner i users tabell for multi-tenant støtte.
     * tenant_id kobler brukeren til en tenant (kunde).
     * role definerer brukerens tilgangsnivå (admin eller tenant_admin).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Legg til tenant_id kolonne (nullable fordi admin-brukere ikke har tenant)
            $table->foreignId('tenant_id')
                  ->nullable()
                  ->after('password')
                  ->constrained('tenants')
                  ->onDelete('cascade');
            
            // Legg til role kolonne med enum verdier
            $table->enum('role', ['admin', 'tenant_admin'])
                  ->default('tenant_admin')
                  ->after('tenant_id');
            
            // Index på tenant_id for raskere queries
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Fjern foreign key constraint først
            $table->dropForeign(['tenant_id']);
            
            // Fjern index
            $table->dropIndex(['tenant_id']);
            
            // Fjern kolonnene
            $table->dropColumn(['tenant_id', 'role']);
        });
    }
};

// Migration som utvider users tabell med multi-tenant støtte og rollebasert tilgangskontroll
