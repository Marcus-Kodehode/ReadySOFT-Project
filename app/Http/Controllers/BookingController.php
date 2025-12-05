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
}

// Booking management controller - tenant administrerer bookinger for sine ressurser
