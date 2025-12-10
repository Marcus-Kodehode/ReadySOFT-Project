<?php

// File: tests/Feature/ResourcePolicyTest.php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ResourcePolicyTest
 * 
 * Tester at ResourcePolicy sikrer tenant-isolasjon korrekt.
 * Verifiserer at brukere kun kan se, oppdatere og slette ressurser
 * som tilhører deres egen tenant.
 */
class ResourcePolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at bruker kan se ressurs som tilhører sin tenant.
     */
    public function test_user_can_view_own_tenant_resource(): void
    {
        // Arrange: Opprett tenant, plan, subscription, user og resource
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);
        
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Act: Logg inn og prøv å aksessere edit-siden
        $response = $this->actingAs($user)->get(route('resources.edit', $resource->id));

        // Assert: Skal få tilgang
        $response->assertStatus(200);
        $response->assertViewIs('resources.edit');
        $response->assertViewHas('resource', $resource);
    }

    /**
     * Test at bruker IKKE kan se ressurs som tilhører annen tenant.
     */
    public function test_user_cannot_view_other_tenant_resource(): void
    {
        // Arrange: Opprett to tenants med hver sin bruker og ressurs
        $plan = Plan::factory()->create();
        
        $tenant1 = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant1->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);
        
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
        ]);

        // Act: Logg inn som user1 og prøv å aksessere resource2 (tilhører tenant2)
        $response = $this->actingAs($user1)->get(route('resources.edit', $resource2->id));

        // Assert: Skal få 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test at bruker kan oppdatere ressurs som tilhører sin tenant.
     */
    public function test_user_can_update_own_tenant_resource(): void
    {
        // Arrange
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);
        
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Original Name',
        ]);

        // Act: Oppdater ressursen
        $response = $this->actingAs($user)->put(route('resources.update', $resource->id), [
            'name' => 'Updated Name',
            'type' => 'Cabin',
            'capacity' => 5,
        ]);

        // Assert: Skal lykkes
        $response->assertRedirect(route('resources.index'));
        $response->assertSessionHas('success', 'Resource updated successfully');
        
        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Updated Name',
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * Test at bruker IKKE kan oppdatere ressurs som tilhører annen tenant.
     */
    public function test_user_cannot_update_other_tenant_resource(): void
    {
        // Arrange: Opprett to tenants
        $plan = Plan::factory()->create();
        
        $tenant1 = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant1->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);
        
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
            'name' => 'Original Name',
        ]);

        // Act: Prøv å oppdatere resource2 som user1
        $response = $this->actingAs($user1)->put(route('resources.update', $resource2->id), [
            'name' => 'Hacked Name',
            'type' => 'Cabin',
            'capacity' => 5,
        ]);

        // Assert: Skal få 403 Forbidden
        $response->assertStatus(403);
        
        // Verifiser at ressursen IKKE ble oppdatert
        $this->assertDatabaseHas('resources', [
            'id' => $resource2->id,
            'name' => 'Original Name',
            'tenant_id' => $tenant2->id,
        ]);
    }

    /**
     * Test at bruker kan slette ressurs som tilhører sin tenant.
     */
    public function test_user_can_delete_own_tenant_resource(): void
    {
        // Arrange
        $plan = Plan::factory()->create();
        $tenant = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);
        
        $resource = Resource::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Act: Slett ressursen
        $response = $this->actingAs($user)->delete(route('resources.destroy', $resource->id));

        // Assert: Skal lykkes
        $response->assertRedirect(route('resources.index'));
        $response->assertSessionHas('success', 'Resource deleted successfully');
        
        $this->assertDatabaseMissing('resources', [
            'id' => $resource->id,
        ]);
    }

    /**
     * Test at bruker IKKE kan slette ressurs som tilhører annen tenant.
     */
    public function test_user_cannot_delete_other_tenant_resource(): void
    {
        // Arrange: Opprett to tenants
        $plan = Plan::factory()->create();
        
        $tenant1 = Tenant::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant1->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        $user1 = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin',
        ]);
        
        $tenant2 = Tenant::factory()->create();
        $resource2 = Resource::factory()->create([
            'tenant_id' => $tenant2->id,
        ]);

        // Act: Prøv å slette resource2 som user1
        $response = $this->actingAs($user1)->delete(route('resources.destroy', $resource2->id));

        // Assert: Skal få 403 Forbidden
        $response->assertStatus(403);
        
        // Verifiser at ressursen fortsatt eksisterer
        $this->assertDatabaseHas('resources', [
            'id' => $resource2->id,
            'tenant_id' => $tenant2->id,
        ]);
    }
}

// Test suite for ResourcePolicy - verifiserer tenant-isolasjon for view, update og delete operasjoner.
