<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class LandingPageCacheTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at tenant list caches i 5 minutter
     */
    public function test_tenant_list_is_cached_for_5_minutes(): void
    {
        // Arrange: Opprett noen test-tenants
        Tenant::factory()->count(3)->create(['active' => true]);
        
        // Sørg for at cache er tom
        Cache::forget('landing.tenants');
        
        // Act: Besøk landingsside første gang
        $response = $this->get('/');
        
        // Assert: Cache skal nå inneholde tenant list
        $this->assertTrue(Cache::has('landing.tenants'));
        
        // Hent cached data
        $cachedTenants = Cache::get('landing.tenants');
        $this->assertCount(3, $cachedTenants);
        
        $response->assertStatus(200);
    }

    /**
     * Test at cache tømmes når ny tenant opprettes
     */
    public function test_cache_is_cleared_when_new_tenant_is_created(): void
    {
        // Arrange: Seed database med Basic plan (required for registration)
        \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);
        
        // Opprett initial tenant og cache
        Tenant::factory()->create(['active' => true]);
        $this->get('/'); // Trigger caching
        
        $this->assertTrue(Cache::has('landing.tenants'));
        
        // Act: Registrer ny tenant
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'business_name' => 'Test Business',
            'business_type' => 'Cabin Rental',
            'slug' => 'test-business',
        ]);
        
        // Assert: Cache skal være tømt
        $this->assertFalse(Cache::has('landing.tenants'));
        
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test at cache tømmes når tenant status toggles
     */
    public function test_cache_is_cleared_when_tenant_status_is_toggled(): void
    {
        // Arrange: Opprett admin bruker og tenant
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $tenant = Tenant::factory()->create(['active' => true]);
        
        // Cache tenant list
        $this->get('/');
        $this->assertTrue(Cache::has('landing.tenants'));
        
        // Act: Toggle tenant status som admin
        $response = $this->actingAs($admin)
            ->post("/admin/tenants/{$tenant->id}/toggle");
        
        // Assert: Cache skal være tømt
        $this->assertFalse(Cache::has('landing.tenants'));
        
        $response->assertRedirect();
    }
}
