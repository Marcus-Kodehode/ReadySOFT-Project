<?php

// File: app/Http/Controllers/PublicBookingController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Store a new booking for a tenant's resource.
     *
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, string $slug)
    {
        // Valider input
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'booking_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => ['required', 'regex:/^[+]?[0-9]{8,15}$/'],
            'notes' => 'nullable|string',
        ]);

        // Sjekk at ressursen tilhører denne tenanten
        $tenant = Tenant::where('slug', $slug)->firstOrFail();
        $resource = Resource::where('id', $validated['resource_id'])
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        // Sjekk for konflikter - overlappende bookinger
        // Hent alle eksisterende bookinger for denne ressursen og datoen
        $existingBookings = Booking::where('resource_id', $validated['resource_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        // Sjekk manuelt for overlapp (mer robust enn SQL-sammenligning)
        $hasConflict = false;
        foreach ($existingBookings as $existing) {
            // Normaliser tidsformater med full dato for korrekt sammenligning
            $existingStart = \Carbon\Carbon::parse($validated['booking_date'] . ' ' . $existing->start_time);
            $existingEnd = \Carbon\Carbon::parse($validated['booking_date'] . ' ' . $existing->end_time);
            $newStart = \Carbon\Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $newEnd = \Carbon\Carbon::parse($validated['booking_date'] . ' ' . $validated['end_time']);
            
            // To tidsperioder overlapper hvis:
            // - Ny start er før eksisterende slutt OG
            // - Ny slutt er etter eksisterende start
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                $hasConflict = true;
                break;
            }
        }

        if ($hasConflict) {
            return back()->withErrors([
                'booking' => 'This time slot is no longer available. Please select a different time.'
            ])->withInput();
        }

        // Lagre booking
        $booking = Booking::create([
            'resource_id' => $validated['resource_id'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'confirmed',
        ]);

        // Redirect til confirmation side (Task 8.4 vil implementere denne ruten)
        // For nå redirecter vi tilbake til booking-siden med success melding
        return redirect()->route('booking.show', ['slug' => $slug])
            ->with('success', 'Your booking has been confirmed!')
            ->with('booking_id', $booking->id);
    }
}

// Public booking controller - håndterer offentlig bookingside uten autentisering
