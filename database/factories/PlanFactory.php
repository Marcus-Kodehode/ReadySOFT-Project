<?php

// File: database/factories/PlanFactory.php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Plan Factory
 * 
 * Factory for generating test Plan instances.
 */
class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Basic Plan', 'Pro Plan', 'Enterprise Plan']),
            'description' => $this->faker->sentence(),
            'features' => [
                'max_resources' => $this->faker->numberBetween(5, 50),
                'max_bookings_per_month' => $this->faker->numberBetween(100, 1000),
            ],
        ];
    }
}

// Factory for generating test Plan data
