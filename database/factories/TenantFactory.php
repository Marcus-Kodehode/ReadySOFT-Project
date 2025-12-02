<?php

// File: database/factories/TenantFactory.php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessTypes = [
            'Cabin Rental',
            'Hair Salon',
            'Spa & Wellness',
            'Room Rental',
            'Other',
        ];

        $name = fake()->company();
        
        return [
            'name' => $name,
            'slug' => fn (array $attributes) => $this->generateUniqueSlug($attributes['name'] ?? $name),
            'business_type' => fake()->randomElement($businessTypes),
            'description' => fake()->optional(0.7)->sentence(12),
            'active' => true,
        ];
    }

    /**
     * Generer en unik slug basert på navn.
     * Konverterer til lowercase, erstatter mellomrom med bindestrek,
     * og håndterer norske tegn (æ, ø, å).
     *
     * @param string $name
     * @return string
     */
    protected function generateUniqueSlug(string $name): string
    {
        // Konverter norske tegn
        $slug = str_replace(
            ['æ', 'ø', 'å', 'Æ', 'Ø', 'Å'],
            ['ae', 'o', 'a', 'ae', 'o', 'a'],
            $name
        );

        // Bruk Laravel sin Str::slug helper
        $slug = Str::slug($slug);

        // Sjekk om slug allerede eksisterer
        $originalSlug = $slug;
        $counter = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Indiker at tenant skal være inaktiv.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indiker at tenant skal ha en spesifikk business type.
     */
    public function businessType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'business_type' => $type,
        ]);
    }
}

// TenantFactory genererer test-data for Tenant modellen.
// Slug genereres automatisk fra navn og sikres unik.
// Støtter norske tegn (æ, ø, å) i slug-generering.
