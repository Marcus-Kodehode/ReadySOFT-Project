<?php

// File: app/Http/Controllers/PublicBookingController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Booking;
use App\Models\Resource;
use App\Services\AvailabilityService;
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

        // Sjekk for konflikter basert på capacity
        // Tell antall overlappende bookinger og sammenlign med ressursens kapasitet
        $overlappingBookingsCount = Booking::where('resource_id', $validated['resource_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                // Bruk Carbon for å sikre korrekt tidssammenligning
                $query->whereRaw('TIME(end_time) > TIME(?)', [$validated['start_time']])
                      ->whereRaw('TIME(start_time) < TIME(?)', [$validated['end_time']]);
            })
            ->count();

        // Hvis antall overlappende bookinger >= capacity, er det fullt
        if ($overlappingBookingsCount >= $resource->capacity) {
            return back()->withErrors([
                'booking' => 'This time slot is fully booked. Please select a different time.'
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

        // Redirect til confirmation side
        return redirect()->route('booking.confirmation', ['id' => $booking->id]);
    }

    /**
     * Display the booking confirmation page.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function confirmation(int $id)
    {
        // Finn booking med resource relationship
        $booking = Booking::with('resource')->findOrFail($id);

        return view('public.booking-confirmation', compact('booking'));
    }

    /**
     * Get available time slots for a resource on a specific date.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableSlots(Request $request)
    {
        // Valider input
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'date' => 'required|date|after:yesterday',
        ]);

        // Hent ressurs
        $resource = Resource::findOrFail($validated['resource_id']);

        // Hent ledige tidsluker via AvailabilityService
        $availabilityService = new AvailabilityService();
        $slots = $availabilityService->getAvailableSlots($resource, $validated['date']);

        return response()->json([
            'slots' => $slots,
        ]);
    }
}

// Public booking controller - håndterer offentlig bookingside uten autentisering
