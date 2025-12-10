<?php

// File: tests/Feature/AdminNavigationTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin navigation functionality
 * 
 * Verifiserer at admin-brukere ser admin-navigasjon med logo,
 * "Admin Panel" tekst, Dashboard og Tenants lenker.
 */
class AdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at admin-bruker ser admin-navigasjon med "Admin Panel" tekst
     */
    public function test_admin_user_sees_admin_navigation_with_admin_panel_text(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Besøk admin dashboard
        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        
        // Verifiser at "Admin Panel" tekst vises
        $response->assertSee('Admin Panel');
    }

    /**
     * Test at admin-navigasjon har Dashboard og Tenants lenker
     */
    public function test_admin_navigation_has_dashboard_and_tenants_links(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        
        // Verifiser at Dashboard og Tenants lenker finnes
        $response->assertSee('Dashboard');
        $response->assertSee('Tenants');
        
        // Verifiser at lenkene peker til riktige routes
        $response->assertSee(route('admin.dashboard'), false);
        $response->assertSee(route('admin.tenants'), false);
    }

    /**
     * Test at admin-navigasjon har Logout i dropdown
     */
    public function test_admin_navigation_has_logout_in_dropdown(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        
        // Verifiser at brukerens navn vises i dropdown trigger
        $response->assertSee($admin->name);
        
        // Verifiser at Log Out lenke finnes
        $response->assertSee('Log Out');
    }

    /**
     * Test at tenant-bruker IKKE ser admin-navigasjon
     */
    public function test_tenant_user_does_not_see_admin_navigation(): void
    {
        // Opprett plan og tenant
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create(['active' => true]);
        
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);

        // Opprett tenant-bruker
        $tenantUser = User::factory()->create([
            'role' => 'tenant_admin',
            'tenant_id' => $tenant->id,
        ]);

        // Besøk tenant dashboard
        $response = $this->actingAs($tenantUser)->get('/dashboard');

        $response->assertStatus(200);
        
        // Verifiser at "Admin Panel" tekst IKKE vises
        $response->assertDontSee('Admin Panel');
        
        // Verifiser at tenant-navigasjon vises i stedet
        $response->assertSee('Resources');
        $response->assertSee('Bookings');
        $response->assertSee('SMS Settings');
    }

    /**
     * Test at admin-navigasjon har logo
     */
    public function test_admin_navigation_has_logo(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        
        // Verifiser at logo-bilde finnes
        $response->assertSee('images/icons/readysoft2.png', false);
        $response->assertSee('Schedulo Logo', false);
    }
}

// Test suite som verifiserer at admin-brukere ser admin-navigasjon
// med logo, "Admin Panel" tekst, Dashboard og Tenants lenker.
