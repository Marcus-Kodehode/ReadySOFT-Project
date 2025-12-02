<?php

// File: tests/Feature/BookingFactoryTest.php

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Tenant;

describe('BookingFactory', function () {
    
    test('genererer booking med alle påkrevde felter', function () {
        $booking = Booking::factory()->create();
        
        expect($booking->customer_name)->toBeString()->not->toBeEmpty();
        expect($booking->customer_email)->toBeString()->not->toBeEmpty();
        expect($booking->customer_phone)->toBeString()->not->toBeEmpty();
        expect($booking->booking_date)->not->toBeNull();
        expect($booking->start_time)->toBeString()->not->toBeEmpty();
        expect($booking->end_time)->toBeString()->not->toBeEmpty();
        expect($booking->status)->toBe('confirmed');
    });

    test('genererer gyldig email format', function () {
        $booking = Booking::factory()->create();
        
        expect($booking->customer_email)->toMatch('/^[^\s@]+@[^\s@]+\.[^\s@]+$/');
    });

    test('genererer telefonnummer i gyldig format', function () {
        $bookings = Booking::factory()->count(10)->create();
        
        foreach ($bookings as $booking) {
            // Telefonnummer skal inneholde tall og kan ha +, mellomrom
            expect($booking->customer_phone)->toMatch('/^[\+\d\s]+$/');
        }
    });

    test('booking_date er i fremtiden som standard', function () {
        $booking = Booking::factory()->create();
        
        expect($booking->booking_date->isFuture())->toBeTrue();
    });

    test('start_time er før end_time', function () {
        $booking = Booking::factory()->create();
        
        $start = strtotime($booking->start_time);
        $end = strtotime($booking->end_time);
        
        expect($end)->toBeGreaterThan($start);
    });

    test('genererer booking innenfor arbeidstid (09:00-18:00)', function () {
        $bookings = Booking::factory()->count(20)->create();
        
        foreach ($bookings as $booking) {
            $startHour = (int) substr($booking->start_time, 0, 2);
            $endHour = (int) substr($booking->end_time, 0, 2);
            
            expect($startHour)->toBeGreaterThanOrEqual(9);
            expect($startHour)->toBeLessThanOrEqual(16);
            expect($endHour)->toBeLessThanOrEqual(18);
        }
    });

    test('past() state genererer booking i fortiden', function () {
        $booking = Booking::factory()->past()->create();
        
        expect($booking->booking_date->isPast())->toBeTrue();
    });

    test('pending() state setter status til pending', function () {
        $booking = Booking::factory()->pending()->create();
        
        expect($booking->status)->toBe('pending');
    });

    test('cancelled() state setter status til cancelled', function () {
        $booking = Booking::factory()->cancelled()->create();
        
        expect($booking->status)->toBe('cancelled');
    });

    test('forResource() knytter booking til spesifikk ressurs', function () {
        $tenant = Tenant::factory()->create();
        $resource = Resource::factory()->forTenant($tenant)->create();
        
        $booking = Booking::factory()->forResource($resource)->create();
        
        expect($booking->resource_id)->toBe($resource->id);
        expect($booking->resource->id)->toBe($resource->id);
    });

    test('onDate() setter spesifikk booking dato', function () {
        $targetDate = '2025-12-25';
        $booking = Booking::factory()->onDate($targetDate)->create();
        
        expect($booking->booking_date->format('Y-m-d'))->toBe($targetDate);
    });

    test('atTime() setter spesifikke tider', function () {
        $startTime = '10:00:00';
        $endTime = '11:30:00';
        
        $booking = Booking::factory()->atTime($startTime, $endTime)->create();
        
        expect($booking->start_time)->toBe($startTime);
        expect($booking->end_time)->toBe($endTime);
    });

    test('notes er valgfri', function () {
        // Factory genererer notes 50% av tiden
        $bookings = Booking::factory()->count(20)->create();
        
        $withNotes = $bookings->filter(fn($b) => !is_null($b->notes))->count();
        $withoutNotes = $bookings->filter(fn($b) => is_null($b->notes))->count();
        
        // Verifiser at noen har notes og noen ikke har
        expect($withNotes)->toBeGreaterThan(0);
        expect($withoutNotes)->toBeGreaterThan(0);
    });

    test('genererer flere bookinger med forskjellige verdier', function () {
        $bookings = Booking::factory()->count(5)->create();
        
        $names = $bookings->pluck('customer_name')->toArray();
        $emails = $bookings->pluck('customer_email')->toArray();
        
        // Alle navn og emails skal være unike
        expect(count($names))->toBe(count(array_unique($names)));
        expect(count($emails))->toBe(count(array_unique($emails)));
    });

    test('kombinerer flere states', function () {
        $booking = Booking::factory()
            ->past()
            ->cancelled()
            ->onDate('2025-11-15')
            ->create();
        
        expect($booking->booking_date->format('Y-m-d'))->toBe('2025-11-15');
        expect($booking->status)->toBe('cancelled');
    });

    test('booking tilhører en ressurs', function () {
        $booking = Booking::factory()->create();
        
        expect($booking->resource)->toBeInstanceOf(Resource::class);
        expect($booking->resource_id)->not->toBeNull();
    });

    test('genererer gyldig status fra enum', function () {
        $validStatuses = ['pending', 'confirmed', 'cancelled'];
        
        $bookings = collect([
            Booking::factory()->create(),
            Booking::factory()->pending()->create(),
            Booking::factory()->cancelled()->create(),
        ]);
        
        foreach ($bookings as $booking) {
            expect($booking->status)->toBeIn($validStatuses);
        }
    });

});

// BookingFactory test suite dokumenterer og verifiserer at factory
// genererer gyldige test-data for Booking modellen.
// Tester dekker: alle påkrevde felter, tidsvalidering, state modifiers,
// og relasjoner til ressurser.

