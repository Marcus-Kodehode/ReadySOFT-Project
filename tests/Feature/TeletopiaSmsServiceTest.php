<?php

// File: tests/Feature/TeletopiaSmsServiceTest.php

namespace Tests\Feature;

use App\Models\SmsSettings;
use App\Models\Tenant;
use App\Services\TeletopiaSmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TeletopiaSmsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_key_is_retrieved_and_decrypted_automatically(): void
    {
        $tenant = Tenant::factory()->create();
        
        // Create SMS settings with encrypted API key
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'secret-api-key-12345',
            'enabled' => true,
        ]);

        // Mock HTTP request to verify API key is used correctly
        Http::fake([
            'https://api.teletopia.no/sms/send' => Http::response(['status' => 'sent'], 200)
        ]);

        $service = new TeletopiaSmsService();
        $result = $service->sendSms($tenant->id, '+4712345678', 'Test message');

        // Verify the request was made with the correct API key
        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer secret-api-key-12345');
        });

        $this->assertTrue($result['success']);
    }

    public function test_returns_error_when_settings_not_found(): void
    {
        $tenant = Tenant::factory()->create();
        
        // Don't create SMS settings
        
        $service = new TeletopiaSmsService();
        $result = $service->sendSms($tenant->id, '+4712345678', 'Test message');

        $this->assertFalse($result['success']);
        $this->assertEquals('SMS settings not found for this tenant', $result['message']);
    }

    public function test_returns_error_when_sms_not_enabled(): void
    {
        $tenant = Tenant::factory()->create();
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-key',
            'enabled' => false, // Disabled
        ]);

        $service = new TeletopiaSmsService();
        $result = $service->sendSms($tenant->id, '+4712345678', 'Test message');

        $this->assertFalse($result['success']);
        $this->assertEquals('SMS functionality is not enabled', $result['message']);
    }

    public function test_returns_error_when_api_key_is_empty(): void
    {
        $tenant = Tenant::factory()->create();
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => '', // Empty API key
            'enabled' => true,
        ]);

        $service = new TeletopiaSmsService();
        $result = $service->sendSms($tenant->id, '+4712345678', 'Test message');

        $this->assertFalse($result['success']);
        $this->assertEquals('API key is not configured', $result['message']);
    }
}

// Test suite for TeletopiaSmsService - verifiserer at API-nøkkel hentes og dekrypteres korrekt
