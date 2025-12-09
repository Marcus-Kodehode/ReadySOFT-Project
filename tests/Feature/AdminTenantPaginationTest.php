<?php

// File: tests/Feature/AdminTenantPaginationTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin tenant pagination functionality
 * 
 * Verifiserer at paginering fungerer korrekt og bevarer query parameters
 */
class AdminTenantPaginationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at pagination vises når det er mer enn 20 tenants
     */
    public function test_pagination_displays_when_more_than_twenty_tenants(): void
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
        
        // Verifiser at pagination vises
        $response->assertSee('Next');
        
        // Verifiser at kun 20 tenants vises
        $this->assertEquals(20, $response->viewData('tenants')->count());
    }

    /**
     * Test at pagination ikke vises når det er færre enn 20 tenants
     */
    public function test_pagination_does_not_display_when_less_than_twenty_tenants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett kun 10 tenants
        $tenants = Tenant::factory()->count(10)->create();
        foreach ($tenants as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants'));

        $response->assertStatus(200);
        
        // Verifiser at pagination IKKE vises
        $response->assertDontSee('Next');
        
        // Verifiser at alle 10 tenants vises
        $this->assertEquals(10, $response->viewData('tenants')->count());
    }

    /**
     * Test at side 2 viser de resterende tenants
     */
    public function test_page_two_displays_remaining_tenants(): void
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

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['page' => 2]));

        $response->assertStatus(200);
        
        // Verifiser at side 2 viser de resterende 5 tenants
        $this->assertEquals(5, $response->viewData('tenants')->count());
        
        // Verifiser at "Previous" link vises
        $response->assertSee('Previous');
    }

    /**
     * Test at pagination bevarer søkeparameter
     */
    public function test_pagination_preserves_search_parameter(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 tenants med "Salon" i navnet
        for ($i = 1; $i <= 25; $i++) {
            $tenant = Tenant::factory()->create([
                'name' => "Salon Number {$i}",
            ]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['search' => 'Salon']));

        $response->assertStatus(200);
        
        // Verifiser at pagination link inneholder search parameter
        $response->assertSee('search=Salon', false);
    }

    /**
     * Test at pagination bevarer filter parameter
     */
    public function test_pagination_preserves_filter_parameter(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 aktive tenants
        $tenants = Tenant::factory()->count(25)->create(['active' => true]);
        foreach ($tenants as $tenant) {
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['filter' => 'active']));

        $response->assertStatus(200);
        
        // Verifiser at pagination link inneholder filter parameter
        $response->assertSee('filter=active', false);
    }

    /**
     * Test at pagination bevarer sorteringsparametere
     */
    public function test_pagination_preserves_sort_parameters(): void
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

        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        // Verifiser at pagination link inneholder sort parametere
        $response->assertSee('sort=name', false);
        $response->assertSee('direction=asc', false);
    }

    /**
     * Test at pagination bevarer alle parametere samtidig
     */
    public function test_pagination_preserves_all_parameters_together(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 aktive tenants med "Salon" i navnet
        for ($i = 1; $i <= 25; $i++) {
            $tenant = Tenant::factory()->create([
                'name' => "Salon Number {$i}",
                'active' => true,
            ]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'search' => 'Salon',
            'filter' => 'active',
            'sort' => 'name',
            'direction' => 'asc'
        ]));

        $response->assertStatus(200);
        
        // Verifiser at pagination link inneholder alle parametere
        $response->assertSee('search=Salon', false);
        $response->assertSee('filter=active', false);
        $response->assertSee('sort=name', false);
        $response->assertSee('direction=asc', false);
    }

    /**
     * Test at navigering til side 2 med alle parametere fungerer
     */
    public function test_navigating_to_page_two_with_all_parameters_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 aktive tenants med "Salon" i navnet
        for ($i = 1; $i <= 25; $i++) {
            $tenant = Tenant::factory()->create([
                'name' => "Salon Number {$i}",
                'active' => true,
            ]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        // Gå til side 2 med alle parametere
        $response = $this->actingAs($admin)->get(route('admin.tenants', [
            'search' => 'Salon',
            'filter' => 'active',
            'sort' => 'name',
            'direction' => 'asc',
            'page' => 2
        ]));

        $response->assertStatus(200);
        
        // Verifiser at vi er på side 2 (5 tenants)
        $this->assertEquals(5, $response->viewData('tenants')->count());
        
        // Verifiser at alle parametere fortsatt er aktive
        $response->assertSee('search=Salon', false);
        $response->assertSee('filter=active', false);
        $response->assertSee('sort=name', false);
        $response->assertSee('direction=asc', false);
    }

    /**
     * Test at pagination fungerer med filtrering som gir færre enn 20 resultater
     */
    public function test_pagination_with_filter_resulting_in_less_than_twenty(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();

        // Opprett 25 tenants, men kun 10 aktive
        for ($i = 1; $i <= 25; $i++) {
            $tenant = Tenant::factory()->create([
                'active' => $i <= 10, // Kun de første 10 er aktive
            ]);
            Subscription::factory()->create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.tenants', ['filter' => 'active']));

        $response->assertStatus(200);
        
        // Verifiser at kun 10 tenants vises
        $this->assertEquals(10, $response->viewData('tenants')->count());
        
        // Verifiser at pagination IKKE vises (færre enn 20)
        $response->assertDontSee('Next');
    }
}

// Test suite som verifiserer at paginering fungerer korrekt
// og bevarer alle query parameters (search, filter, sort, direction)

