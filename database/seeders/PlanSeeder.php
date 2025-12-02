<?php

// File: database/seeders/PlanSeeder.php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Plan Seeder
 * 
 * Seeder for å opprette standard abonnementsplaner.
 * Denne seederen er idempotent og kan kjøres flere ganger uten å lage duplikater.
 */
class PlanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opprett Basic Plan hvis den ikke allerede eksisterer
        Plan::firstOrCreate(
            ['name' => 'Basic Plan'],
            [
                'description' => 'Basic plan with essential features for small businesses',
                'features' => [
                    'max_resources' => 10,
                ],
            ]
        );
    }
}

// PlanSeeder oppretter standard abonnementsplaner i systemet
