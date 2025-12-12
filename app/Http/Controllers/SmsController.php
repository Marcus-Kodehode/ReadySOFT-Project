<?php

// File: app/Http/Controllers/SmsController.php

namespace App\Http\Controllers;

use App\Models\SmsSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * SmsController
 * 
 * Håndterer SMS settings for tenant.
 * Lar tenant-administratorer konfigurere Teletopia API-nøkkel,
 * aktivere/deaktivere SMS-funksjonalitet, og teste SMS-sending.
 */
class SmsController extends Controller
{
    /**
     * Display the SMS settings page.
     * 
     * Henter eksisterende SMS settings for innlogget tenant,
     * eller oppretter en tom instans hvis ingen settings finnes.
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Hent eller opprett SMS settings for denne tenant
        $smsSettings = SmsSettings::firstOrNew(['tenant_id' => $tenantId]);

        return view('sms.index', compact('smsSettings'));
    }

    /**
     * Update SMS settings for the tenant.
     * 
     * Lagrer eller oppdaterer API-nøkkel og enabled status for tenant.
     * API-nøkkelen krypteres automatisk via model cast.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Valider input
        $validated = $request->validate([
            'api_key' => 'required|string|min:10',
            'enabled' => 'boolean',
        ]);

        // Hent eller opprett SMS settings
        $smsSettings = SmsSettings::firstOrNew(['tenant_id' => $tenantId]);
        
        // Oppdater verdier
        $smsSettings->api_key = $validated['api_key'];
        $smsSettings->enabled = $request->has('enabled') ? true : false;
        $smsSettings->tenant_id = $tenantId;
        
        // Lagre til database (API-nøkkel krypteres automatisk)
        $smsSettings->save();

        // Redirect tilbake med success melding
        return redirect()->route('dashboard.sms')
            ->with('success', 'SMS settings saved successfully');
    }

    /**
     * Send a test SMS to verify configuration.
     * 
     * Sender en test-SMS til angitt telefonnummer for å verifisere
     * at Teletopia-konfigurasjonen fungerer korrekt.
     * VIKTIG: Bruker 1 SMS credit per test!
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Valider input med strenge regler
        $validated = $request->validate([
            'phone_number' => [
                'required',
                'string',
                'regex:/^[+]?[0-9\s\-\(\)]{8,20}$/'
            ],
            'message' => [
                'required',
                'string',
                'max:160', // Standard SMS lengde
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count($value);
                    if ($wordCount > 50) {
                        $fail("Message must not exceed 50 words (current: {$wordCount} words)");
                    }
                }
            ]
        ]);

        // Hent SMS settings for tenant
        $smsSettings = SmsSettings::where('tenant_id', $tenantId)->first();

        // Sjekk om settings eksisterer og er enabled
        if (!$smsSettings) {
            return response()->json([
                'success' => false,
                'message' => 'SMS settings not found. Please configure SMS first.'
            ], 400);
        }

        if (!$smsSettings->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'SMS functionality is not enabled. Please enable it in settings.'
            ], 400);
        }

        // Sjekk at Teletopia credentials er konfigurert
        $username = config('services.teletopia.username', env('TELETOPIA_USERNAME'));
        $password = config('services.teletopia.password', env('TELETOPIA_PASSWORD'));

        if (empty($username) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Teletopia credentials not configured in .env file'
            ], 400);
        }

        // Opprett SMS service og send test-melding
        $smsService = app(\App\Services\TeletopiaSmsService::class);
        $result = $smsService->sendSms(
            $tenantId,
            $validated['phone_number'],
            $validated['message']
        );

        // Returner resultat med credits info
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'credits_used' => $result['credits_used'] ?? 0
        ], $result['success'] ? 200 : 400);
    }
}

// SMS Controller - håndterer SMS settings og test-funksjon for tenant

