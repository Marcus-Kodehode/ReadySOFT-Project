<?php
// File: app/Http/Controllers/Api/SlugController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SlugService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for slug validation
 * Håndterer sanntids slug-validering for registreringsskjema
 */
class SlugController extends Controller
{
    protected SlugService $slugService;
    
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
        
        // Rate limiting: Max 10 requests per minutt
        $this->middleware('throttle:10,1');
    }
    
    /**
     * Sjekk om slug er tilgjengelig
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $slug = $request->query('slug');
        
        // Valider at slug er oppgitt
        if (empty($slug)) {
            return response()->json([
                'available' => false,
                'suggestions' => [],
                'error' => 'Slug is required'
            ], 400);
        }
        
        // Sjekk om slug er ledig
        $available = $this->slugService->isSlugAvailable($slug);
        
        // Hvis opptatt, generer forslag
        $suggestions = [];
        if (!$available) {
            $suggestions = $this->slugService->suggestAlternatives($slug);
        }
        
        return response()->json([
            'available' => $available,
            'suggestions' => $suggestions
        ]);
    }
}

// API controller for slug-validering
