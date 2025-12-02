<?php

// File: app/Http/Middleware/CheckAdminRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckAdminRole Middleware
 * 
 * Sjekker om den autentiserte brukeren har admin-rolle.
 * Hvis bruker ikke er admin, returneres 403 Forbidden.
 */
class CheckAdminRole
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

        // Sjekk om bruker har admin-rolle
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        // Bruker er admin, fortsett til neste middleware
        return $next($request);
    }
}

// Middleware som sikrer at kun brukere med admin-rolle kan aksessere admin-ruter.
// Brukes på alle /admin/* ruter for å håndheve admin-tilgang.
