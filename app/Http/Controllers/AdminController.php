<?php
// File: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
     * Hent alle tenants med søk, filter og sortering
     * 
     * Støtter søk på navn og slug, samt filtrering på aktiv status
     * Støtter sortering på alle kolonner (name, slug, business_type, active, created_at)
     * Returnerer paginerte resultater (20 per side)
     */
    public function tenants(Request $request)
    {
        // Definer tillatte sorteringskolonner
        $allowedSortColumns = ['name', 'slug', 'business_type', 'active', 'created_at'];
        
        // Hent sorteringsparametere fra request, med defaults
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Valider sorteringskolonne
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        // Valider sorteringsretning
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $tenants = Tenant::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('slug', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filter === 'active', function ($query) {
                $query->where('active', true);
            })
            ->when($request->filter === 'inactive', function ($query) {
                $query->where('active', false);
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(20);

        return view('admin.tenants', compact('tenants'));
    }

    /**
     * Toggle aktiv status for en tenant
     * 
     * Finner tenant og bytter active status (true -> false eller false -> true)
     * Tømmer cache for tenant list siden status er endret
     * Returnerer tilbake til forrige side med flash message
     */
    public function toggleTenantStatus($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $tenant->update(['active' => !$tenant->active]);
        
        // Tøm cache for tenant list siden tenant status er endret
        Cache::forget('landing.tenants');
        
        $status = $tenant->active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Tenant '{$tenant->name}' has been {$status} successfully.");
    }
}

// Admin controller - system administrator dashboard og tenant management
