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
}

// SMS Controller - håndterer SMS settings og test-funksjon for tenant

