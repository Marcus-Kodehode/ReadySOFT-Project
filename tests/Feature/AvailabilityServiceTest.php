<?php

use App\Models\Booking;
use App\Models\Resource;
use App\Models\ResourceAvailability;
use App\Models\Tenant;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new AvailabilityService();
});

test('getAvailableSlots returns empty array when no availability defined', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    $date = Carbon::now()->addDay();
    $slots = $this->service->getAvailableSlots($resource, $date);
    
    expect($slots)->toBeArray()->toBeEmpty();
});

test('getAvailableSlots returns all slots when no bookings exist', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    // Opprett åpningstider for mandag (1)
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
    ]);
    
    // Finn neste mandag
    $nextMonday = Carbon::now()->next(Carbon::MONDAY);
    $slots = $this->service->getAvailableSlots($resource, $nextMonday);
    
    expect($slots)
        ->toBeArray()
        ->not->toBeEmpty()
        ->toContain('09:00')
        ->toContain('09:30')
        ->toContain('10:00')
        ->toContain('10:30');
});

test('getAvailableSlots excludes booked slots', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    // Opprett åpningstider for mandag
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
    ]);
    
    $nextMonday = Carbon::now()->next(Carbon::MONDAY);
    
    // Opprett en booking fra 09:00 til 10:00 (enklere test case)
    Booking::create([
        'resource_id' => $resource->id,
        'customer_name' => 'Test Customer',
        'customer_email' => 'test@example.com',
        'customer_phone' => '12345678',
        'booking_date' => $nextMonday->format('Y-m-d'),
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'status' => 'confirmed',
    ]);
    
    $slots = $this->service->getAvailableSlots($resource, $nextMonday);
    
    // 09:00 og 09:30 skal være opptatt, 10:00 og 10:30 skal være ledig
    expect($slots)
        ->not->toContain('09:00')
        ->not->toContain('09:30')
        ->toContain('10:00')
        ->toContain('10:30');
});

test('isTimeSlotAvailable returns false when no availability defined', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    $date = Carbon::now()->addDay();
    $available = $this->service->isTimeSlotAvailable($resource, $date, '09:00', '10:00');
    
    expect($available)->toBeFalse();
});

test('isTimeSlotAvailable returns true when slot is free', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    // Opprett åpningstider for mandag
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
    ]);
    
    $nextMonday = Carbon::now()->next(Carbon::MONDAY);
    $available = $this->service->isTimeSlotAvailable($resource, $nextMonday, '09:00', '10:00');
    
    expect($available)->toBeTrue();
});

test('isTimeSlotAvailable returns false when slot is booked', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    // Opprett åpningstider for mandag
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
    ]);
    
    $nextMonday = Carbon::now()->next(Carbon::MONDAY);
    
    // Opprett en booking fra 10:00 til 11:00
    Booking::create([
        'resource_id' => $resource->id,
        'customer_name' => 'Test Customer',
        'customer_email' => 'test@example.com',
        'customer_phone' => '12345678',
        'booking_date' => $nextMonday->format('Y-m-d'),
        'start_time' => '10:00:00',
        'end_time' => '11:00:00',
        'status' => 'confirmed',
    ]);
    
    // Prøv å booke eksakt samme tid (helt overlappende)
    $available = $this->service->isTimeSlotAvailable($resource, $nextMonday, '10:00', '11:00');
    
    expect($available)->toBeFalse();
});

test('isTimeSlotAvailable returns false when outside opening hours', function () {
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    
    // Opprett åpningstider for mandag (09:00 - 17:00)
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
    ]);
    
    $nextMonday = Carbon::now()->next(Carbon::MONDAY);
    
    // Prøv å booke utenfor åpningstider
    $available = $this->service->isTimeSlotAvailable($resource, $nextMonday, '08:00', '09:00');
    
    expect($available)->toBeFalse();
});
