<?php

// File: tests/Feature/AdminMiddlewareTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin middleware protection
 * 
 * Verifiserer at admin-ruter er beskyttet med admin middleware
 * og at kun brukere med admin-rolle har tilgang.
 */
class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at uautentiserte brukere redirectes til login
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test at tenant_admin brukere får 403 Forbidden
     */
    public function test_tenant_admin_users_cannot_access_admin_routes(): void
    {
        // Opprett tenant og plan
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);

        // Opprett tenant_admin bruker
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        $response = $this->actingAs($user)->get('/admin');
        
        $response->assertStatus(403);
    }

    /**
     * Test at admin brukere har tilgang til admin dashboard
     */
    public function test_admin_users_can_access_admin_dashboard(): void
    {
        // Opprett admin bruker (uten tenant)
        $user = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($user)->get('/admin');
        
        $response->assertStatus(200);
    }

    /**
     * Test at admin brukere har tilgang til tenants liste
     */
    public function test_admin_users_can_access_tenants_list(): void
    {
        // Opprett admin bruker
        $user = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($user)->get('/admin/tenants');
        
        $response->assertStatus(200);
    }

    /**
     * Test at tenant_admin ikke kan toggle tenant status
     */
    public function test_tenant_admin_cannot_toggle_tenant_status(): void
    {
        // Opprett tenant og plan
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);

        // Opprett tenant_admin bruker
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        $response = $this->actingAs($user)->post("/admin/tenants/{$tenant->id}/toggle");
        
        $response->assertStatus(403);
    }
}

// Test suite som verifiserer at admin middleware beskytter alle admin-ruter
// og at kun brukere med admin-rolle har tilgang til admin-funksjonalitet.
