<?php

// File: tests/Feature/AdminTenantToggleTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite for admin tenant status toggle functionality
 * 
 * Verifiserer at admin kan toggle tenant status via inline switch
 */
class AdminTenantToggleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at admin kan aktivere en inaktiv tenant
     */
    public function test_admin_can_activate_inactive_tenant(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Opprett plan og inaktiv tenant
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create(['active' => false]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Toggle status
        $response = $this->actingAs($admin)
            ->post(route('admin.tenants.toggle', $tenant->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifiser at tenant er aktivert
        $this->assertTrue($tenant->fresh()->active);
    }

    /**
     * Test at admin kan deaktivere en aktiv tenant
     */
    public function test_admin_can_deactivate_active_tenant(): void
    {
        // Opprett admin bruker
        $admin = User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
        ]);

        // Opprett plan og aktiv tenant
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create(['active' => true]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Toggle status
        $response = $this->actingAs($admin)
            ->post(route('admin.tenants.toggle', $tenant->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifiser at tenant er deaktivert
        $this->assertFalse($tenant->fresh()->active);
    }

    /**
     * Test at toggle returnerer riktig success melding for aktivering
     */
    public function test_toggle_returns_correct_success_message_for_activation(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create([
            'name' => 'Test Salon',
            'active' => false
        ]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.tenants.toggle', $tenant->id));

        $response->assertSessionHas('success', "Tenant 'Test Salon' has been activated successfully.");
    }

    /**
     * Test at toggle returnerer riktig success melding for deaktivering
     */
    public function test_toggle_returns_correct_success_message_for_deactivation(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create([
            'name' => 'Test Salon',
            'active' => true
        ]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.tenants.toggle', $tenant->id));

        $response->assertSessionHas('success', "Tenant 'Test Salon' has been deactivated successfully.");
    }

    /**
     * Test at toggle feiler med 404 for ikke-eksisterende tenant
     */
    public function test_toggle_fails_with_404_for_nonexistent_tenant(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);

        $response = $this->actingAs($admin)
            ->post(route('admin.tenants.toggle', 99999));

        $response->assertStatus(404);
    }

    /**
     * Test at toggle kan kalles flere ganger (idempotent)
     */
    public function test_toggle_can_be_called_multiple_times(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'tenant_id' => null]);
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create(['active' => true]);
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);

        // Toggle 1: true -> false
        $this->actingAs($admin)->post(route('admin.tenants.toggle', $tenant->id));
        $this->assertFalse($tenant->fresh()->active);

        // Toggle 2: false -> true
        $this->actingAs($admin)->post(route('admin.tenants.toggle', $tenant->id));
        $this->assertTrue($tenant->fresh()->active);

        // Toggle 3: true -> false
        $this->actingAs($admin)->post(route('admin.tenants.toggle', $tenant->id));
        $this->assertFalse($tenant->fresh()->active);
    }
}

// Test suite som verifiserer at admin kan toggle tenant status via inline switch

