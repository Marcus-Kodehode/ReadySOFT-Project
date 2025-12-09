<?php

// File: database/factories/SubscriptionFactory.php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating Subscription test data
 * 
 * Genererer subscription data for testing.
 * Kobler automatisk til tenant og plan.
 */
class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'plan_id' => Plan::factory(),
            'active' => true,
            'active_from' => now(),
            'active_to' => null,
        ];
    }

    /**
     * Indicate that the subscription is inactive.
     *
     * @return static
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the subscription is active.
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }
}

// Factory for generating Subscription test data - kobler tenants til plans med aktiv status
