<?php
// File: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Vis admin dashboard med statistikk
     * 
     * Henter totalt antall tenants, aktive tenants, inaktive tenants og totalt antall bookinger
     */
    public function index()
    {
        // Hent statistikk
        $total_tenants = Tenant::count();
        $active_tenants = Tenant::where('active', true)->count();
        $inactive_tenants = Tenant::where('active', false)->count();
        $total_bookings = Booking::count();

        return view('admin.dashboard', compact(
            'total_tenants',
            'active_tenants',
            'inactive_tenants',
            'total_bookings'
        ));
    }

    /**
     * Hent alle tenants med søk og filter
     * 
     * Støtter søk på navn og filtrering på aktiv status
     * Returnerer paginerte resultater (20 per side)
     */
    public function tenants(Request $request)
    {
        $tenants = Tenant::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->when($request->filter === 'active', function ($query) {
                $query->where('active', true);
            })
            ->when($request->filter === 'inactive', function ($query) {
                $query->where('active', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.tenants', compact('tenants'));
    }

    /**
     * Toggle aktiv status for en tenant
     * 
     * Finner tenant og bytter active status (true -> false eller false -> true)
     * Returnerer tilbake til forrige side med flash message
     */
    public function toggleTenantStatus($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $tenant->update(['active' => !$tenant->active]);
        
        $status = $tenant->active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Tenant '{$tenant->name}' has been {$status} successfully.");
    }
}

// Admin controller - system administrator dashboard og tenant management
