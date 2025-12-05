<?php

// File: app/Http/Controllers/PublicBookingController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    /**
     * Display the public booking page for a tenant.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
    {
        // Finn tenant via slug, kast 404 hvis ikke funnet
        $tenant = Tenant::where('slug', $slug)
            ->with('resources') // Eager load resources for å unngå N+1 queries
            ->firstOrFail();

        return view('public.booking', compact('tenant'));
    }
}

// Public booking controller - håndterer offentlig bookingside uten autentisering
