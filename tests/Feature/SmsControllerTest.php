<?php

// File: tests/Feature/SmsControllerTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SmsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SmsControllerTest
 * 
 * Tester SmsController funksjonalitet:
 * - Visning av SMS settings side
 * - Lagring av API-nøkkel
 * - Validering av input
 */
class SmsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at SMS settings siden vises korrekt
     */
    public function test_sms_settings_page_displays_correctly(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Logg inn som bruker
        $response = $this->actingAs($user)->get(route('dashboard.sms'));

        // Sjekk at siden vises
        $response->assertStatus(200);
        $response->assertViewIs('sms.index');
        $response->assertViewHas('smsSettings');
    }

    /**
     * Test at API-nøkkel kan lagres
     */
    public function test_api_key_can_be_saved(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Send POST request med API-nøkkel
        $response = $this->actingAs($user)->post(route('dashboard.sms.update'), [
            'api_key' => 'test-api-key-12345',
            'enabled' => true,
        ]);

        // Sjekk redirect og success melding
        $response->assertRedirect(route('dashboard.sms'));
        $response->assertSessionHas('success', 'SMS settings saved successfully');

        // Sjekk at data er lagret i database
        $this->assertDatabaseHas('sms_settings', [
            'tenant_id' => $tenant->id,
            'enabled' => true,
        ]);

        // Sjekk at API-nøkkel er kryptert (ikke lik plaintext)
        $smsSettings = SmsSettings::where('tenant_id', $tenant->id)->first();
        $this->assertNotNull($smsSettings);
        $this->assertEquals('test-api-key-12345', $smsSettings->api_key);
        $this->assertTrue($smsSettings->enabled);
    }

    /**
     * Test at API-nøkkel kan oppdateres
     */
    public function test_api_key_can_be_updated(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Opprett eksisterende SMS settings
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'old-api-key',
            'enabled' => false,
        ]);

        // Oppdater med ny API-nøkkel
        $response = $this->actingAs($user)->post(route('dashboard.sms.update'), [
            'api_key' => 'new-api-key-67890',
            'enabled' => true,
        ]);

        // Sjekk redirect
        $response->assertRedirect(route('dashboard.sms'));

        // Sjekk at data er oppdatert
        $smsSettings = SmsSettings::where('tenant_id', $tenant->id)->first();
        $this->assertEquals('new-api-key-67890', $smsSettings->api_key);
        $this->assertTrue($smsSettings->enabled);
    }

    /**
     * Test validering: API-nøkkel er påkrevd
     */
    public function test_api_key_is_required(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Send POST uten API-nøkkel
        $response = $this->actingAs($user)->post(route('dashboard.sms.update'), [
            'enabled' => true,
        ]);

        // Sjekk at validering feiler
        $response->assertSessionHasErrors('api_key');
    }

    /**
     * Test validering: API-nøkkel må være minimum 10 tegn
     */
    public function test_api_key_must_be_at_least_10_characters(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Send POST med for kort API-nøkkel
        $response = $this->actingAs($user)->post(route('dashboard.sms.update'), [
            'api_key' => 'short',
            'enabled' => true,
        ]);

        // Sjekk at validering feiler
        $response->assertSessionHasErrors('api_key');
    }

    /**
     * Test at enabled checkbox håndteres korrekt når den ikke er checked
     */
    public function test_enabled_defaults_to_false_when_not_checked(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Send POST uten enabled checkbox
        $response = $this->actingAs($user)->post(route('dashboard.sms.update'), [
            'api_key' => 'test-api-key-12345',
        ]);

        // Sjekk at enabled er false
        $smsSettings = SmsSettings::where('tenant_id', $tenant->id)->first();
        $this->assertFalse($smsSettings->enabled);
    }

    /**
     * Test at test SMS krever telefonnummer
     */
    public function test_test_sms_requires_phone_number(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Opprett SMS settings
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-api-key-12345',
            'enabled' => true,
        ]);

        // Send POST uten telefonnummer
        $response = $this->actingAs($user)->postJson(route('dashboard.sms.test'), []);

        // Sjekk at validering feiler
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('phone_number');
    }

    /**
     * Test at test SMS validerer telefonnummer format
     */
    public function test_test_sms_validates_phone_number_format(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Opprett SMS settings
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-api-key-12345',
            'enabled' => true,
        ]);

        // Send POST med ugyldig telefonnummer
        $response = $this->actingAs($user)->postJson(route('dashboard.sms.test'), [
            'phone_number' => 'invalid-phone',
        ]);

        // Sjekk at validering feiler
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('phone_number');
    }

    /**
     * Test at test SMS feiler hvis API-nøkkel ikke er konfigurert
     */
    public function test_test_sms_fails_without_api_key(): void
    {
        // Opprett tenant og bruker
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true,
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Ikke opprett SMS settings (ingen API-nøkkel)

        // Send POST med telefonnummer
        $response = $this->actingAs($user)->postJson(route('dashboard.sms.test'), [
            'phone_number' => '+4712345678',
        ]);

        // Sjekk at det feiler med riktig melding
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Please configure your API key first',
        ]);
    }

    /**
     * Test at subscription middleware blokkerer tilgang til SMS settings
     * når subscription er inaktiv
     */
    public function test_subscription_middleware_blocks_access_when_inactive(): void
    {
        // Opprett tenant og bruker med INAKTIV subscription
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => false, // INAKTIV subscription
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Prøv å aksessere SMS settings siden
        $response = $this->actingAs($user)->get(route('dashboard.sms'));

        // Sjekk at bruker redirectes til subscription.inactive
        $response->assertRedirect(route('subscription.inactive'));
    }

    /**
     * Test at subscription middleware tillater tilgang når subscription er aktiv
     */
    public function test_subscription_middleware_allows_access_when_active(): void
    {
        // Opprett tenant og bruker med AKTIV subscription
        $tenant = Tenant::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'active' => true, // AKTIV subscription
        ]);
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
        ]);

        // Prøv å aksessere SMS settings siden
        $response = $this->actingAs($user)->get(route('dashboard.sms'));

        // Sjekk at siden vises (ikke redirect)
        $response->assertStatus(200);
        $response->assertViewIs('sms.index');
    }
}

// Test for SmsController - verifiserer lagring og oppdatering av SMS settings

