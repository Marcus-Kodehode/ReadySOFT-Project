<?php

namespace Tests\Feature;

use App\Models\SmsSettings;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmsSettingsModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_key_is_encrypted_in_database(): void
    {
        $tenant = Tenant::factory()->create();
        
        $settings = SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'my-secret-api-key-123',
            'enabled' => true,
        ]);

        // Verify the model returns the decrypted value
        $this->assertEquals('my-secret-api-key-123', $settings->api_key);
        
        // Verify the database stores encrypted value (not plain text)
        $rawValue = \DB::table('sms_settings')
            ->where('id', $settings->id)
            ->value('api_key');
        
        $this->assertNotEquals('my-secret-api-key-123', $rawValue);
        $this->assertNotEmpty($rawValue);
    }

    public function test_enabled_is_cast_to_boolean(): void
    {
        $tenant = Tenant::factory()->create();
        
        $settings = SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-key',
            'enabled' => 1, // Store as integer
        ]);

        // Verify it's cast to boolean
        $this->assertIsBool($settings->enabled);
        $this->assertTrue($settings->enabled);
        
        $settings->enabled = 0;
        $settings->save();
        
        $settings->refresh();
        $this->assertIsBool($settings->enabled);
        $this->assertFalse($settings->enabled);
    }

    public function test_tenant_relationship_works(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'Test Tenant']);
        
        $settings = SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-key',
            'enabled' => false,
        ]);

        $this->assertEquals('Test Tenant', $settings->tenant->name);
        $this->assertInstanceOf(Tenant::class, $settings->tenant);
    }

    public function test_sms_settings_relationship_on_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        
        $settings = SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test-key',
            'enabled' => true,
        ]);

        $this->assertInstanceOf(SmsSettings::class, $tenant->smsSettings);
        $this->assertEquals('test-key', $tenant->smsSettings->api_key);
    }
}
