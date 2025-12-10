<?php
// File: app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Vis landingsside med alle aktive tenants
     * 
     * Henter alle aktive tenants for å vise på forsiden
     * Tenants sorteres med nyeste først
     * Resultatet caches i 5 minutter for bedre ytelse
     */
    public function index()
    {
        // Hent alle aktive tenants fra cache (5 minutter)
        // Cache key: 'landing.tenants'
        $tenants = Cache::remember('landing.tenants', 300, function () {
            return Tenant::where('active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return view('welcome', compact('tenants'));
    }
}

// Landing controller - håndterer landingsside med oversikt over alle aktive tenants
