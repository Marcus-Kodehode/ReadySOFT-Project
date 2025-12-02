<?php

// File: tests/Feature/TenantFactoryTest.php

use App\Models\Tenant;

describe('TenantFactory', function () {
    
    test('genererer tenant med alle påkrevde felter', function () {
        $tenant = Tenant::factory()->create();
        
        expect($tenant->name)->toBeString()->not->toBeEmpty();
        expect($tenant->slug)->toBeString()->not->toBeEmpty();
        expect($tenant->business_type)->toBeString()->not->toBeEmpty();
        expect($tenant->active)->toBeTrue();
    });

    test('genererer unik slug fra navn', function () {
        $tenant = Tenant::factory()->create(['name' => 'Test Company']);
        
        expect($tenant->slug)->toBe('test-company');
    });

    test('håndterer duplikat navn med counter', function () {
        $tenant1 = Tenant::factory()->create(['name' => 'Test Company']);
        $tenant2 = Tenant::factory()->create(['name' => 'Test Company']);
        
        expect($tenant1->slug)->toBe('test-company');
        expect($tenant2->slug)->toBe('test-company-1');
    });

    test('konverterer norske tegn i slug', function () {
        $tenant = Tenant::factory()->create(['name' => 'Salong Røse & Blå']);
        
        expect($tenant->slug)->toBe('salong-rose-bla');
    });

    test('genererer flere tenants med unike slugs', function () {
        $tenants = Tenant::factory()->count(5)->create();
        
        $slugs = $tenants->pluck('slug')->toArray();
        $uniqueSlugs = array_unique($slugs);
        
        expect(count($slugs))->toBe(count($uniqueSlugs));
    });

    test('inactive() state setter active til false', function () {
        $tenant = Tenant::factory()->inactive()->create();
        
        expect($tenant->active)->toBeFalse();
    });

    test('businessType() setter spesifikk business type', function () {
        $tenant = Tenant::factory()->businessType('Hair Salon')->create();
        
        expect($tenant->business_type)->toBe('Hair Salon');
    });

    test('genererer gyldig business type fra predefinert liste', function () {
        $validTypes = [
            'Cabin Rental',
            'Hair Salon',
            'Spa & Wellness',
            'Room Rental',
            'Other',
        ];
        
        $tenant = Tenant::factory()->create();
        
        expect($tenant->business_type)->toBeIn($validTypes);
    });

    test('description er valgfri', function () {
        // Factory genererer description 70% av tiden
        $tenants = Tenant::factory()->count(20)->create();
        
        $withDescription = $tenants->filter(fn($t) => !is_null($t->description))->count();
        $withoutDescription = $tenants->filter(fn($t) => is_null($t->description))->count();
        
        // Verifiser at noen har description og noen ikke har
        expect($withDescription)->toBeGreaterThan(0);
        expect($withoutDescription)->toBeGreaterThan(0);
    });

    test('slug er alltid lowercase', function () {
        $tenant = Tenant::factory()->create(['name' => 'UPPERCASE COMPANY']);
        
        expect($tenant->slug)->toBe(strtolower($tenant->slug));
    });

    test('slug håndterer spesialtegn', function () {
        $tenant = Tenant::factory()->create(['name' => 'Company & Co. (2024)']);
        
        // Slug skal kun inneholde lowercase, tall og bindestrek
        expect($tenant->slug)->toMatch('/^[a-z0-9-]+$/');
    });

});

// TenantFactory test suite dokumenterer og verifiserer at factory
// genererer gyldige test-data for Tenant modellen.
// Tester dekker: slug-generering, norske tegn, duplikater, og state modifiers.
