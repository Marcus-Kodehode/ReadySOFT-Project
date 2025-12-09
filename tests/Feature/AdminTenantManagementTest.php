<?php

// File: tests/Feature/AdminTenantManagementTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin tenant management functionality
 * 
 * Verifiserer at admin kan se liste over alle tenants
 * med Name, Slug, Business Type, Status, Created, Actions kolonner.
 */
class AdminTenantManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at tenant management siden viser tabell med alle kolonner
     */
    public function test_tenant_management_displays_table_with_all_columns(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Opprett plan
        $plan = Plan::factory()->create();

        // Opprett en tenant med subscription
        $tenant = Tenant::factory()->create([
            'name' => 'Test Salon',
            'slug' => 'test-salon',
            'business_type' => 'Hair Salon',
            'active' => true,
        ]);

        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);

        // Besøk tenant management siden
        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);

        // Verifiser at alle kolonner vises i header
        $response->assertSee('Name');
        $response->assertSee('Slug');
        $response->assertSee('Business Type');
        $response->assertSee('Status');
        $response->assertSee('Created');
        $response->assertSee('Actions');

        // Verifiser at tenant data vises
        $response->assertSee('Test Salon');
        $response->assertSee('test-salon');
        $response->assertSee('Hair Salon');
        $response->assertSee('Active');
    }

    /**
     * Test at aktive tenants viser grønn badge
     */
    public function test_active_tenants_display_green_badge(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create(['active' => true]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee('Active');
        $response->assertSee('bg-green-100', false);
        $response->assertSee('text-green-800', false);
    }

    /**
     * Test at inaktive tenants viser grå badge
     */
    public function test_inactive_tenants_display_gray_badge(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create(['active' => false]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee('Inactive');
        $response->assertSee('bg-gray-100', false);
        $response->assertSee('text-gray-800', false);
    }

    /**
     * Test at empty state vises når det ikke finnes tenants
     */
    public function test_empty_state_displays_when_no_tenants_exist(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee('No Tenants Found');
        $response->assertSee('There are no tenants in the system yet.');
    }

    /**
     * Test at created date vises i riktig format
     */
    public function test_created_date_displays_in_correct_format(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create([
            'created_at' => '2025-12-01 10:00:00',
        ]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee('Dec 01, 2025');
    }

    /**
     * Test at View link peker til tenant sin bookingside
     */
    public function test_view_link_points_to_tenant_booking_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create(['slug' => 'test-salon']);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee(url('/test-salon'), false);
    }

    /**
     * Test at paginering fungerer med mer enn 20 tenants
     */
    public function test_pagination_works_with_more_than_twenty_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 tenants
        $tenants = Tenant::factory()->count(25)->create();
        foreach ($tenants as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        
        // Verifiser at kun 20 tenants vises på første side
        $this->assertEquals(20, $response->viewData('tenants')->count());
        
        // Verifiser at paginering vises
        $response->assertSee('Next', false);
    }

    /**
     * Test at ikke-admin brukere ikke har tilgang
     */
    public function test_non_admin_users_cannot_access_tenant_management(): void
    {
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);

        $tenantAdmin = User::factory()->create([
            'role' => 'tenant_admin',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->actingAs($tenantAdmin)->get(route('admin.tenants'));

        $response->assertStatus(403);
    }
}

// Test suite som verifiserer at admin kan se liste over alle tenants
// med Name, Slug, Business Type, Status, Created, Actions kolonner.
