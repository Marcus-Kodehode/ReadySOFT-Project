<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the public booking page displays tenant information.
     */
    public function test_show_displays_tenant_information(): void
    {
        // Arrange: Create a tenant with a resource
        $tenant = Tenant::factory()->create([
            'name' => 'Test Salon',
            'slug' => 'test-salon',
            'business_type' => 'Hair Salon',
            'description' => 'A test salon description',
        ]);

        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Chair 1',
            'active' => true,
        ]);

        // Act: Visit the public booking page
        $response = $this->get('/test-salon');

        // Assert: Check that the page displays tenant information
        $response->assertStatus(200);
        $response->assertSee('Test Salon');
        $response->assertSee('Hair Salon');
        $response->assertSee('A test salon description');
        $response->assertSee('Chair 1');
    }

    /**
     * Test that the public booking page returns 404 for non-existent slug.
     */
    public function test_show_returns_404_for_nonexistent_slug(): void
    {
        // Act: Visit a non-existent slug
        $response = $this->get('/nonexistent-slug');

        // Assert: Should return 404
        $response->assertStatus(404);
    }

    /**
     * Test that the public booking page eager loads resources.
     */
    public function test_show_eager_loads_resources(): void
    {
        // Arrange: Create a tenant with multiple resources
        $tenant = Tenant::factory()->create([
            'slug' => 'eager-test',
        ]);

        Resource::factory()->count(3)->create([
            'tenant_id' => $tenant->id,
            'active' => true,
        ]);

        // Act: Visit the public booking page
        $response = $this->get('/eager-test');

        // Assert: Page loads successfully
        $response->assertStatus(200);
        
        // Verify that resources are loaded (check for "Book Now" buttons)
        $response->assertSee('Book Now');
    }

    /**
     * Test that inactive resources are not displayed.
     */
    public function test_show_does_not_display_inactive_resources(): void
    {
        // Arrange: Create a tenant with active and inactive resources
        $tenant = Tenant::factory()->create([
            'slug' => 'inactive-test',
        ]);

        $activeResource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Active Resource',
            'active' => true,
        ]);

        $inactiveResource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Inactive Resource',
            'active' => false,
        ]);

        // Act: Visit the public booking page
        $response = $this->get('/inactive-test');

        // Assert: Only active resource is displayed
        $response->assertStatus(200);
        $response->assertSee('Active Resource');
        $response->assertDontSee('Inactive Resource');
    }
}
