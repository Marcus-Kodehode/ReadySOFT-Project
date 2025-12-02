<?php

// File: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * DashboardController
 * 
 * Håndterer tenant dashboard med statistikk og kommende bookinger.
 * Viser oversikt over dagens bookinger, ukens bookinger, aktive ressurser
 * og subscription status for innlogget tenant.
 */
class DashboardController extends Controller
{
    /**
     * Display the tenant dashboard.
     * 
     * Henter statistikk og kommende bookinger for innlogget tenant.
     * Bruker optimaliserte queries med count() og eager loading.
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        // Hent alle resource IDs for denne tenant
        $resourceIds = Resource::where('tenant_id', $tenantId)->pluck('id');

        // Statistikk: Bookinger i dag
        $bookingsToday = Booking::whereIn('resource_id', $resourceIds)
            ->whereDate('booking_date', today())
            ->count();

        // Statistikk: Bookinger denne uken
        $bookingsThisWeek = Booking::whereIn('resource_id', $resourceIds)
            ->whereBetween('booking_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        // Statistikk: Antall aktive ressurser
        $activeResources = Resource::where('tenant_id', $tenantId)
            ->where('active', true)
            ->count();

        // Subscription status
        $subscriptionStatus = $user->tenant->subscriptions()
            ->where('active', true)
            ->exists();

        // Kommende bookinger (5 siste med resource eager loaded)
        $upcomingBookings = Booking::whereIn('resource_id', $resourceIds)
            ->where('booking_date', '>=', today())
            ->with('resource')
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'bookingsToday',
            'bookingsThisWeek',
            'activeResources',
            'subscriptionStatus',
            'upcomingBookings'
        ));
    }
}

// Controller for tenant dashboard - henter statistikk og kommende bookinger
