<?php

use App\Models\Tenant;
use App\Models\Resource;

test('it displays resource grid on public booking page', function () {
    // Arrange: Create a tenant with resources
    $tenant = Tenant::factory()->create([
        'name' => 'Test Salon',
        'slug' => 'test-salon',
        'business_type' => 'Hair Salon',
        'description' => 'A test salon',
        'active' => true,
    ]);

    $resource1 = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Chair 1',
        'description' => 'First chair',
        'type' => 'Chair',
        'capacity' => 1,
        'active' => true,
    ]);

    $resource2 = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Chair 2',
        'description' => 'Second chair',
        'type' => 'Chair',
        'capacity' => 1,
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/test-salon');

    // Assert: Check that the page loads successfully
    $response->assertStatus(200);

    // Assert: Check that tenant info is displayed
    $response->assertSee('Test Salon');
    $response->assertSee('Hair Salon');
    $response->assertSee('A test salon');

    // Assert: Check that the grid container exists
    $response->assertSee('grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3', false);

    // Assert: Check that resources are displayed
    $response->assertSee('Chair 1');
    $response->assertSee('First chair');
    $response->assertSee('Chair 2');
    $response->assertSee('Second chair');

    // Assert: Check that Book Now buttons are present
    $response->assertSee('Book Now');
});

test('it displays empty state when no resources', function () {
    // Arrange: Create a tenant without resources
    $tenant = Tenant::factory()->create([
        'name' => 'Empty Salon',
        'slug' => 'empty-salon',
        'business_type' => 'Hair Salon',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/empty-salon');

    // Assert: Check that empty state message is displayed
    $response->assertStatus(200);
    $response->assertSee('No resources available for booking at this time.');
});

test('it only displays active resources', function () {
    // Arrange: Create a tenant with active and inactive resources
    $tenant = Tenant::factory()->create([
        'slug' => 'mixed-salon',
        'active' => true,
    ]);

    $activeResource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Active Chair',
        'active' => true,
    ]);

    $inactiveResource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Inactive Chair',
        'active' => false,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/mixed-salon');

    // Assert: Check that only active resource is displayed
    $response->assertStatus(200);
    $response->assertSee('Active Chair');
    $response->assertDontSee('Inactive Chair');
});

test('it includes alpine.js modal functionality for booking', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'modal-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/modal-test-salon');

    // Assert: Check that Alpine.js data attributes are present
    $response->assertStatus(200);
    $response->assertSee('x-data', false);
    $response->assertSee('modalOpen', false);
    $response->assertSee('selectedResourceId', false);
    
    // Assert: Check that Book Now button has click handler
    $response->assertSee('@click', false);
    $response->assertSee('modalOpen = true', false);
    
    // Assert: Check that modal structure exists
    $response->assertSee('x-show="modalOpen"', false);
    $response->assertSee('x-cloak', false);
});
