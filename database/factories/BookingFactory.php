<?php

// File: database/factories/BookingFactory.php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generer en booking_date i fremtiden (neste 30 dager)
        $bookingDate = fake()->dateTimeBetween('now', '+30 days');
        
        // Generer start_time mellom 09:00 og 16:00
        $startHour = fake()->numberBetween(9, 16);
        $startMinute = fake()->randomElement([0, 30]);
        $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
        
        // Generer end_time (1-2 timer etter start_time)
        $duration = fake()->randomElement([1, 1.5, 2]);
        $endHour = $startHour + floor($duration);
        $endMinute = $startMinute + (($duration - floor($duration)) * 60);
        
        // Håndter minutt overflow
        if ($endMinute >= 60) {
            $endHour += 1;
            $endMinute -= 60;
        }
        
        $endTime = sprintf('%02d:%02d:00', $endHour, $endMinute);

        return [
            'resource_id' => Resource::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => $this->generatePhoneNumber(),
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => fake()->optional(0.5)->sentence(8),
            'status' => 'confirmed',
        ];
    }

    /**
     * Generer et realistisk telefonnummer (norsk eller internasjonalt format).
     *
     * @return string
     */
    protected function generatePhoneNumber(): string
    {
        $formats = [
            // Norsk format
            '+47 ' . fake()->numerify('### ## ###'),
            // Internasjonalt format
            '+' . fake()->numberBetween(1, 99) . ' ' . fake()->numerify('### ### ####'),
            // Enkel norsk
            fake()->numerify('### ## ###'),
        ];

        return fake()->randomElement($formats);
    }

    /**
     * Indiker at bookingen skal være i fortiden.
     */
    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $bookingDate = fake()->dateTimeBetween('-30 days', '-1 day');
            
            return [
                'booking_date' => $bookingDate,
            ];
        });
    }

    /**
     * Indiker at bookingen skal være pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indiker at bookingen skal være cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indiker at bookingen skal tilhøre en spesifikk ressurs.
     */
    public function forResource(Resource $resource): static
    {
        return $this->state(fn (array $attributes) => [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Indiker at bookingen skal være på en spesifikk dato.
     */
    public function onDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_date' => $date,
        ]);
    }

    /**
     * Indiker at bookingen skal være på et spesifikt tidspunkt.
     */
    public function atTime(string $startTime, string $endTime): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}

// BookingFactory genererer test-data for Booking modellen.
// Genererer realistiske booking-tider (09:00-18:00) med 1-2 timers varighet.
// Støtter norske og internasjonale telefonnummerformater.
