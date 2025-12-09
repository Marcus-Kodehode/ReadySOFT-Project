<?php

// File: app/Services/TeletopiaSmsService.php

namespace App\Services;

use App\Models\SmsSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TeletopiaSmsService
{
    /**
     * Send SMS via Teletopia API
     *
     * @param int $tenantId
     * @param string $phoneNumber
     * @param string $message
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendSms(int $tenantId, string $phoneNumber, string $message): array
    {
        try {
            // Hent SMS settings for tenant
            $settings = SmsSettings::where('tenant_id', $tenantId)->first();

            // Sjekk om settings eksisterer
            if (!$settings) {
                return [
                    'success' => false,
                    'message' => 'SMS settings not found for this tenant'
                ];
            }

            // Sjekk om SMS er enabled
            if (!$settings->enabled) {
                return [
                    'success' => false,
                    'message' => 'SMS functionality is not enabled'
                ];
            }

            // Hent API-nøkkel (automatisk dekryptert via cast)
            $apiKey = $settings->api_key;

            if (empty($apiKey)) {
                return [
                    'success' => false,
                    'message' => 'API key is not configured'
                ];
            }

            // Send HTTP POST til Teletopia API
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}"
                ])
                ->post('https://api.teletopia.no/sms/send', [
                    'to' => $phoneNumber,
                    'message' => $message
                ]);

            // Sjekk om request var vellykket
            if ($response->successful()) {
                Log::info("SMS sent to {$phoneNumber}", [
                    'tenant_id' => $tenantId,
                    'success' => true
                ]);

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully'
                ];
            } else {
                Log::warning("Failed to send SMS to {$phoneNumber}", [
                    'tenant_id' => $tenantId,
                    'success' => false,
                    'status' => $response->status()
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send SMS'
                ];
            }

        } catch (\Exception $e) {
            Log::error("Exception while sending SMS to {$phoneNumber}", [
                'tenant_id' => $tenantId,
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}

// Teletopia SMS service - sender SMS via Teletopia API med error handling og logging
