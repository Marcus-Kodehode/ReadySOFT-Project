<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\TeletopiaSmsService;
use App\Models\SmsSettings;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeletopiaSmsApiValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Validerer TeletopiaSMS API request struktur
     * SENDER IKKE FAKTISK SMS - kun validering
     */
    public function test_validates_api_request_structure(): void
    {
        echo "\n\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "  TELETOPIA SMS API STRUKTUR VALIDERING\n";
        echo "═══════════════════════════════════════════════════════════════\n\n";

        $tenant = Tenant::factory()->create(['active' => true]);
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test_key',
            'enabled' => true
        ]);

        // Mock TeletopiaSMS response
        Http::fake([
            'api1.teletopiasms.no/*' => Http::response([
                'responses' => [
                    [
                        'accepted' => 1,
                        'messageId' => 'test_msg_12345',
                        'recipient' => '4790039911',
                        'statusCode' => 1000,
                        'statusDescription' => 'accepted'
                    ]
                ]
            ], 200)
        ]);

        $smsService = new TeletopiaSmsService();
        $result = $smsService->sendSms(
            $tenant->id,
            '90039911',
            'Test message for validation'
        );

        // Valider request
        Http::assertSent(function ($request) {
            $checks = [];
            
            // 1. URL
            $checks['URL'] = [
                'expected' => 'https://api1.teletopiasms.no/gateway/v3/json',
                'actual' => $request->url(),
                'pass' => $request->url() === 'https://api1.teletopiasms.no/gateway/v3/json'
            ];

            // 2. HTTP Method
            $checks['HTTP Method'] = [
                'expected' => 'POST',
                'actual' => $request->method(),
                'pass' => $request->method() === 'POST'
            ];

            // 3. Content-Type
            $contentType = $request->header('Content-Type')[0] ?? '';
            $checks['Content-Type'] = [
                'expected' => 'application/json',
                'actual' => $contentType,
                'pass' => str_contains($contentType, 'application/json')
            ];

            $body = $request->data();

            // 4. Auth struktur
            $checks['Auth Username'] = [
                'expected' => 'y3330c5nuv2',
                'actual' => $body['auth']['username'] ?? 'MISSING',
                'pass' => isset($body['auth']['username']) && $body['auth']['username'] === 'y3330c5nuv2'
            ];

            $checks['Auth Password'] = [
                'expected' => 'LlTM060VKuq30iaJQcpl9JLK',
                'actual' => isset($body['auth']['password']) ? '***' . substr($body['auth']['password'], -4) : 'MISSING',
                'pass' => isset($body['auth']['password']) && $body['auth']['password'] === 'LlTM060VKuq30iaJQcpl9JLK'
            ];

            // 5. Messages array
            $checks['Messages Array'] = [
                'expected' => 'array with 1 message',
                'actual' => isset($body['messages']) ? 'array with ' . count($body['messages']) . ' message(s)' : 'MISSING',
                'pass' => isset($body['messages']) && is_array($body['messages']) && count($body['messages']) === 1
            ];

            $message = $body['messages'][0] ?? [];

            // 6. Recipient (UTEN +)
            $checks['Recipient'] = [
                'expected' => '4790039911 (no + symbol)',
                'actual' => $message['recipient'] ?? 'MISSING',
                'pass' => isset($message['recipient']) && $message['recipient'] === '4790039911' && !str_starts_with($message['recipient'], '+')
            ];

            // 7. SenderType
            $checks['SenderType'] = [
                'expected' => '5 (Alphanumeric)',
                'actual' => $message['senderType'] ?? 'MISSING',
                'pass' => isset($message['senderType']) && $message['senderType'] === 5
            ];

            // 8. Sender
            $checks['Sender'] = [
                'expected' => 'ReadySoft (max 11 chars)',
                'actual' => $message['sender'] ?? 'MISSING',
                'pass' => isset($message['sender']) && $message['sender'] === 'ReadySoft' && strlen($message['sender']) <= 11
            ];

            // 9. ContentText
            $checks['ContentText'] = [
                'expected' => 'object with text property',
                'actual' => isset($message['contentText']['text']) ? 'text: "' . substr($message['contentText']['text'], 0, 30) . '..."' : 'MISSING',
                'pass' => isset($message['contentText']['text'])
            ];

            // Print results
            echo "┌─────────────────────────────────────────────────────────────┐\n";
            echo "│ VALIDERING AV API REQUEST                                   │\n";
            echo "└─────────────────────────────────────────────────────────────┘\n\n";

            $allPass = true;
            foreach ($checks as $name => $check) {
                $status = $check['pass'] ? '✅' : '❌';
                $allPass = $allPass && $check['pass'];
                
                echo sprintf("%-20s %s\n", $name . ':', $status);
                echo sprintf("  Expected: %s\n", $check['expected']);
                echo sprintf("  Actual:   %s\n", $check['actual']);
                echo "\n";
            }

            if ($allPass) {
                echo "═══════════════════════════════════════════════════════════════\n";
                echo "  ✅ ALLE SJEKKER PASSERTE!\n";
                echo "  API request er korrekt strukturert.\n";
                echo "  Klar for å sende ekte SMS!\n";
                echo "═══════════════════════════════════════════════════════════════\n\n";
            } else {
                echo "═══════════════════════════════════════════════════════════════\n";
                echo "  ❌ NOEN SJEKKER FEILET!\n";
                echo "  Se detaljer over.\n";
                echo "═══════════════════════════════════════════════════════════════\n\n";
            }

            return $allPass;
        });

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['credits_used']);
    }
}
