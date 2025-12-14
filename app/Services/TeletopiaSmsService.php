<?php

// File: app/Services/TeletopiaSmsService.php

namespace App\Services;

use App\Models\SmsSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TeletopiaSmsService
{
    private const MAX_SMS_LENGTH = 160; // Standard SMS length
    private const MAX_WORD_COUNT = 50; // Sikrer at vi holder oss innenfor 1 SMS
    
    /**
     * Send SMS via Teletopia API
     *
     * @param int $tenantId
     * @param string $phoneNumber
     * @param string $message
     * @return array ['success' => bool, 'message' => string, 'credits_used' => int]
     */
    public function sendSms(int $tenantId, string $phoneNumber, string $message): array
    {
        try {
            // Valider meldingslengde FØRST - kritisk for å unngå ekstra credits
            $validationResult = $this->validateMessage($message);
            if (!$validationResult['valid']) {
                return [
                    'success' => false,
                    'message' => $validationResult['error'],
                    'credits_used' => 0
                ];
            }

            // Normaliser telefonnummer
            $phoneNumber = $this->normalizePhoneNumber($phoneNumber);
            if (!$phoneNumber) {
                return [
                    'success' => false,
                    'message' => 'Invalid phone number format',
                    'credits_used' => 0
                ];
            }

            // Hent SMS settings for tenant
            $settings = SmsSettings::where('tenant_id', $tenantId)->first();

            if (!$settings) {
                return [
                    'success' => false,
                    'message' => 'SMS settings not found for this tenant',
                    'credits_used' => 0
                ];
            }

            if (!$settings->enabled) {
                return [
                    'success' => false,
                    'message' => 'SMS functionality is not enabled',
                    'credits_used' => 0
                ];
            }

            // Hent credentials fra .env
            $username = config('services.teletopia.username', env('TELETOPIA_USERNAME'));
            $password = config('services.teletopia.password', env('TELETOPIA_PASSWORD'));

            if (empty($username) || empty($password)) {
                return [
                    'success' => false,
                    'message' => 'Teletopia credentials not configured',
                    'credits_used' => 0
                ];
            }

            // Bygg JSON request i henhold til TeletopiaSMS HTTP JSON API
            $payload = [
                'auth' => [
                    'username' => $username,
                    'password' => $password
                ],
                'messages' => [
                    [
                        'recipient' => $phoneNumber,
                        'senderType' => 5, // 5 = Alphanumeric
                        'sender' => 'ReadySoft', // Maks 11 tegn
                        'contentText' => [
                            'text' => $message
                        ]
                    ]
                ]
            ];

            // Send HTTP POST til TeletopiaSMS JSON API (primary endpoint)
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api1.teletopiasms.no/gateway/v3/json', $payload);

            // Logg full response for debugging
            Log::info("TeletopiaSMS API Response", [
                'status' => $response->status(),
                'body' => $response->body(),
                'tenant_id' => $tenantId
            ]);

            // Sjekk HTTP status
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Sjekk om meldingen ble akseptert (TeletopiaSMS returnerer "accepted": 1)
                if (isset($responseData['responses'][0]['accepted']) && $responseData['responses'][0]['accepted'] === 1) {
                    $messageId = $responseData['responses'][0]['messageId'] ?? 'unknown';
                    
                    Log::info("SMS sent successfully", [
                        'tenant_id' => $tenantId,
                        'phone' => $phoneNumber,
                        'message_id' => $messageId,
                        'message_length' => strlen($message),
                        'word_count' => str_word_count($message),
                        'credits_used' => 1
                    ]);

                    return [
                        'success' => true,
                        'message' => 'SMS sent successfully (Message ID: ' . $messageId . ')',
                        'credits_used' => 1
                    ];
                } else {
                    // Melding ble avvist
                    $statusDesc = $responseData['responses'][0]['statusDescription'] ?? 'Unknown error';
                    
                    Log::error("SMS rejected by TeletopiaSMS", [
                        'tenant_id' => $tenantId,
                        'phone' => $phoneNumber,
                        'status_code' => $responseData['responses'][0]['statusCode'] ?? 'unknown',
                        'status_description' => $statusDesc
                    ]);

                    return [
                        'success' => false,
                        'message' => 'SMS rejected: ' . $statusDesc,
                        'credits_used' => 0
                    ];
                }
            } else {
                Log::error("Failed to send SMS - HTTP error", [
                    'tenant_id' => $tenantId,
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send SMS: HTTP ' . $response->status(),
                    'credits_used' => 0
                ];
            }

        } catch (\Exception $e) {
            Log::error("Exception while sending SMS", [
                'tenant_id' => $tenantId,
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'credits_used' => 0
            ];
        }
    }

    /**
     * Valider melding for å sikre at den holder seg innenfor 1 SMS (1 credit)
     */
    private function validateMessage(string $message): array
    {
        $message = trim($message);
        
        if (empty($message)) {
            return ['valid' => false, 'error' => 'Message cannot be empty'];
        }

        // Tell ord
        $wordCount = str_word_count($message);
        if ($wordCount > self::MAX_WORD_COUNT) {
            return [
                'valid' => false, 
                'error' => "Message exceeds maximum of " . self::MAX_WORD_COUNT . " words (current: {$wordCount} words)"
            ];
        }

        // Sjekk lengde
        $length = strlen($message);
        if ($length > self::MAX_SMS_LENGTH) {
            return [
                'valid' => false,
                'error' => "Message exceeds maximum of " . self::MAX_SMS_LENGTH . " characters (current: {$length} characters)"
            ];
        }

        return ['valid' => true];
    }

    /**
     * Normaliser telefonnummer til Teletopia format (UTEN +)
     * Teletopia krever format: 4712345678 (ikke +4712345678)
     */
    private function normalizePhoneNumber(string $phoneNumber): ?string
    {
        // Fjern alle mellomrom, bindestreker, parenteser og +
        $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $phoneNumber);
        
        // Hvis det starter med 00, fjern det og bruk resten
        if (Str::startsWith($cleaned, '00')) {
            return substr($cleaned, 2);
        }
        
        // Hvis det er et norsk nummer uten landskode (8 siffer), legg til 47
        if (strlen($cleaned) === 8 && ctype_digit($cleaned)) {
            return '47' . $cleaned;
        }
        
        // Hvis det allerede har landskode, returner som det er
        if (ctype_digit($cleaned) && strlen($cleaned) >= 10) {
            return $cleaned;
        }
        
        return null;
    }
}

// Teletopia SMS service - sender SMS via Teletopia API med error handling og logging
