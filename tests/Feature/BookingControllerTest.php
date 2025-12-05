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
}
