<?php

// File: app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SlugService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController
 * 
 * Håndterer brukerregistrering med multi-tenant funksjonalitet.
 * Oppretter Tenant, User og Subscription i én atomisk transaksjon.
 */
class RegisteredUserController extends Controller
{
    protected SlugService $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * Oppretter Tenant, User og Subscription i én database-transaksjon.
     * Hvis noe feiler, rulles alt tilbake.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'min:3', 'max:255'],
            'business_type' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'unique:tenants,slug'],
        ]);

        // Generer slug fra business_name hvis ikke oppgitt (fallback for no-JS)
        // Eksempel: "Salong Rosa" → "salong-rosa"
        $slug = $request->slug;
        if (empty($slug)) {
            $slug = $this->slugService->generateSlug($request->business_name);
            
            // Hvis generert slug er opptatt, legg til suffix
            if (!$this->slugService->isSlugAvailable($slug)) {
                $alternatives = $this->slugService->suggestAlternatives($slug, 1);
                $slug = $alternatives[0] ?? $slug . '-' . time();
            }
        }

        // Database transaksjon: Opprett Tenant → User → Subscription
        // Hvis noe feiler, rulles alt tilbake
        DB::transaction(function () use ($request, $slug, &$user) {
            // 1. Opprett Tenant
            $tenant = Tenant::create([
                'name' => $request->business_name,
                'slug' => $slug,
                'business_type' => $request->business_type,
                'active' => true,
            ]);

            // 2. Opprett User med tenant_id
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role' => 'tenant_admin',
            ]);

            // 3. Opprett Subscription med Basic plan
            $basicPlan = Plan::first(); // Hent første plan (Basic)
            
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $basicPlan->id,
                'active' => true,
                'active_from' => now(),
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Welcome! Let\'s get started');
    }
}

// Controller håndterer registrering av nye tenants med full multi-tenant setup.
// Bruker database-transaksjoner for å sikre dataintegritet.
