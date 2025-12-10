<?php
// File: app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Vis landingsside med alle aktive tenants
     * 
     * Henter alle aktive tenants for å vise på forsiden
     * Tenants sorteres med nyeste først
     */
    public function index()
    {
        // Hent alle aktive tenants, sortert med nyeste først
        $tenants = Tenant::where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('welcome', compact('tenants'));
    }
}

// Landing controller - håndterer landingsside med oversikt over alle aktive tenants
