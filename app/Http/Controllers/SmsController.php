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
     * at API-nøkkelen er korrekt konfigurert og at SMS-sending fungerer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Valider input
        $validated = $request->validate([
            'phone_number' => 'required|string|regex:/^[+]?[0-9]{8,15}$/',
        ]);

        // Hent SMS settings for tenant
        $smsSettings = SmsSettings::where('tenant_id', $tenantId)->first();

        // Sjekk om settings eksisterer
        if (!$smsSettings || empty($smsSettings->api_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure your API key first'
            ], 400);
        }

        // Opprett SMS service og send test-melding
        $smsService = app(\App\Services\TeletopiaSmsService::class);
        $result = $smsService->sendSms(
            $tenantId,
            $validated['phone_number'],
            'This is a test SMS from ReadySoft. Your SMS configuration is working correctly!'
        );

        // Returner resultat som JSON
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['success'] ? 200 : 400);
    }
}

// SMS Controller - håndterer SMS settings og test-funksjon for tenant

