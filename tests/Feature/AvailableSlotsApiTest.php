<?php

use App\Models\Tenant;
use App\Models\Resource;
use App\Models\ResourceAvailability;
use Carbon\Carbon;

test('api returns available slots for a resource on a specific date', function () {
    // Arrange: Create a tenant with a resource and availability
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'capacity' => 1,
    ]);

    // Add availability for Monday (day_of_week = 1)
    ResourceAvailability::create([
        'resource_id' => $resource->id,
        'day_of_week' => 1, // Monday
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    // Get next Monday
    $nextMonday = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');

    // Act: Call the API endpoint
    $response = $this->getJson("/api/available-slots?resource_id={$resource->id}&date={$nextMonday}");

    // Assert: Check that the response is successful
    $response->assertStatus(200);
    $response->assertJsonStructure(['slots']);

    // Assert: Check that slots are returned
    $slots = $response->json('slots');
    expect($slots)->toBeArray();
    expect($slots)->toContain('09:00', '09:30', '10:00', '10:30', '11:00', '11:30');
});

test('api returns empty array when no availability defined', function () {
    // Arrange: Create a tenant with a resource but no availability
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $date = Carbon::now()->addDay()->format('Y-m-d');

    // Act: Call the API endpoint
    $response = $this->getJson("/api/available-slots?resource_id={$resource->id}&date={$date}");

    // Assert: Check that the response is successful
    $response->assertStatus(200);
    $response->assertJson(['slots' => []]);
});

test('api validates required parameters', function () {
    // Act: Call the API without required parameters
    $response = $this->getJson('/api/available-slots');

    // Assert: Check that validation fails
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['resource_id', 'date']);
});

test('api validates resource exists', function () {
    // Act: Call the API with non-existent resource
    $date = Carbon::now()->addDay()->format('Y-m-d');
    $response = $this->getJson("/api/available-slots?resource_id=99999&date={$date}");

    // Assert: Check that validation fails
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['resource_id']);
});

test('api validates date is not in the past', function () {
    // Arrange: Create a resource
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    // Act: Call the API with a past date
    $pastDate = Carbon::now()->subDay()->format('Y-m-d');
    $response = $this->getJson("/api/available-slots?resource_id={$resource->id}&date={$pastDate}");

    // Assert: Check that validation fails
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['date']);
});
