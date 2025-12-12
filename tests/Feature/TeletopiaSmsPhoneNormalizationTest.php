<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\TeletopiaSmsService;
use App\Models\SmsSettings;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeletopiaSmsPhoneNormalizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test telefonnummer normalisering
     */
    public function test_phone_number_normalization(): void
    {
        echo "\n\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "  TELEFONNUMMER NORMALISERING TEST\n";
        echo "═══════════════════════════════════════════════════════════════\n\n";

        $tenant = Tenant::factory()->create(['active' => true]);
        
        SmsSettings::create([
            'tenant_id' => $tenant->id,
            'api_key' => 'test_key',
            'enabled' => true
        ]);

        $smsService = new TeletopiaSmsService();

        $testCases = [
            '90039911' => '4790039911',
            '+47 900 39 911' => '4790039911',
            '47-90-03-99-11' => '4790039911',
            '4790039911' => '4790039911',
            '+4790039911' => '4790039911',
            '47 90 03 99 11' => '4790039911',
            '(47) 900-39-911' => '4790039911',
        ];

        echo "Testing " . count($testCases) . " different phone number formats:\n\n";

        $allPass = true;
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

            $pass = false;
            Http::assertSent(function ($request) use ($expected, $input, &$pass) {
                $body = $request->data();
                $recipient = $body['messages'][0]['recipient'] ?? '';
                $pass = ($recipient === $expected);
                return true;
            });

            $status = $pass ? '✅' : '❌';
            $allPass = $allPass && $pass;
            
            echo sprintf("%-25s => %-15s %s\n", 
                '"' . $input . '"', 
                $pass ? $expected : 'FAILED',
                $status
            );
        }

        echo "\n";
        if ($allPass) {
            echo "═══════════════════════════════════════════════════════════════\n";
            echo "  ✅ ALLE TELEFONNUMMER-FORMATER NORMALISERES KORREKT!\n";
            echo "  Telefonnummer sendes UTEN + symbol til TeletopiaSMS.\n";
            echo "═══════════════════════════════════════════════════════════════\n\n";
        } else {
            echo "═══════════════════════════════════════════════════════════════\n";
            echo "  ❌ NOEN FORMATER FEILET!\n";
            echo "═══════════════════════════════════════════════════════════════\n\n";
        }

        $this->assertTrue($allPass, 'All phone number formats should normalize correctly');
    }
}
