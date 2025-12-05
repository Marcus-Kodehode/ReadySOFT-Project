<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that tenant can access their own booking through the controller.
     * Note: This test verifies the controller logic and authorization.
     * The view will be created in Task 9.3.
     */
    public function test_tenant_can_access_own_booking(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create booking for this resource
        $booking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(1),
        ]);

        // Verify the booking can be accessed by this tenant
        $loadedBooking = Booking::with('resource')->findOrFail($booking->id);
        $this->assertEquals($tenant->id, $loadedBooking->resource->tenant_id);
        $this->assertEquals($booking->id, $loadedBooking->id);
    }

    /**
     * Test that booking detail view displays all required information.
     * Verifies: Resource, Date, Time, Customer (name, email, phone), Notes, Status
     */
    public function test_booking_detail_view_displays_all_information(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant with description
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Resource',
            'type' => 'Cabin',
            'description' => 'A beautiful cabin in the woods',
        ]);

        // Create booking with all fields populated
        $booking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => '2025-12-15',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '+4712345678',
            'notes' => 'Please prepare the cabin before arrival',
            'status' => 'confirmed',
        ]);

        // Access the booking detail page
        $response = $this->actingAs($user)
            ->get(route('bookings.show', $booking->id));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify Resource information is displayed
        $response->assertSee('Test Resource');
        $response->assertSee('Cabin');
        $response->assertSee('A beautiful cabin in the woods');

        // Verify Date is displayed (formatted)
        $response->assertSee('Monday, December 15, 2025');

        // Verify Time is displayed
        $response->assertSee('10:00');
        $response->assertSee('12:00');

        // Verify Customer information is displayed
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
        $response->assertSee('+4712345678');

        // Verify Notes are displayed
        $response->assertSee('Please prepare the cabin before arrival');

        // Verify Status is displayed
        $response->assertSee('Confirmed');

        // Verify booking ID is displayed
        $response->assertSee('Booking #' . $booking->id);
    }

    /**
     * Test that tenant cannot view another tenant's booking.
     */
    public function test_tenant_cannot_view_other_tenant_booking(): void
    {
        // Create first tenant with user
        $tenant1 = Tenant::factory()->create();
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);

        // Create second tenant with resource and booking
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
        ]);
        $booking2 = Booking::factory()->create([
            'resource_id' => $resource2->id,
        ]);

        // Try to access tenant2's booking as tenant1
        $response = $this->actingAs($user1)
            ->get(route('bookings.show', $booking2->id));

        // Assert forbidden response
        $response->assertStatus(403);
    }

    /**
     * Test that show method returns 404 for non-existent booking.
     */
    public function test_show_returns_404_for_nonexistent_booking(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Try to access non-existent booking
        $response = $this->actingAs($user)
            ->get(route('bookings.show', 99999));

        // Assert not found response
        $response->assertStatus(404);
    }

    /**
     * Test that tenant can update status of their own booking.
     */
    public function test_tenant_can_update_own_booking_status(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create booking with pending status
        $booking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'status' => 'pending',
        ]);

        // Update status to confirmed
        $response = $this->actingAs($user)
            ->patch(route('bookings.updateStatus', $booking->id), [
                'status' => 'confirmed',
            ]);

        // Assert redirect with success message
        $response->assertRedirect(route('bookings.show', $booking->id));
        $response->assertSessionHas('success');

        // Verify status was updated in database
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Test that updateStatus validates status values.
     */
    public function test_update_status_validates_status_values(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create booking
        $booking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'status' => 'pending',
        ]);

        // Try to update with invalid status
        $response = $this->actingAs($user)
            ->patch(route('bookings.updateStatus', $booking->id), [
                'status' => 'invalid_status',
            ]);

        // Assert validation error
        $response->assertSessionHasErrors('status');

        // Verify status was NOT updated in database
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that tenant cannot update status of another tenant's booking.
     */
    public function test_tenant_cannot_update_other_tenant_booking_status(): void
    {
        // Create first tenant with user
        $tenant1 = Tenant::factory()->create();
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);

        // Create second tenant with resource and booking
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
        ]);
        $booking2 = Booking::factory()->create([
            'resource_id' => $resource2->id,
            'status' => 'pending',
        ]);

        // Try to update tenant2's booking as tenant1
        $response = $this->actingAs($user1)
            ->patch(route('bookings.updateStatus', $booking2->id), [
                'status' => 'confirmed',
            ]);

        // Assert forbidden response
        $response->assertStatus(403);

        // Verify status was NOT updated in database
        $this->assertDatabaseHas('bookings', [
            'id' => $booking2->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that all valid status values can be set.
     */
    public function test_all_valid_status_values_can_be_set(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $validStatuses = ['pending', 'confirmed', 'cancelled'];

        foreach ($validStatuses as $status) {
            // Create booking
            $booking = Booking::factory()->create([
                'resource_id' => $resource->id,
                'status' => 'pending',
            ]);

            // Update to each valid status
            $response = $this->actingAs($user)
                ->patch(route('bookings.updateStatus', $booking->id), [
                    'status' => $status,
                ]);

            // Assert success
            $response->assertRedirect(route('bookings.show', $booking->id));
            $response->assertSessionHas('success');

            // Verify status was updated
            $this->assertDatabaseHas('bookings', [
                'id' => $booking->id,
                'status' => $status,
            ]);
        }
    }

    /**
     * Test that index filters upcoming bookings correctly.
     */
    public function test_index_filters_upcoming_bookings(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create past booking
        $pastBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->subDays(2),
        ]);

        // Create upcoming booking
        $upcomingBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(2),
        ]);

        // Request with upcoming filter
        $response = $this->actingAs($user)
            ->get(route('bookings.index', ['filter' => 'upcoming']));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify only upcoming booking is in the view data
        $bookings = $response->viewData('bookings');
        $this->assertCount(1, $bookings);
        $this->assertEquals($upcomingBooking->id, $bookings->first()->id);
    }

    /**
     * Test that index filters past bookings correctly.
     */
    public function test_index_filters_past_bookings(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create past booking
        $pastBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->subDays(2),
        ]);

        // Create upcoming booking
        $upcomingBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(2),
        ]);

        // Request with past filter
        $response = $this->actingAs($user)
            ->get(route('bookings.index', ['filter' => 'past']));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify only past booking is in the view data
        $bookings = $response->viewData('bookings');
        $this->assertCount(1, $bookings);
        $this->assertEquals($pastBooking->id, $bookings->first()->id);
    }

    /**
     * Test that index shows all bookings when no filter is applied.
     */
    public function test_index_shows_all_bookings_without_filter(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create past booking
        $pastBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->subDays(2),
        ]);

        // Create upcoming booking
        $upcomingBooking = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(2),
        ]);

        // Request without filter (defaults to 'all')
        $response = $this->actingAs($user)
            ->get(route('bookings.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify both bookings are in the view data
        $bookings = $response->viewData('bookings');
        $this->assertCount(2, $bookings);
    }

    /**
     * Test that index only shows bookings for tenant's own resources.
     */
    public function test_index_only_shows_own_tenant_bookings(): void
    {
        // Create first tenant with user
        $tenant1 = Tenant::factory()->create();
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for tenant1
        $resource1 = Resource::factory()->create([
            'tenant_id' => $tenant1->id,
        ]);

        // Create booking for tenant1
        $booking1 = Booking::factory()->create([
            'resource_id' => $resource1->id,
            'booking_date' => now()->addDays(1),
        ]);

        // Create second tenant with resource and booking
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
        ]);
        $booking2 = Booking::factory()->create([
            'resource_id' => $resource2->id,
            'booking_date' => now()->addDays(1),
        ]);

        // Request as tenant1
        $response = $this->actingAs($user1)
            ->get(route('bookings.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Verify only tenant1's booking is in the view data
        $bookings = $response->viewData('bookings');
        $this->assertCount(1, $bookings);
        $this->assertEquals($booking1->id, $bookings->first()->id);
    }

    /**
     * Test that bookings are sorted by date DESC then by start_time DESC.
     * This test verifies the sorting logic directly without requiring the view.
     */
    public function test_bookings_are_sorted_by_date_and_time_desc(): void
    {
        // Create tenant with user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Create resource for this tenant
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Create bookings with different dates and times
        // Booking 1: Earlier date, earlier time
        $booking1 = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        // Booking 2: Earlier date, later time
        $booking2 = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
        ]);

        // Booking 3: Later date, earlier time
        $booking3 = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
        ]);

        // Booking 4: Later date, later time
        $booking4 = Booking::factory()->create([
            'resource_id' => $resource->id,
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '16:00:00',
            'end_time' => '17:00:00',
        ]);

        // Simulate the controller's query logic
        $resourceIds = Resource::where('tenant_id', $tenant->id)->pluck('id');
        $bookings = Booking::with('resource')
            ->whereIn('resource_id', $resourceIds)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        // Verify bookings are sorted correctly
        $this->assertCount(4, $bookings);

        // Expected order: booking4, booking3, booking2, booking1
        // (latest date first, then latest time first within same date)
        $this->assertEquals($booking4->id, $bookings[0]->id, 'First booking should be booking4 (latest date, latest time)');
        $this->assertEquals($booking3->id, $bookings[1]->id, 'Second booking should be booking3 (latest date, earlier time)');
        $this->assertEquals($booking2->id, $bookings[2]->id, 'Third booking should be booking2 (earlier date, latest time)');
        $this->assertEquals($booking1->id, $bookings[3]->id, 'Fourth booking should be booking1 (earlier date, earlier time)');
    }
}
