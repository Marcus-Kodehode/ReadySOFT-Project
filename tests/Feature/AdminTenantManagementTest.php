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

    /**
     * Test at søk på name fungerer
     */
    public function test_search_by_name_filters_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett flere tenants
        $tenant1 = Tenant::factory()->create([
            'name' => 'Beautiful Salon',
            'slug' => 'beautiful-salon',
        ]);
        $tenant2 = Tenant::factory()->create([
            'name' => 'Cozy Cabin',
            'slug' => 'cozy-cabin',
        ]);
        $tenant3 = Tenant::factory()->create([
            'name' => 'Spa Retreat',
            'slug' => 'spa-retreat',
        ]);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Søk på "Salon"
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'Salon']));

        $response->assertStatus(200);
        $response->assertSee('Beautiful Salon');
        $response->assertDontSee('Cozy Cabin');
        $response->assertDontSee('Spa Retreat');
    }

    /**
     * Test at søk på slug fungerer
     */
    public function test_search_by_slug_filters_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett flere tenants
        $tenant1 = Tenant::factory()->create([
            'name' => 'Beautiful Salon',
            'slug' => 'beautiful-salon',
        ]);
        $tenant2 = Tenant::factory()->create([
            'name' => 'Cozy Cabin',
            'slug' => 'cozy-cabin',
        ]);
        $tenant3 = Tenant::factory()->create([
            'name' => 'Spa Retreat',
            'slug' => 'spa-retreat',
        ]);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Søk på "cozy-cabin"
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'cozy-cabin']));

        $response->assertStatus(200);
        $response->assertSee('Cozy Cabin');
        $response->assertDontSee('Beautiful Salon');
        $response->assertDontSee('Spa Retreat');
    }

    /**
     * Test at søk fungerer med partial match
     */
    public function test_search_works_with_partial_match(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett flere tenants
        $tenant1 = Tenant::factory()->create([
            'name' => 'Beautiful Salon',
            'slug' => 'beautiful-salon',
        ]);
        $tenant2 = Tenant::factory()->create([
            'name' => 'Cozy Cabin',
            'slug' => 'cozy-cabin',
        ]);

        foreach ([$tenant1, $tenant2] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Søk på "beau" (partial match for "Beautiful")
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'beau']));

        $response->assertStatus(200);
        $response->assertSee('Beautiful Salon');
        $response->assertDontSee('Cozy Cabin');
    }

    /**
     * Test at søk er case-insensitive
     */
    public function test_search_is_case_insensitive(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create([
            'name' => 'Beautiful Salon',
            'slug' => 'beautiful-salon',
        ]);

        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Søk med lowercase
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'beautiful']));

        $response->assertStatus(200);
        $response->assertSee('Beautiful Salon');

        // Søk med uppercase
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'BEAUTIFUL']));

        $response->assertStatus(200);
        $response->assertSee('Beautiful Salon');
    }

    /**
     * Test at tom søk returnerer alle tenants
     */
    public function test_empty_search_returns_all_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant1 = Tenant::factory()->create(['name' => 'Tenant 1']);
        $tenant2 = Tenant::factory()->create(['name' => 'Tenant 2']);

        foreach ([$tenant1, $tenant2] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => '']));

        $response->assertStatus(200);
        $response->assertSee('Tenant 1');
        $response->assertSee('Tenant 2');
    }

    /**
     * Test at søk som ikke matcher noe viser empty state
     */
    public function test_search_with_no_matches_shows_empty_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create([
            'name' => 'Beautiful Salon',
            'slug' => 'beautiful-salon',
        ]);

        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'nonexistent']));

        $response->assertStatus(200);
        $response->assertSee('No Tenants Found');
    }

    /**
     * Test at filter tabs vises på siden
     */
    public function test_filter_tabs_are_displayed(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        $response->assertSee('All');
        $response->assertSee('Active');
        $response->assertSee('Inactive');
    }

    /**
     * Test at filter på active viser kun aktive tenants
     */
    public function test_filter_active_shows_only_active_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett aktive og inaktive tenants
        $activeTenant = Tenant::factory()->create([
            'name' => 'Active Salon',
            'active' => true,
        ]);
        $inactiveTenant = Tenant::factory()->create([
            'name' => 'Inactive Salon',
            'active' => false,
        ]);

        foreach ([$activeTenant, $inactiveTenant] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Filtrer på active
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['filter' => 'active']));

        $response->assertStatus(200);
        $response->assertSee('Active Salon');
        $response->assertDontSee('Inactive Salon');
    }

    /**
     * Test at filter på inactive viser kun inaktive tenants
     */
    public function test_filter_inactive_shows_only_inactive_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett aktive og inaktive tenants
        $activeTenant = Tenant::factory()->create([
            'name' => 'Active Salon',
            'active' => true,
        ]);
        $inactiveTenant = Tenant::factory()->create([
            'name' => 'Inactive Salon',
            'active' => false,
        ]);

        foreach ([$activeTenant, $inactiveTenant] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Filtrer på inactive
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['filter' => 'inactive']));

        $response->assertStatus(200);
        $response->assertSee('Inactive Salon');
        $response->assertDontSee('Active Salon');
    }

    /**
     * Test at filter all viser alle tenants
     */
    public function test_filter_all_shows_all_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett aktive og inaktive tenants
        $activeTenant = Tenant::factory()->create([
            'name' => 'Active Salon',
            'active' => true,
        ]);
        $inactiveTenant = Tenant::factory()->create([
            'name' => 'Inactive Salon',
            'active' => false,
        ]);

        foreach ([$activeTenant, $inactiveTenant] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Filtrer på all (eller ingen filter)
        $response = $this->actingAs($admin)->get(route('admin.tenants', ['filter' => 'all']));

        $response->assertStatus(200);
        $response->assertSee('Active Salon');
        $response->assertSee('Inactive Salon');
    }

    /**
     * Test at filter og søk fungerer sammen
     */
    public function test_filter_and_search_work_together(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett flere tenants
        $activeSalon = Tenant::factory()->create([
            'name' => 'Active Salon',
            'active' => true,
        ]);
        $inactiveSalon = Tenant::factory()->create([
            'name' => 'Inactive Salon',
            'active' => false,
        ]);
        $activeCabin = Tenant::factory()->create([
            'name' => 'Active Cabin',
            'active' => true,
        ]);

        foreach ([$activeSalon, $inactiveSalon, $activeCabin] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Filtrer på active og søk på "Salon"
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'filter' => 'active',
            'search' => 'Salon'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Active Salon');
        $response->assertDontSee('Inactive Salon');
        $response->assertDontSee('Active Cabin');
    }

    /**
     * Test at filter tabs viser riktig count
     */
    public function test_filter_tabs_show_correct_counts(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 3 aktive og 2 inaktive tenants
        $activeCount = 3;
        $inactiveCount = 2;

        for ($i = 0; $i < $activeCount; $i++) {
            $tenant = Tenant::factory()->create(['active' => true]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        for ($i = 0; $i < $inactiveCount; $i++) {
            $tenant = Tenant::factory()->create(['active' => false]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        
        // Verifiser at counts vises
        $response->assertSee((string)($activeCount + $inactiveCount)); // Total count
        $response->assertSee((string)$activeCount); // Active count
        $response->assertSee((string)$inactiveCount); // Inactive count
    }

    /**
     * Test at sortering på name fungerer (ascending)
     */
    public function test_sorting_by_name_ascending_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige navn
        $tenantC = Tenant::factory()->create(['name' => 'Charlie Salon']);
        $tenantA = Tenant::factory()->create(['name' => 'Alpha Cabin']);
        $tenantB = Tenant::factory()->create(['name' => 'Bravo Spa']);

        foreach ([$tenantC, $tenantA, $tenantB] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på name ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        // Verifiser at tenants er sortert alfabetisk
        $tenants = $response->viewData('tenants');
        $this->assertEquals('Alpha Cabin', $tenants[0]->name);
        $this->assertEquals('Bravo Spa', $tenants[1]->name);
        $this->assertEquals('Charlie Salon', $tenants[2]->name);
    }

    /**
     * Test at sortering på name fungerer (descending)
     */
    public function test_sorting_by_name_descending_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige navn
        $tenantC = Tenant::factory()->create(['name' => 'Charlie Salon']);
        $tenantA = Tenant::factory()->create(['name' => 'Alpha Cabin']);
        $tenantB = Tenant::factory()->create(['name' => 'Bravo Spa']);

        foreach ([$tenantC, $tenantA, $tenantB] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på name descending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'name',
            'direction' => 'desc'
        ]));

        $response->assertStatus(200);
        
        // Verifiser at tenants er sortert omvendt alfabetisk
        $tenants = $response->viewData('tenants');
        $this->assertEquals('Charlie Salon', $tenants[0]->name);
        $this->assertEquals('Bravo Spa', $tenants[1]->name);
        $this->assertEquals('Alpha Cabin', $tenants[2]->name);
    }

    /**
     * Test at sortering på slug fungerer
     */
    public function test_sorting_by_slug_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige slugs
        $tenant1 = Tenant::factory()->create(['slug' => 'zebra-salon']);
        $tenant2 = Tenant::factory()->create(['slug' => 'alpha-cabin']);
        $tenant3 = Tenant::factory()->create(['slug' => 'middle-spa']);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på slug ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'slug',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertEquals('alpha-cabin', $tenants[0]->slug);
        $this->assertEquals('middle-spa', $tenants[1]->slug);
        $this->assertEquals('zebra-salon', $tenants[2]->slug);
    }

    /**
     * Test at sortering på business_type fungerer
     */
    public function test_sorting_by_business_type_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige business types
        $tenant1 = Tenant::factory()->create(['business_type' => 'Spa & Wellness']);
        $tenant2 = Tenant::factory()->create(['business_type' => 'Cabin Rental']);
        $tenant3 = Tenant::factory()->create(['business_type' => 'Hair Salon']);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på business_type ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'business_type',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertEquals('Cabin Rental', $tenants[0]->business_type);
        $this->assertEquals('Hair Salon', $tenants[1]->business_type);
        $this->assertEquals('Spa & Wellness', $tenants[2]->business_type);
    }

    /**
     * Test at sortering på active status fungerer
     */
    public function test_sorting_by_active_status_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellig active status
        $tenant1 = Tenant::factory()->create(['name' => 'Active 1', 'active' => true]);
        $tenant2 = Tenant::factory()->create(['name' => 'Inactive 1', 'active' => false]);
        $tenant3 = Tenant::factory()->create(['name' => 'Active 2', 'active' => true]);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på active ascending (false først, deretter true)
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'active',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertFalse($tenants[0]->active);
        $this->assertTrue($tenants[1]->active);
        $this->assertTrue($tenants[2]->active);
    }

    /**
     * Test at sortering på created_at fungerer
     */
    public function test_sorting_by_created_at_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige created_at datoer
        $tenant1 = Tenant::factory()->create([
            'name' => 'Oldest',
            'created_at' => '2025-01-01 10:00:00'
        ]);
        $tenant2 = Tenant::factory()->create([
            'name' => 'Newest',
            'created_at' => '2025-12-01 10:00:00'
        ]);
        $tenant3 = Tenant::factory()->create([
            'name' => 'Middle',
            'created_at' => '2025-06-01 10:00:00'
        ]);

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Sorter på created_at ascending (eldste først)
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'created_at',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertEquals('Oldest', $tenants[0]->name);
        $this->assertEquals('Middle', $tenants[1]->name);
        $this->assertEquals('Newest', $tenants[2]->name);
    }

    /**
     * Test at default sortering er created_at descending
     */
    public function test_default_sorting_is_created_at_descending(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med forskjellige created_at datoer
        $tenant1 = Tenant::factory()->create([
            'name' => 'Oldest',
            'created_at' => '2025-01-01 10:00:00'
        ]);
        $tenant2 = Tenant::factory()->create([
            'name' => 'Newest',
            'created_at' => '2025-12-01 10:00:00'
        ]);

        foreach ([$tenant1, $tenant2] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Ingen sorteringsparametere (default)
        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertEquals('Newest', $tenants[0]->name);
        $this->assertEquals('Oldest', $tenants[1]->name);
    }

    /**
     * Test at ugyldig sorteringskolonne faller tilbake til default
     */
    public function test_invalid_sort_column_falls_back_to_default(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Prøv å sortere på ugyldig kolonne
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'invalid_column',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        // Skal ikke krasje, men bruke default sortering
    }

    /**
     * Test at ugyldig sorteringsretning faller tilbake til desc
     */
    public function test_invalid_sort_direction_falls_back_to_desc(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Prøv å bruke ugyldig retning
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'name',
            'direction' => 'invalid'
        ]));

        $response->assertStatus(200);
        // Skal ikke krasje, men bruke desc som default
    }

    /**
     * Test at sortering fungerer sammen med søk
     */
    public function test_sorting_works_with_search(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett tenants med "Salon" i navnet
        $tenant1 = Tenant::factory()->create(['name' => 'Zebra Salon']);
        $tenant2 = Tenant::factory()->create(['name' => 'Alpha Salon']);
        $tenant3 = Tenant::factory()->create(['name' => 'Beta Cabin']); // Skal ikke vises

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Søk på "Salon" og sorter på name ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'search' => 'Salon',
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertCount(2, $tenants);
        $this->assertEquals('Alpha Salon', $tenants[0]->name);
        $this->assertEquals('Zebra Salon', $tenants[1]->name);
    }

    /**
     * Test at sortering fungerer sammen med filter
     */
    public function test_sorting_works_with_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett aktive tenants
        $tenant1 = Tenant::factory()->create(['name' => 'Zebra Salon', 'active' => true]);
        $tenant2 = Tenant::factory()->create(['name' => 'Alpha Salon', 'active' => true]);
        $tenant3 = Tenant::factory()->create(['name' => 'Beta Cabin', 'active' => false]); // Skal ikke vises

        foreach ([$tenant1, $tenant2, $tenant3] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Filtrer på active og sorter på name ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'filter' => 'active',
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertCount(2, $tenants);
        $this->assertEquals('Alpha Salon', $tenants[0]->name);
        $this->assertEquals('Zebra Salon', $tenants[1]->name);
    }

    /**
     * Test at sortering fungerer sammen med både søk og filter
     */
    public function test_sorting_works_with_search_and_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett forskjellige tenants
        $tenant1 = Tenant::factory()->create(['name' => 'Zebra Salon', 'active' => true]);
        $tenant2 = Tenant::factory()->create(['name' => 'Alpha Salon', 'active' => true]);
        $tenant3 = Tenant::factory()->create(['name' => 'Beta Salon', 'active' => false]); // Filtreres bort
        $tenant4 = Tenant::factory()->create(['name' => 'Gamma Cabin', 'active' => true]); // Søkes bort

        foreach ([$tenant1, $tenant2, $tenant3, $tenant4] as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Søk på "Salon", filtrer på active, og sorter på name ascending
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'search' => 'Salon',
            'filter' => 'active',
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        $tenants = $response->viewData('tenants');
        $this->assertCount(2, $tenants);
        $this->assertEquals('Alpha Salon', $tenants[0]->name);
        $this->assertEquals('Zebra Salon', $tenants[1]->name);
    }
}

// Test suite som verifiserer at admin kan se liste over alle tenants
// med Name, Slug, Business Type, Status, Created, Actions kolonner.
