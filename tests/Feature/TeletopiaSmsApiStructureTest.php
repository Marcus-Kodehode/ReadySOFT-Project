<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\TeletopiaSmsService;
use App\Models\SmsSettings;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeletopiaSmsApiStructureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at TeletopiaSMS API request er korrekt strukturert
     * VIKTIG: Denne testen sender IKKE faktisk SMS, den bare verifiserer strukturen
     */
    public function test_teletopia_api_request_structure_is_correct(): void
    {
        // Sett opp test data
        $tenant = Tenant::factory()->create(['active' => true]);
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test_key',
            'enabled' => true
        ]);

        // Mock HTTP response fra TeletopiaSMS
        Http::fake([
            'api1.teletopiasms.no/*' => Http::response([
                'responses' => [
                    [
                        'accepted' => 1,
                        'messageId' => 'test_message_id_12345',
                        'recipient' => '4790039911',
                        'statusCode' => 1000,
                        'statusDescription' => 'accepted'
                    ]
                ]
            ], 200)
        ]);

        // Send test SMS
        $smsService = new TeletopiaSmsService();
        $result = $smsService->sendSms(
            $tenant->id,
            '90039911',
            'Test message'
        );

        // Verifiser at request ble sendt til riktig URL
        Http::assertSent(function ($request) {
            // Sjekk URL
            if ($request->url() !== 'https://api1.teletopiasms.no/gateway/v3/json') {
                dump('❌ Feil URL: ' . $request->url());
                return false;
            }
            dump('✅ Korrekt URL: ' . $request->url());

            // Sjekk at det er POST request
            if ($request->method() !== 'POST') {
                dump('❌ Feil HTTP method: ' . $request->method());
                return false;
            }
            dump('✅ Korrekt HTTP method: POST');

            // Sjekk Content-Type header
            $contentType = $request->header('Content-Type')[0] ?? '';
            if (!str_contains($contentType, 'application/json')) {
                dump('❌ Feil Content-Type: ' . $contentType);
                return false;
            }
            dump('✅ Korrekt Content-Type: ' . $contentType);

            // Hent request body
            $body = $request->data();
            
            // Sjekk auth struktur
            if (!isset($body['auth']['username']) || !isset($body['auth']['password'])) {
                dump('❌ Mangler auth struktur');
                dump($body);
                return false;
            }
            dump('✅ Auth struktur OK');
            dump('   Username: ' . $body['auth']['username']);

            // Sjekk messages array
            if (!isset($body['messages']) || !is_array($body['messages'])) {
                dump('❌ Mangler messages array');
                return false;
            }
            dump('✅ Messages array OK');

            // Sjekk første melding
            $message = $body['messages'][0] ?? null;
            if (!$message) {
                dump('❌ Ingen melding i messages array');
                return false;
            }

            // Sjekk recipient (skal være UTEN +)
            if (!isset($message['recipient'])) {
                dump('❌ Mangler recipient');
                return false;
            }
            if (str_starts_with($message['recipient'], '+')) {
                dump('❌ Recipient har + symbol: ' . $message['recipient']);
                return false;
            }
            dump('✅ Recipient OK (uten +): ' . $message['recipient']);

            // Sjekk senderType
            if (!isset($message['senderType']) || $message['senderType'] !== 5) {
                dump('❌ Feil senderType: ' . ($message['senderType'] ?? 'mangler'));
                return false;
            }
            dump('✅ SenderType OK: 5 (Alphanumeric)');

            // Sjekk sender
            if (!isset($message['sender'])) {
                dump('❌ Mangler sender');
                return false;
            }
            if (strlen($message['sender']) > 11) {
                dump('❌ Sender for lang (maks 11 tegn): ' . $message['sender']);
                return false;
            }
            dump('✅ Sender OK: ' . $message['sender']);

            // Sjekk contentText struktur
            if (!isset($message['contentText']['text'])) {
                dump('❌ Mangler contentText.text');
                return false;
            }
            dump('✅ ContentText OK: ' . $message['contentText']['text']);

            dump('');
            dump('🎉 ALLE SJEKKER PASSERTE!');
            dump('Request struktur er korrekt i henhold til TeletopiaSMS HTTP JSON API');
            dump('');
            dump('Full request body:');
            dump($body);

            return true;
        });

        // Verifiser at resultatet er success
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['credits_used']);
    }

    /**
     * Test telefonnummer normalisering
     */
    public function test_phone_number_normalization(): void
    {
        $tenant = Tenant::factory()->create(['active' => true]);
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test_key',
            'enabled' => true
        ]);

        Http::fake([
            'api1.teletopiasms.no/*' => Http::response([
                'responses' => [
                    [
                        'accepted' => 1,
                        'messageId' => 'test_id',
                        'recipient' => '4790039911',
                        'statusCode' => 1000,
                        'statusDescription' => 'accepted'
                    ]
                ]
            ], 200)
        ]);

        $smsService = new TeletopiaSmsService();

        // Test forskjellige input formater
        $testCases = [
            '90039911' => '4790039911',
            '+47 900 39 911' => '4790039911',
            '47-90-03-99-11' => '4790039911',
            '4790039911' => '4790039911',
            '+4790039911' => '4790039911',
        ];

        foreach ($testCases as $input => $expected) {
            Http::fake([
                'api1.teletopiasms.no/*' => Http::response([
                    'responses' => [
                        [
                            'accepted' => 1,
                            'messageId' => 'test_id',
                            'recipient' => $expected,
                            'statusCode' => 1000,
                            'statusDescription' => 'accepted'
                        ]
                    ]
                ], 200)
            ]);

            $result = $smsService->sendSms($tenant->id, $input, 'Test');

            Http::assertSent(function ($request) use ($expected, $input) {
                $body = $request->data();
                $recipient = $body['messages'][0]['recipient'] ?? '';
                
                if ($recipient !== $expected) {
                    dump("❌ Input: {$input} => Forventet: {$expected}, Fikk: {$recipient}");
                    return false;
                }
                
                dump("✅ Input: {$input} => Output: {$recipient}");
                return true;
            });
        }

        dump('');
        dump('🎉 ALLE TELEFONNUMMER-FORMATER NORMALISERES KORREKT!');
    }
}
