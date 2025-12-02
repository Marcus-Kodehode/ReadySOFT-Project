# Task 2 - Seed Data og Testing

## Oversikt
Fase 2 etablerer seed data og testing-infrastruktur for systemet. Dette gjør det mulig å raskt populere databasen med testdata og verifisere funksjonalitet.

---

## Task 2.1: Database seeder for plans ✅

**Status:** Fullført  
**Prioritet:** Høy  
**Estimat:** 20 min  
**Avhengigheter:** Task 1.4

### Hva ble gjort
Opprettet en database seeder som automatisk oppretter en "Basic Plan" som alle nye tenants får tildelt ved registrering.

#### **PlanSeeder** (`database/seeders/PlanSeeder.php`)

**Funksjonalitet:**
- Oppretter "Basic Plan" med beskrivelse og features
- Bruker `firstOrCreate()` for idempotens (kan kjøres flere ganger uten duplikater)
- Features definert som JSON: `{"max_resources": 10}`
- Klar til bruk i registreringsprosessen

**Tekniske detaljer:**
```php
Plan::firstOrCreate(
    ['name' => 'Basic Plan'],  // Søkekriterium
    [
        'description' => 'Basic plan with essential features for small businesses',
        'features' => [
            'max_resources' => 10,
        ],
    ]
);
```

**Idempotens:**
- Første kjøring: Oppretter planen
- Påfølgende kjøringer: Finner eksisterende plan, oppretter ikke duplikat
- Trygt å kjøre i produksjon og utvikling

### Dokumentasjon
Filen har:
- ✅ **Header:** `// File: database/seeders/PlanSeeder.php`
- ✅ **PHPDoc:** Detaljert beskrivelse på norsk
- ✅ **Footer:** `// PlanSeeder oppretter standard abonnementsplaner i systemet`
- ✅ **Inline kommentarer:** Forklarer idempotens-logikken

### Verifisering
```bash
php artisan db:seed --class=PlanSeeder  # ✅ Kjørte uten feil
php artisan tinker
>>> App\Models\Plan::count()  # ✅ Returnerer 1
>>> App\Models\Plan::first()->name  # ✅ "Basic Plan"
>>> App\Models\Plan::first()->features  # ✅ ["max_resources" => 10]
```

### Betydning
Med denne seederen på plass kan vi nå:
- Automatisk tildele nye tenants en Basic Plan ved registrering
- Kjøre seederen i både utvikling og produksjon uten bekymring for duplikater
- Enkelt utvide med flere planer i fremtiden (Premium, Enterprise, etc.)
- Teste subscription-funksjonalitet med reelle data

### Bruk i registreringsprosessen
```php
// I RegisteredUserController.php (Task 3.5)
$subscription = Subscription::create([
    'tenant_id' => $tenant->id,
    'plan_id' => 1,  // Basic Plan fra PlanSeeder
    'active' => true,
]);
```

---

### Neste steg
- Task 2.2: Opprett factory for testing (TenantFactory, ResourceFactory, BookingFactory)
- Fase 3: Multi-tenant Registrering

---

**Tid brukt:** ~20 min  
**Sist oppdatert:** 2. desember 2025
