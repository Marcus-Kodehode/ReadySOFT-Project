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


## Shame Entry #3: Tailwind CSS 4 - Glemte å oppdatere Vite config

**Dato:** 2025-12-02  
**AI Forslag:** "Oppdater til Tailwind v4 med `@import 'tailwindcss'`"  
**Resultat:** ❌ Delvis - CSS fungerte ikke i nettleseren  
**Menneske Oppdagelse:** "Vite config mangler Tailwind v4 plugin!"  
**Resultat:** ✅ Fungerte perfekt etter full oppdatering  

**Hva gikk galt:**
AI oppdaterte `resources/css/app.css` til Tailwind v4 syntaks:
```css
@import "tailwindcss"; // ✅ Riktig
```

Men glemte å oppdatere `vite.config.js` til å bruke Tailwind v4 Vite plugin:
```js
// ❌ Manglet dette:
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({...}),
        tailwindcss(), // ← Dette manglet!
    ],
});
```

**Resultat:**
- CSS kompilerte uten feil
- Men Tailwind-klasser fungerte ikke i nettleseren
- Ingen synlige feilmeldinger (stille feil)

**Riktig Tailwind v4 oppsett:**

**1. `resources/css/app.css`:**
```css
@import "tailwindcss";
```

**2. `vite.config.js`:**
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**3. Kjør:**
```bash
npm run build  # Eller npm run dev
```

**Viktige forskjeller Tailwind v3 vs v4:**

| Tailwind v3 | Tailwind v4 |
|-------------|-------------|
| `@tailwind base;` | `@import "tailwindcss";` |
| `@tailwind components;` | (ikke nødvendig) |
| `@tailwind utilities;` | (ikke nødvendig) |
| `tailwind.config.js` påkrevd | Valgfri (defaults er gode) |
| PostCSS plugin | Vite plugin |
| `postcss.config.js` | Ikke nødvendig |

**Lærdommen:**
✅ Tailwind v4 krever BÅDE CSS-endring OG Vite plugin  
✅ Sjekk alltid at CSS faktisk fungerer i nettleseren  
✅ Stille feil er verst - ingen feilmelding, men ingenting fungerer  
❌ AI gjorde halve jobben og antok resten var OK  

**Mennesket reddet dagen igjen! 🏆**

**Bonus - Hvordan verifisere at Tailwind fungerer:**
1. Åpne nettleseren
2. Inspiser et element med Tailwind-klasser (f.eks. `bg-blue-600`)
3. Sjekk at CSS-en faktisk er applisert
4. Hvis ikke: Sjekk Vite config og rebuild

---


## Shame Entry #4: Modal Z-Index Layering Problem

**Dato:** 2025-12-02  
**AI Forslag:** "Her er en modal-komponent med backdrop"  
**Resultat:** ❌ Modal-innhold ble grått/transparent sammen med backdrop  
**Menneske Observasjon:** "Modal-boksen blir også grå, ikke bare bakgrunnen, kan det være problemer med z-index?"  
**AI Løsning:** "Ah! Mangler `relative z-10` på modal-innholdet"  
**Resultat:** ✅ Fungerte perfekt etter z-index fix  

**Hva gikk galt:**
AI laget en modal med backdrop, men glemte å sette riktig z-index på modal-innholdet:

```blade
<!-- ❌ Opprinnelig (feil) -->
<div x-show="open" class="fixed inset-0 flex items-center justify-center">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal content - MANGLER z-index! -->
    <div class="w-full max-w-md p-6 bg-white rounded-lg">
        Content here
    </div>
</div>
```

**Resultat:**
- Backdrop ble grå (korrekt)
- Men modal-innholdet ble OGSÅ grått/transparent
- Modal-innholdet lå ikke "over" backdrop
- Ubrukelig modal

**Riktig løsning:**

```blade
<!-- ✅ Riktig (med z-index) -->
<div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div @click="open = false" 
         class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal content - MED relative z-10 -->
    <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
        Content here
    </div>
</div>
```

**Hvorfor `relative z-10` er nødvendig:**

1. **`relative`** - Etablerer en ny stacking context
2. **`z-10`** - Plasserer modal-innholdet OVER backdrop
3. **Uten dette** - Modal-innholdet ligger på samme nivå som backdrop

**Z-index hierarki i modals:**

```
z-50  → Hele modal-containeren (over alt annet på siden)
  ├─ z-0  → Backdrop (grå bakgrunn)
  └─ z-10 → Modal-innhold (hvit boks) ← MÅ være høyere enn backdrop!
```

**Lærdommen:**
✅ Modals trenger alltid riktig z-index layering  
✅ Modal-innhold må ha høyere z-index enn backdrop  
✅ `relative z-10` på modal-innhold er standard  
✅ Test alltid at modal-innholdet er klikkbart og synlig  
❌ AI glemte å tenke på stacking context  

**Bonus - Standard modal-struktur:**
```blade
<div x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Backdrop (z-0 implicit) -->
    <div @click="open = false" 
         class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal content (z-10 explicit) -->
    <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
        <h3 class="text-lg font-semibold">Title</h3>
        <p class="mt-2">Content</p>
        <div class="flex justify-end gap-3 mt-4">
            <button @click="open = false">Cancel</button>
            <button>Confirm</button>
        </div>
    </div>
</div>
```

**Mennesket oppdaget problemet visuelt! 🏆**

---

