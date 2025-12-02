<?php
// File: app/Services/SlugService.php

namespace App\Services;

use App\Models\Tenant;

/**
 * Service for handling slug generation and validation
 * Håndterer slug-generering og validering for tenants
 */
class SlugService
{
    /**
     * Generer slug fra navn
     * Konverterer til lowercase, erstatter mellomrom med bindestrek
     * Håndterer norske tegn (æ, ø, å)
     */
    public function generateSlug(string $name): string
    {
        // Konverter til lowercase
        $slug = mb_strtolower($name);
        
        // Erstatt norske tegn
        $slug = str_replace(['æ', 'ø', 'å'], ['ae', 'o', 'a'], $slug);
        
        // Erstatt mellomrom og spesialtegn med bindestrek
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Fjern bindestreker i starten og slutten
        $slug = trim($slug, '-');
        
        return $slug;
    }
    
    /**
     * Sjekk om slug er ledig
     */
    public function isSlugAvailable(string $slug): bool
    {
        return !Tenant::where('slug', $slug)->exists();
    }
    
    /**
     * Foreslå alternative slugs hvis opptatt
     * Returnerer array med forslag (slug-1, slug-2, etc.)
     */
    public function suggestAlternatives(string $slug, int $count = 3): array
    {
        $suggestions = [];
        $counter = 1;
        
        while (count($suggestions) < $count) {
            $alternative = $slug . '-' . $counter;
            
            if ($this->isSlugAvailable($alternative)) {
                $suggestions[] = $alternative;
            }
            
            $counter++;
            
            // Sikkerhet: Ikke loop evig
            if ($counter > 100) {
                break;
            }
        }
        
        return $suggestions;
    }
}

// Service for slug-generering og validering
