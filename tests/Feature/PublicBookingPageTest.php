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

test('it includes date selection field in booking modal', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'date-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/date-test-salon');

    // Assert: Check that date input field exists
    $response->assertStatus(200);
    $response->assertSee('type="date"', false);
    $response->assertSee('id="booking_date"', false);
    $response->assertSee('x-model="bookingDate"', false);
    
    // Assert: Check that min date attribute is set
    $response->assertSee(':min="minDate"', false);
    
    // Assert: Check that Alpine.js data includes bookingDate and minDate
    $response->assertSee('bookingDate', false);
    $response->assertSee('minDate', false);
    
    // Assert: Check that label and helper text are present
    $response->assertSee('Select Date');
    $response->assertSee('Choose a date for your booking');
    
    // Assert: Check that Next button is present and disabled when no date or time slot selected
    $response->assertSee('Next');
    $response->assertSee(':disabled="!bookingDate || !selectedTimeSlot"', false);
});

test('it includes time slot selection field in booking modal', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'time-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/time-test-salon');

    // Assert: Check that time slot dropdown exists
    $response->assertStatus(200);
    $response->assertSee('id="time_slot"', false);
    $response->assertSee('x-model="selectedTimeSlot"', false);
    
    // Assert: Check that Alpine.js data includes time slot related variables
    $response->assertSee('availableSlots', false);
    $response->assertSee('selectedTimeSlot', false);
    $response->assertSee('loadingSlots', false);
    
    // Assert: Check that fetchAvailableSlots function exists
    $response->assertSee('fetchAvailableSlots', false);
    
    // Assert: Check that label is present
    $response->assertSee('Select Time');
    
    // Assert: Check that loading state message exists
    $response->assertSee('Loading available times...');
    
    // Assert: Check that no slots available message exists
    $response->assertSee('No available time slots for this date');
    
    // Assert: Check that Next button is disabled when no time slot selected
    $response->assertSee(':disabled="!bookingDate || !selectedTimeSlot"', false);
});

test('it includes customer information fields in booking modal', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'customer-info-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/customer-info-test-salon');

    // Assert: Check that customer name field exists
    $response->assertStatus(200);
    $response->assertSee('id="customer_name"', false);
    $response->assertSee('x-model="customerName"', false);
    $response->assertSee('Full Name');
    
    // Assert: Check that customer email field exists
    $response->assertSee('id="customer_email"', false);
    $response->assertSee('x-model="customerEmail"', false);
    $response->assertSee('Email Address');
    
    // Assert: Check that customer phone field exists
    $response->assertSee('id="customer_phone"', false);
    $response->assertSee('x-model="customerPhone"', false);
    $response->assertSee('Phone Number');
    
    // Assert: Check that customer notes field exists (optional)
    $response->assertSee('id="customer_notes"', false);
    $response->assertSee('x-model="customerNotes"', false);
    $response->assertSee('Additional Notes');
    $response->assertSee('(Optional)');
    
    // Assert: Check that Alpine.js data includes customer info variables
    $response->assertSee('customerName', false);
    $response->assertSee('customerEmail', false);
    $response->assertSee('customerPhone', false);
    $response->assertSee('customerNotes', false);
    
    // Assert: Check that validation function exists
    $response->assertSee('validateCustomerInfo', false);
    $response->assertSee('validateEmail', false);
    $response->assertSee('validatePhone', false);
    
    // Assert: Check that errors object exists
    $response->assertSee('errors: {}', false);
});

test('it includes step indicator in booking modal', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'step-indicator-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/step-indicator-test-salon');

    // Assert: Check that step indicator exists
    $response->assertStatus(200);
    $response->assertSee('currentStep', false);
    $response->assertSee('Date & Time', false);
    $response->assertSee('Your Info', false);
    
    // Assert: Check that step navigation functions exist
    $response->assertSee('nextStep', false);
    $response->assertSee('previousStep', false);
    $response->assertSee('resetModal', false);
    
    // Assert: Check that Back button exists
    $response->assertSee('Back');
    
    // Assert: Check that Complete Booking button exists
    $response->assertSee('Complete Booking');
});

test('it includes validation for customer information fields', function () {
    // Arrange: Create a tenant with a resource
    $tenant = Tenant::factory()->create([
        'slug' => 'validation-test-salon',
        'active' => true,
    ]);

    $resource = Resource::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Test Chair',
        'active' => true,
    ]);

    // Act: Visit the public booking page
    $response = $this->get('/validation-test-salon');

    // Assert: Check that validation error messages are present
    $response->assertStatus(200);
    $response->assertSee('x-show="errors.name"', false);
    $response->assertSee('x-show="errors.email"', false);
    $response->assertSee('x-show="errors.phone"', false);
    
    // Assert: Check that required field indicators are present
    $response->assertSee('<span class="text-red-500">*</span>', false);
    
    // Assert: Check that validation triggers on blur
    $response->assertSee('@blur="validateCustomerInfo()"', false);
    
    // Assert: Check that Complete Booking button is disabled when fields are empty
    $response->assertSee(':disabled="!customerName || !customerEmail || !customerPhone"', false);
});
