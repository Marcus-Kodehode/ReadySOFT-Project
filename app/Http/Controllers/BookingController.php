<?php

// File: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Http\Request;

/**
 * BookingController
 * 
 * Håndterer booking-administrasjon for tenant-admin.
 * Tenant kan se og administrere bookinger for sine ressurser.
 */
class BookingController extends Controller
{
    /**
     * Display a listing of bookings for the authenticated tenant.
     * 
     * Henter alle bookinger for tenant sine ressurser med filtrering og sortering.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Hent alle resource IDs for innlogget tenant
        $resourceIds = Resource::where('tenant_id', auth()->user()->tenant_id)
            ->pluck('id');

        // Start query med eager loading av resource
        $query = Booking::with('resource')
            ->whereIn('resource_id', $resourceIds);

        // Filtrer basert på request parameter
        $filter = $request->input('filter', 'all');
        
        if ($filter === 'upcoming') {
            $query->whereDate('booking_date', '>=', now());
        } elseif ($filter === 'past') {
            $query->whereDate('booking_date', '<', now());
        }
        // 'all' krever ingen ekstra filtrering

        // Sorter etter booking_date og start_time (nyeste først)
        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('bookings.index', compact('bookings', 'filter'));
    }

    /**
     * Display the specified booking.
     * 
     * Viser detaljer for en enkelt booking. Sjekker at bookingen tilhører
     * en ressurs som eies av innlogget tenant.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        // Finn booking med eager loading av resource
        $booking = Booking::with('resource')->findOrFail($id);

        // Sjekk at booking tilhører en ressurs som eies av innlogget tenant
        if ($booking->resource->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Update the status of the specified booking.
     * 
     * Oppdaterer status for en booking. Validerer at status er gyldig
     * og at bookingen tilhører innlogget tenant.
     * 
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateStatus($id, Request $request)
    {
        // Valider status input
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        // Finn booking med eager loading av resource
        $booking = Booking::with('resource')->findOrFail($id);

        // Sjekk at booking tilhører en ressurs som eies av innlogget tenant
        if ($booking->resource->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Oppdater booking status
        $booking->status = $validated['status'];
        $booking->save();

        // Returner redirect med success melding
        return redirect()
            ->route('bookings.show', $booking->id)
            ->with('success', 'Booking status updated successfully to ' . $validated['status'] . '.');
    }
}

// Booking management controller - tenant administrerer bookinger for sine ressurser
