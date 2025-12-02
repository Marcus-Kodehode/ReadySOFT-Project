<?php

// File: database/factories/ResourceFactory.php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resourceTypes = [
            'Cabin',
            'Chair',
            'Room',
            'Treatment Room',
            'Other',
        ];

        $typeNames = [
            'Cabin' => ['Mountain Cabin', 'Lake Cabin', 'Forest Cabin', 'Luxury Cabin'],
            'Chair' => ['Styling Chair', 'Barber Chair', 'Treatment Chair', 'Massage Chair'],
            'Room' => ['Meeting Room', 'Conference Room', 'Private Room', 'Studio Room'],
            'Treatment Room' => ['Massage Room', 'Therapy Room', 'Spa Room', 'Wellness Room'],
            'Other' => ['Resource A', 'Resource B', 'Resource C', 'Resource D'],
        ];

        $type = fake()->randomElement($resourceTypes);
        $name = fake()->randomElement($typeNames[$type]);

        return [
            'tenant_id' => Tenant::factory(),
            'name' => $name,
            'description' => fake()->optional(0.8)->sentence(10),
            'type' => $type,
            'capacity' => fake()->numberBetween(1, 10),
            'active' => true,
        ];
    }

    /**
     * Indiker at ressursen skal være inaktiv.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indiker at ressursen skal ha en spesifikk type.
     */
    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indiker at ressursen skal tilhøre en spesifikk tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * Indiker at ressursen skal ha en spesifikk kapasitet.
     */
    public function withCapacity(int $capacity): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => $capacity,
        ]);
    }
}

// ResourceFactory genererer test-data for Resource modellen.
// Genererer realistiske navn basert på ressurstype.
// Støtter ulike typer: Cabin, Chair, Room, Treatment Room, Other.
