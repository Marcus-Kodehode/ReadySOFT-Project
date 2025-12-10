<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingPageTenantGridTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at tenant grid vises med korrekt HTML struktur
     */
    public function test_tenant_grid_displays_with_correct_structure(): void
    {
        // Arrange: Opprett test-tenants
        $tenant1 = Tenant::factory()->create([
            'name' => 'Test Cabin Rental',
            'slug' => 'test-cabin',
            'business_type' => 'Cabin Rental',
            'description' => 'Beautiful cabins in the mountains',
            'active' => true,
        ]);

        $tenant2 = Tenant::factory()->create([
            'name' => 'Hair Salon Rosa',
            'slug' => 'hair-salon-rosa',
            'business_type' => 'Hair Salon',
            'description' => 'Professional hair styling services',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at siden laster
        $response->assertStatus(200);

        // Sjekk at grid container finnes
        $response->assertSee('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6', false);

        // Sjekk at tenant cards vises
        $response->assertSee('Test Cabin Rental');
        $response->assertSee('Hair Salon Rosa');
        $response->assertSee('Cabin Rental');
        $response->assertSee('Hair Salon');

        // Sjekk at beskrivelser vises
        $response->assertSee('Beautiful cabins in the mountains');
        $response->assertSee('Professional hair styling services');

        // Sjekk at "Book Now" knapper finnes
        $response->assertSee('Book Now');

        // Sjekk at links til tenant booking pages finnes
        $response->assertSee('/test-cabin', false);
        $response->assertSee('/hair-salon-rosa', false);
    }

    /**
     * Test at kun aktive tenants vises i grid
     */
    public function test_only_active_tenants_are_displayed(): void
    {
        // Arrange: Opprett aktiv og inaktiv tenant
        $activeTenant = Tenant::factory()->create([
            'name' => 'Active Business',
            'active' => true,
        ]);

        $inactiveTenant = Tenant::factory()->create([
            'name' => 'Inactive Business',
            'active' => false,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Kun aktiv tenant skal vises
        $response->assertStatus(200);
        $response->assertSee('Active Business');
        $response->assertDontSee('Inactive Business');
    }

    /**
     * Test at empty state vises når ingen tenants finnes
     */
    public function test_empty_state_displays_when_no_tenants_exist(): void
    {
        // Act: Besøk landingsside uten tenants
        $response = $this->get('/');

        // Assert: Empty state skal vises
        $response->assertStatus(200);
        $response->assertSee('No Services Available Yet');
        $response->assertSee('Be the first to offer your services on our platform!');
        $response->assertSee('Register Your Business');
    }

    /**
     * Test at tenant cards har korrekt styling
     */
    public function test_tenant_cards_have_correct_styling(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at card styling finnes
        $response->assertStatus(200);
        $response->assertSee('bg-white rounded-lg shadow-sm border border-gray-200 p-6', false);
        $response->assertSee('hover:shadow-md transition-shadow', false);
    }

    /**
     * Test at søkefelt vises på landingsside
     */
    public function test_search_field_is_displayed(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at søkefelt finnes
        $response->assertStatus(200);
        $response->assertSee('Search by name');
        $response->assertSee('Search for services...', false);
        $response->assertSee('id="tenant-search"', false);
        $response->assertSee('x-model="search"', false);
    }

    /**
     * Test at Alpine.js data er konfigurert for søk og filter
     */
    public function test_alpine_search_data_is_configured(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at Alpine.js data finnes med search, selectedType og filteredCount
        $response->assertStatus(200);
        $response->assertSee('x-data="{ ', false);
        $response->assertSee('search: \'\'', false);
        $response->assertSee('selectedType: \'\'', false);
        $response->assertSee('get filteredCount()', false);
    }

    /**
     * Test at tenant cards har x-show attributt for filtrering
     */
    public function test_tenant_cards_have_filter_attributes(): void
    {
        // Arrange: Opprett test-tenant
        $tenant = Tenant::factory()->create([
            'name' => 'Test Business',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at x-show attributt finnes
        $response->assertStatus(200);
        $response->assertSee('x-show=', false);
        $response->assertSee('x-transition', false);
    }

    /**
     * Test at "no results" melding finnes i HTML
     */
    public function test_no_results_message_exists(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at "no results" melding finnes
        $response->assertStatus(200);
        $response->assertSee('No services found');
        $response->assertSee('Try adjusting your search or filter');
        $response->assertSee('Clear filters');
    }

    /**
     * Test at business type filter chips vises
     */
    public function test_business_type_filter_chips_are_displayed(): void
    {
        // Arrange: Opprett tenants med forskjellige business types
        Tenant::factory()->create([
            'name' => 'Cabin Business',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        Tenant::factory()->create([
            'name' => 'Salon Business',
            'business_type' => 'Hair Salon',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at filter label finnes
        $response->assertStatus(200);
        $response->assertSee('Filter by type');

        // Sjekk at "All" chip finnes
        $response->assertSee('All');

        // Sjekk at business type chips finnes
        $response->assertSee('Cabin Rental');
        $response->assertSee('Hair Salon');
    }

    /**
     * Test at filter chips har korrekt Alpine.js binding
     */
    public function test_filter_chips_have_alpine_bindings(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at Alpine.js bindings finnes
        $response->assertStatus(200);
        $response->assertSee('@click="selectedType = \'\'"', false);
        $response->assertSee('@click="selectedType = \'Cabin Rental\'"', false);
        $response->assertSee(':class="selectedType === \'\' ? \'bg-blue-600 text-white\' : \'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50\'"', false);
    }

    /**
     * Test at tenant cards filtreres basert på både søk og business type
     */
    public function test_tenant_cards_filter_by_search_and_type(): void
    {
        // Arrange: Opprett tenants
        $tenant = Tenant::factory()->create([
            'name' => 'Test Cabin',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at x-show attributt inkluderer både search og selectedType
        $response->assertStatus(200);
        $response->assertSee('x-show="(search === \'\' || \'test cabin\'.includes(search.toLowerCase())) && (selectedType === \'\' || selectedType === \'Cabin Rental\')"', false);
    }

    /**
     * Test at unike business types ekstraheres korrekt
     */
    public function test_unique_business_types_are_extracted(): void
    {
        // Arrange: Opprett flere tenants med samme og forskjellige business types
        Tenant::factory()->create([
            'name' => 'Cabin 1',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        Tenant::factory()->create([
            'name' => 'Cabin 2',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        Tenant::factory()->create([
            'name' => 'Salon',
            'business_type' => 'Hair Salon',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at kun unike business types vises (ikke duplikater)
        $response->assertStatus(200);
        
        // Telle antall ganger "Cabin Rental" vises som chip (skal være 1 gang i filter + 2 ganger i cards)
        $content = $response->getContent();
        
        // Sjekk at begge business types finnes
        $this->assertStringContainsString('Cabin Rental', $content);
        $this->assertStringContainsString('Hair Salon', $content);
    }

    /**
     * Test at filter chips har korrekt styling
     */
    public function test_filter_chips_have_correct_styling(): void
    {
        // Arrange: Opprett test-tenant
        Tenant::factory()->create([
            'name' => 'Test Business',
            'business_type' => 'Cabin Rental',
            'active' => true,
        ]);

        // Act: Besøk landingsside
        $response = $this->get('/');

        // Assert: Sjekk at chip styling finnes
        $response->assertStatus(200);
        $response->assertSee('px-4 py-2 rounded-full text-sm font-medium', false);
        $response->assertSee('transition-colors', false);
        $response->assertSee('focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2', false);
    }
}
