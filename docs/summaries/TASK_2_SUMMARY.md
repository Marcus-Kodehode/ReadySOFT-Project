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

## Task 2.2: Opprett factory for testing ✅

**Status:** Fullført  
**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 1.4

### Hva ble gjort
Opprettet tre factories for å enkelt generere realistisk test-data for Tenant, Resource og Booking modellene. Factories gjør det mulig å raskt populere databasen med testdata under utvikling og testing.

#### 1. **TenantFactory** (`database/factories/TenantFactory.php`)

**Genererer:**
- `name` - Tilfeldig firmanavn
- `slug` - Automatisk generert fra navn, garantert unik
- `business_type` - Tilfeldig valgt fra: Cabin Rental, Hair Salon, Spa & Wellness, Room Rental, Other
- `description` - Valgfri beskrivelse (70% sjanse)
- `active` - Default true

**Spesielle funksjoner:**
- **Slug-generering:** Håndterer norske tegn (æ→ae, ø→o, å→a)
- **Unikhet:** Legger til suffix (-1, -2, etc.) hvis slug allerede eksisterer
- **State methods:**
  - `inactive()` - Lag inaktiv tenant
  - `businessType($type)` - Spesifiser business type

**Eksempel:**
```php
Tenant::factory()->create();  // Aktiv tenant
Tenant::factory()->inactive()->create();  // Inaktiv tenant
Tenant::factory()->businessType('Hair Salon')->create();
```

#### 2. **ResourceFactory** (`database/factories/ResourceFactory.php`)

**Genererer:**
- `tenant_id` - Automatisk opprettet tenant (eller spesifisert)
- `name` - Realistisk navn basert på type
- `description` - Valgfri beskrivelse (80% sjanse)
- `type` - Tilfeldig valgt fra: Cabin, Chair, Room, Treatment Room, Other
- `capacity` - Tilfeldig tall mellom 1-10
- `active` - Default true

**Intelligente navn:**
- Cabin: "Mountain Cabin", "Lake Cabin", "Forest Cabin", "Luxury Cabin"
- Chair: "Styling Chair", "Barber Chair", "Treatment Chair", "Massage Chair"
- Room: "Meeting Room", "Conference Room", "Private Room", "Studio Room"
- Treatment Room: "Massage Room", "Therapy Room", "Spa Room", "Wellness Room"

**State methods:**
- `inactive()` - Lag inaktiv ressurs
- `ofType($type)` - Spesifiser ressurstype
- `forTenant($tenant)` - Knytt til spesifikk tenant
- `withCapacity($capacity)` - Sett spesifikk kapasitet

**Eksempel:**
```php
Resource::factory()->create();  // Med auto-generert tenant
Resource::factory()->forTenant($tenant)->create();  // For spesifikk tenant
Resource::factory()->ofType('Cabin')->withCapacity(4)->create();
```

#### 3. **BookingFactory** (`database/factories/BookingFactory.php`)

**Genererer:**
- `resource_id` - Automatisk opprettet ressurs (eller spesifisert)
- `customer_name` - Tilfeldig navn
- `customer_email` - Tilfeldig sikker e-post
- `customer_phone` - Realistisk telefonnummer (norsk/internasjonalt)
- `booking_date` - Tilfeldig dato neste 30 dager
- `start_time` - Mellom 09:00-16:00 (hele eller halve timer)
- `end_time` - 1-2 timer etter start_time
- `notes` - Valgfrie notater (50% sjanse)
- `status` - Default 'confirmed'

**Telefonnummerformater:**
- Norsk: `+47 ### ## ###` eller `### ## ###`
- Internasjonalt: `+XX ### ### ####`

**State methods:**
- `past()` - Booking i fortiden (siste 30 dager)
- `pending()` - Status: pending
- `cancelled()` - Status: cancelled
- `forResource($resource)` - Knytt til spesifikk ressurs
- `onDate($date)` - Spesifiser dato
- `atTime($start, $end)` - Spesifiser tidspunkt

**Eksempel:**
```php
Booking::factory()->create();  // Fremtidig booking
Booking::factory()->past()->create();  // Tidligere booking
Booking::factory()->pending()->forResource($resource)->create();
Booking::factory()->onDate('2025-12-25')->atTime('10:00:00', '12:00:00')->create();
```

### Dokumentasjon
Alle filer har:
- ✅ **Header:** `// File: database/factories/FactoryName.php`
- ✅ **PHPDoc:** Detaljerte kommentarer på alle metoder
- ✅ **Footer:** Norsk forklaring av factory-funksjonalitet
- ✅ **Inline kommentarer:** Forklarer kompleks logikk

### Verifisering
```bash
php artisan tinker
>>> Tenant::factory()->create()  # ✅ Opprettet med unik slug
>>> Resource::factory()->count(5)->create()  # ✅ 5 ressurser opprettet
>>> Booking::factory()->count(10)->create()  # ✅ 10 bookinger opprettet
>>> Tenant::factory()->inactive()->create()  # ✅ Inaktiv tenant
>>> Booking::factory()->past()->cancelled()->create()  # ✅ Cancelled booking i fortiden
```

### Betydning
Med disse factories på plass kan vi nå:
- **Rask testing:** Generere testdata på sekunder
- **Realistiske data:** Navn, telefonnummer og tider ser ekte ut
- **Fleksibilitet:** State methods gir full kontroll over generert data
- **Seeding:** Bruke factories i seeders for demo-data
- **Feature tests:** Enkelt opprette testscenarier

### Bruk i testing
```php
// Feature test eksempel
public function test_tenant_can_view_own_resources()
{
    $tenant = Tenant::factory()->create();
    $resource = Resource::factory()->forTenant($tenant)->create();
    
    $this->actingAs($tenant->users->first())
         ->get('/dashboard/resources')
         ->assertSee($resource->name);
}
```

---

### Neste steg
- Fase 3: Multi-tenant Registrering
- Task 3.1: Utvid registreringsskjema med tenant-felter

---

**Tid brukt:** ~50 min (20 min + 30 min)  
**Sist oppdatert:** 2. desember 2025
