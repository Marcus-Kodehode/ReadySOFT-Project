# AI Shaming Corner 🤦‍♂️

Dette er en samling av feil og oversikter som AI-assistenten har gjort, og som måtte fikses manuelt av mennesker med faktisk intelligens.

---

## Entry #1: Tidsreisende Migrations (2024-12-01)

**Dato oppdaget:** 2025-12-01  
**Alvorlighetsgrad:** 🟡 Lav (men pinlig)

**Hva skjedde:**
AI-assistenten opprettet alle database migrations med dato `2024_12_01_*` når vi faktisk er i 2025. Åpenbart har AI-en ikke fått med seg at vi har gått inn i et nytt år.

**Filer påvirket:**
- `database/migrations/2024_12_01_000001_create_tenants_table.php`
- `database/migrations/2024_12_01_000002_create_plans_table.php`
- `database/migrations/2024_12_01_000003_create_subscriptions_table.php`
- `database/migrations/2024_12_01_000004_create_resources_table.php`
- `database/migrations/2024_12_01_000005_create_resource_availabilities_table.php`
- `database/migrations/2024_12_01_000006_create_bookings_table.php`
- `database/migrations/2024_12_01_000007_add_tenant_fields_to_users_table.php`

**Hvordan det ble fikset:**
Manuelt endret alle filnavn fra `2024_12_01` til `2025_12_01`.

**Lærdom:**
AI-en trenger kanskje en kalender-app. Eller i det minste en reminder om at tiden går.

**AI's forsvar:**
"Men... system prompt sa at datoen var December 1, 2025... jeg bare... glemte å bruke den i filnavnene? 😅"

---

*Flere entries kommer sikkert snart...*

## Shame Entry #2: Unit vs Feature Tests for Factory Testing

**Dato:** 2025-12-02  
**AI Forslag:** "Lag factory test i `tests/Unit/` mappen"  
**Resultat:** ❌ Feilet - Ingen database connection  
**Menneske Løsning:** "Prøv `tests/Feature/` i stedet"  
**Resultat:** ✅ Fungerte perfekt  

**Hva gikk galt:**
AI foreslo å teste TenantFactory i Unit tests, men:
- Unit tests kjører **uten** database connection (isolert, rask)
- Feature tests kjører **med** full database (RefreshDatabase trait)
- Factories trenger database for å:
  - Faktisk opprette records
  - Sjekke unike constraints (f.eks. slug)
  - Teste relasjoner

**Hvorfor Feature test er riktig:**
```php
// tests/Feature/TenantFactoryTest.php
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantFactoryTest extends TestCase
{
    use RefreshDatabase; // ← Dette gir database tilgang!
    
    public function test_can_create_tenant()
    {
        $tenant = Tenant::factory()->create(); // Trenger database
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id]);
    }
}
```

**Når bruke Unit vs Feature:**

**Unit tests (`tests/Unit/`):**
- Tester isolert logikk (ingen database)
- Rene funksjoner, beregninger, validering
- Eksempel: `SlugService::generateSlug()` (kun string manipulation)

**Feature tests (`tests/Feature/`):**
- Tester med database, HTTP requests, full app
- Factories, models, controllers, routes
- Eksempel: Factory tests, booking flow, authentication

**Lærdommen:**
✅ Factory tests = Feature tests (trenger database)  
✅ Ren logikk = Unit tests (ingen database)  
❌ AI antok Unit test var riktig uten å tenke på database-behov  

**Mennesket vant denne runden! 🏆**

---

