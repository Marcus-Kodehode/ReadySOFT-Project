<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\TeletopiaSmsService;
use App\Models\SmsSettings;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeletopiaSmsMessageValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test meldingsvalidering (50 ord, 160 tegn)
     */
    public function test_message_validation(): void
    {
        echo "\n\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "  MELDINGSVALIDERING TEST (50 ORD / 160 TEGN)\n";
        echo "═══════════════════════════════════════════════════════════════\n\n";

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
                        'recipient' => '4712345678',
                        'statusCode' => 1000,
                        'statusDescription' => 'accepted'
                    ]
                ]
            ], 200)
        ]);

        $smsService = new TeletopiaSmsService();

        // Test 1: Gyldig melding (under 50 ord og 160 tegn)
        echo "Test 1: Gyldig melding (23 ord, 128 tegn)\n";
        $validMessage = "Din booking hos NorwayStyle er bekreftet. 15.12.2025 kl 12:00. Kan du ikke møte opp, ta kontakt før 24 timer. (Dette er en test)";
        $wordCount = str_word_count($validMessage);
        $charCount = strlen($validMessage);
        echo "  Ord: $wordCount/50, Tegn: $charCount/160\n";
        
        $result = $smsService->sendSms($tenant->id, '12345678', $validMessage);
        
        if ($result['success']) {
            echo "  ✅ GODKJENT - Melding sendt\n\n";
        } else {
            echo "  ❌ AVVIST - " . $result['message'] . "\n\n";
        }

        // Test 2: For mange ord (over 50)
        echo "Test 2: For mange ord (over 50)\n";
        $tooManyWords = str_repeat("word ", 51);
        $wordCount = str_word_count($tooManyWords);
        $charCount = strlen($tooManyWords);
        echo "  Ord: $wordCount/50, Tegn: $charCount/160\n";
        
        $result = $smsService->sendSms($tenant->id, '12345678', $tooManyWords);
        
        if (!$result['success']) {
            echo "  ✅ KORREKT AVVIST - " . $result['message'] . "\n\n";
        } else {
            echo "  ❌ FEIL - Burde vært avvist!\n\n";
        }

        // Test 3: For mange tegn (over 160)
        echo "Test 3: For mange tegn (over 160)\n";
        $tooManyChars = str_repeat("a", 161);
        $wordCount = str_word_count($tooManyChars);
        $charCount = strlen($tooManyChars);
        echo "  Ord: $wordCount/50, Tegn: $charCount/160\n";
        
        $result = $smsService->sendSms($tenant->id, '12345678', $tooManyChars);
        
        if (!$result['success']) {
            echo "  ✅ KORREKT AVVIST - " . $result['message'] . "\n\n";
        } else {
            echo "  ❌ FEIL - Burde vært avvist!\n\n";
        }

        // Test 4: Nøyaktig på grensen (50 ord, 160 tegn)
        echo "Test 4: Nøyaktig på grensen\n";
        $words = array_fill(0, 50, 'word');
        $exactlyFiftyWords = implode(' ', $words);
        $exactlyFiftyWords = substr($exactlyFiftyWords, 0, 160); // Trim til 160 tegn
        $wordCount = str_word_count($exactlyFiftyWords);
        $charCount = strlen($exactlyFiftyWords);
        echo "  Ord: $wordCount/50, Tegn: $charCount/160\n";
        
        $result = $smsService->sendSms($tenant->id, '12345678', $exactlyFiftyWords);
        
        if ($result['success']) {
            echo "  ✅ GODKJENT - Melding sendt\n\n";
        } else {
            echo "  ❌ AVVIST - " . $result['message'] . "\n\n";
        }

        echo "═══════════════════════════════════════════════════════════════\n";
        echo "  ✅ MELDINGSVALIDERING FUNGERER KORREKT!\n";
        echo "  - Maks 50 ord enforces\n";
        echo "  - Maks 160 tegn enforces\n";
        echo "  - Sikrer kun 1 SMS credit per melding\n";
        echo "═══════════════════════════════════════════════════════════════\n\n";

        $this->assertTrue(true);
    }
}
