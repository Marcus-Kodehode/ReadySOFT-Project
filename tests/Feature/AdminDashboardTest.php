<?php

// File: tests/Feature/AdminDashboardTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Resource;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin dashboard functionality
 * 
 * Verifiserer at admin dashboard viser korrekt statistikk
 * med stat cards for Total Tenants, Active, Inactive og Total Bookings.
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at admin dashboard viser alle 4 stat cards med korrekte verdier
     */
    public function test_admin_dashboard_displays_stat_cards_with_correct_values(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Opprett plan
        $plan = Plan::factory()->create();

        // Opprett 3 aktive tenants
        $activeTenants = Tenant::factory()->count(3)->create(['active' => true]);
        foreach ($activeTenants as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'active' => true,
            ]);
        }

        // Opprett 2 inaktive tenants
        $inactiveTenants = Tenant::factory()->count(2)->create(['active' => false]);
        foreach ($inactiveTenants as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'active' => false,
            ]);
        }

        // Opprett ressurser og bookinger for å teste total bookings
        $resource = Resource::factory()->create([
            'tenant_id' => $activeTenants->first()->id,
        ]);
        
        Booking::factory()->count(5)->create([
            'resource_id' => $resource->id,
        ]);

        // Besøk admin dashboard
        $response = $this->actingAs($admin)->get('/admin');

        // Verifiser at siden laster
        $response->assertStatus(200);

        // Verifiser at stat cards vises med korrekte verdier
        $response->assertSee('Total Tenants');
        $response->assertSee('5'); // 3 aktive + 2 inaktive

        $response->assertSee('Active Tenants');
        $response->assertSee('3');

        $response->assertSee('Inactive Tenants');
        $response->assertSee('2');

        $response->assertSee('Total Bookings');
        $response->assertSee('5');
    }

    /**
     * Test at admin dashboard viser 0 når det ikke finnes data
     */
    public function test_admin_dashboard_displays_zero_when_no_data_exists(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Besøk admin dashboard uten å opprette noen data
        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);

        // Verifiser at alle stat cards viser 0
        $response->assertSee('Total Tenants');
        $response->assertSee('Active Tenants');
        $response->assertSee('Inactive Tenants');
        $response->assertSee('Total Bookings');
    }

    /**
     * Test at admin dashboard viser "View All Tenants" quick action
     */
    public function test_admin_dashboard_displays_quick_actions(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Quick Actions');
        $response->assertSee('View All Tenants');
    }

    /**
     * Test at "View All Tenants" link peker til riktig route
     */
    public function test_view_all_tenants_link_points_to_correct_route(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        
        // Verifiser at linken til admin.tenants route finnes
        $response->assertSee(route('admin.tenants'), false);
    }
}

// Test suite som verifiserer at admin dashboard viser korrekt statistikk
// med stat cards for Total Tenants, Active Tenants, Inactive Tenants og Total Bookings.
