<?php

// File: app/Http/Middleware/CheckActiveSubscription.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckActiveSubscription Middleware
 * 
 * Sjekker om den autentiserte brukeren har en aktiv subscription.
 * Hvis subscription er inaktiv, redirectes brukeren til /subscription/inactive.
 */
class CheckActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sjekk om bruker er autentisert
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Hvis bruker ikke har tenant (f.eks. admin), la dem passere
        if (!$user->tenant_id) {
            return $next($request);
        }

        // Eager load tenant med subscription for å unngå N+1 queries
        $tenant = $user->tenant()->with('subscriptions')->first();

        // Sjekk om tenant har minst én aktiv subscription
        $hasActiveSubscription = $tenant && $tenant->subscriptions()
            ->where('active', true)
            ->exists();

        // Hvis ingen aktiv subscription, redirect til inactive-side
        if (!$hasActiveSubscription) {
            return redirect()->route('subscription.inactive');
        }

        // Subscription er aktiv, fortsett til neste middleware
        return $next($request);
    }
}

// Middleware som sikrer at kun brukere med aktiv subscription kan aksessere beskyttede ruter.
// Brukes på alle /dashboard/* ruter for å håndheve subscription-krav.

