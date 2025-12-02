# Task 3 - Multi-tenant Registrering

## Oversikt
Fase 3 utvider registreringsprosessen til å støtte multi-tenant funksjonalitet. Når en ny kunde registrerer seg, opprettes både en bruker-konto og en tilhørende tenant med unik slug for deres bookingside.

---

## Task 3.1: Utvid registreringsskjema med tenant-felter ✅

**Status:** Fullført  
**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.4, 2.1

### Hva ble gjort
Utvidet Laravel Breeze sitt standard registreringsskjema med multi-tenant funksjonalitet. Skjemaet inkluderer nå business-informasjon og automatisk slug-generering med sanntids validering.

#### **Nye felter i registreringsskjemaet**

**1. Business Name**
- Input-felt for bedriftsnavn
- Trigger for automatisk slug-generering
- Påkrevd felt med autocomplete="organization"

**2. Business Type (Dropdown)**
- Cabin Rental
- Hair Salon
- Spa & Wellness
- Room Rental
- Other
- Husker valgt verdi ved validering-feil (old() helper)

**3. Slug (URL Preview)**
- Visuell preview av booking-URL: `https://domain.com/{slug}`
- Automatisk generert fra business_name
- Kan redigeres manuelt av bruker
- Sanntids validering med visuell feedback

#### **Alpine.js Funksjonalitet**

**Slug-generering:**
```javascript
generateSlug() {
    // 1. Konverter til lowercase
    // 2. Erstatt norske tegn (æ→ae, ø→o, å→a)
    // 3. Erstatt mellomrom og spesialtegn med bindestrek
    // 4. Fjern bindestreker i start/slutt
}
```

**Sanntids validering:**
- Debounced API call (500ms) til `/api/check-slug`
- Visuell feedback:
  - 🔄 Spinner mens sjekker
  - ✅ Grønn checkmark hvis ledig
  - ❌ Rød X hvis opptatt
- Border-farge endres basert på status
- Viser forslag hvis slug er opptatt

**Slug-forslag:**
- Hvis slug er opptatt, vises alternative forslag
- Klikk på forslag for å bruke det
- Forslag genereres av backend (slug-1, slug-2, etc.)

### Tekniske detaljer

**Alpine.js State:**
```javascript
{
    businessName: '',      // Synkronisert med input
    slug: '',              // Generert eller manuelt redigert
    slugAvailable: null,   // true/false/null
    checking: false,       // Loading state
    suggestions: [],       // Alternative slugs
    checkTimeout: null     // For debouncing
}
```

**Visuell feedback:**
- **Ledig slug:** Grønn border + checkmark + "This URL is available!"
- **Opptatt slug:** Rød border + X + "This URL is already taken" + forslag
- **Sjekker:** Grå border + spinner
- **Ikke sjekket:** Grå border + hjelpetekst

**Responsivt design:**
- Følger Tailwind CSS design guide
- Konsistent med Breeze sitt eksisterende design
- Touch-vennlig på mobil
- Tydelige focus states

### Brukeropplevelse

**Flyt:**
1. Bruker fyller inn navn, email, passord (standard Breeze)
2. Bruker skriver inn business name
3. Slug genereres automatisk i sanntid
4. Slug valideres automatisk (debounced)
5. Visuell feedback viser om slug er ledig
6. Hvis opptatt: Forslag vises som klikkbare chips
7. Bruker kan redigere slug manuelt hvis ønsket
8. Bruker velger business type fra dropdown
9. Submit registrering

**Validering:**
- Client-side: Alpine.js sjekker slug-tilgjengelighet
- Server-side: Laravel validerer alle felter (Task 3.5)
- Inline feilmeldinger ved validering-feil
- Old values bevares ved feil

### Integrasjon med backend

**API Endpoint (Task 3.3):**
```
GET /api/check-slug?slug={slug}

Response:
{
    "available": true/false,
    "suggestions": ["slug-1", "slug-2", "slug-3"]
}
```

**Form submission:**
```php
POST /register
{
    name: "John Doe",
    email: "john@example.com",
    password: "password123",
    password_confirmation: "password123",
    business_name: "Salong Rosa",
    business_type: "Hair Salon",
    slug: "salong-rosa"
}
```

### Dokumentasjon
Filen har:
- ✅ **Inline kommentarer:** Forklarer Alpine.js logikk
- ✅ **Tydelige class names:** Følger Tailwind conventions
- ✅ **Accessibility:** Labels på alle inputs
- ✅ **Error handling:** Viser Laravel validation errors

### Verifisering
```bash
# Manuell testing
1. Gå til /register
2. Fyll inn "Salong Rosa" i Business Name
3. Se at slug blir "salong-rosa" automatisk
4. Se grønn checkmark hvis ledig
5. Prøv å endre slug manuelt
6. Se at validering kjører på nytt
7. Test med norske tegn (æ, ø, å)
8. Test med spesialtegn og mellomrom
```

### Betydning
Med dette skjemaet på plass kan vi nå:
- **Samle tenant-info:** Business name, type og slug ved registrering
- **Validere slugs:** Sikre at hver tenant får unik URL
- **Forbedre UX:** Sanntids feedback gir trygghet til bruker
- **Forhindre feil:** Slug-konflikter oppdages før submit
- **Automatisere:** Bruker slipper å tenke på URL-format

---

## Task 3.2: Opprett SlugService for slug-generering ✅

**Status:** Fullført  
**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.1

### Hva ble gjort
Opprettet en dedikert service class som håndterer all slug-relatert logikk. Dette følger Single Responsibility Principle og gjør koden gjenbrukbar og testbar.

#### **SlugService** (`app/Services/SlugService.php`)

**Metoder:**

**1. generateSlug($name)**
- Konverterer navn til gyldig slug-format
- Lowercase transformasjon
- Håndterer norske tegn: æ→ae, ø→o, å→a
- Erstatter mellomrom og spesialtegn med bindestrek
- Fjerner bindestreker i start/slutt
- Bruker `mb_strtolower()` for multibyte support

**Eksempel:**
```php
$service->generateSlug('Salong Rosa');
// Returnerer: "salong-rosa"

$service->generateSlug('Bjørns Hytteutleie');
// Returnerer: "bjorns-hytteutleie"

$service->generateSlug('Spa & Wellness Senter!!!');
// Returnerer: "spa-wellness-senter"
```

**2. isSlugAvailable($slug)**
- Sjekker om slug allerede eksisterer i database
- Returnerer `true` hvis ledig, `false` hvis opptatt
- Enkel database-query mot `tenants` tabell

**Eksempel:**
```php
$service->isSlugAvailable('salong-rosa');
// Returnerer: true/false
```

**3. suggestAlternatives($slug, $count = 3)**
- Genererer alternative slugs hvis opptatt
- Legger til suffix: slug-1, slug-2, slug-3, etc.
- Sjekker tilgjengelighet for hver alternativ
- Returnerer array med ledige forslag
- Sikkerhet: Stopper etter 100 iterasjoner

**Eksempel:**
```php
$service->suggestAlternatives('salong-rosa', 3);
// Returnerer: ["salong-rosa-1", "salong-rosa-2", "salong-rosa-3"]
```

### Tekniske detaljer

**Regex pattern:**
```php
preg_replace('/[^a-z0-9]+/', '-', $slug);
// Matcher alt som IKKE er a-z eller 0-9
// Erstatter med bindestrek
```

**Multibyte support:**
- Bruker `mb_strtolower()` i stedet for `strtolower()`
- Håndterer UTF-8 tegn korrekt
- Viktig for norske tegn

**Performance:**
- Enkle string-operasjoner (rask)
- Database-query kun når nødvendig
- Begrenset antall iterasjoner i suggestAlternatives

### Dokumentasjon
Filen har:
- ✅ **Header:** `// File: app/Services/SlugService.php`
- ✅ **PHPDoc:** Detaljerte kommentarer på alle metoder
- ✅ **Footer:** `// Service for slug-generering og validering`
- ✅ **Inline kommentarer:** Forklarer hver steg på norsk

### Verifisering
```bash
php artisan tinker
>>> $service = new App\Services\SlugService();
>>> $service->generateSlug('Salong Rosa')
=> "salong-rosa"
>>> $service->generateSlug('Bjørns Hytteutleie')
=> "bjorns-hytteutleie"
>>> $service->isSlugAvailable('test-salon')
=> true/false
>>> $service->suggestAlternatives('test-salon')
=> ["test-salon-1", "test-salon-2", "test-salon-3"]
```

### Betydning
Med denne service-klassen kan vi nå:
- **Gjenbruke logikk:** Samme slug-generering i frontend og backend
- **Teste enkelt:** Isolert logikk er lett å teste
- **Konsistens:** Garanterer samme slug-format overalt
- **Vedlikehold:** Endringer i slug-logikk gjøres ett sted

---

## Task 3.3: Opprett API endpoint for slug-validering ✅

**Status:** Fullført  
**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.2

### Hva ble gjort
Opprettet en API endpoint som validerer slugs i sanntid. Dette gjør det mulig for registreringsskjemaet å gi umiddelbar feedback til brukeren.

#### **SlugController** (`app/Http/Controllers/Api/SlugController.php`)

**Endpoint:**
```
GET /api/check-slug?slug={slug}
```

**Funksjonalitet:**
- Mottar slug som query parameter
- Validerer at slug er oppgitt
- Sjekker tilgjengelighet via SlugService
- Genererer forslag hvis opptatt
- Returnerer JSON response

**Response format:**
```json
// Hvis ledig
{
    "available": true,
    "suggestions": []
}

// Hvis opptatt
{
    "available": false,
    "suggestions": ["salong-rosa-1", "salong-rosa-2", "salong-rosa-3"]
}

// Hvis feil
{
    "available": false,
    "suggestions": [],
    "error": "Slug is required"
}
```

### Tekniske detaljer

**Rate Limiting:**
- Middleware: `throttle:10,1`
- Maksimalt 10 requests per minutt
- Forhindrer spam og misbruk
- Beskytter database mot overbelastning

**Dependency Injection:**
```php
public function __construct(SlugService $slugService)
{
    $this->slugService = $slugService;
}
```
- SlugService injiseres automatisk av Laravel
- Gjør controller testbar
- Følger SOLID principles

**Error Handling:**
- Validerer at slug parameter er oppgitt
- Returnerer 400 Bad Request hvis mangler
- Tydelig feilmelding i response

**HTTP Status Codes:**
- 200 OK: Vellykket sjekk
- 400 Bad Request: Manglende parameter

### Route Registration

**I `routes/web.php`:**
```php
Route::get('/api/check-slug', [SlugController::class, 'check'])
    ->name('api.check-slug');
```

**Hvorfor web.php og ikke api.php?**
- Ingen autentisering påkrevd (offentlig endpoint)
- Brukes av registreringsskjema (før login)
- Enklere CSRF-håndtering
- Rate limiting fungerer ut av boksen

### Dokumentasjon
Filen har:
- ✅ **Header:** `// File: app/Http/Controllers/Api/SlugController.php`
- ✅ **PHPDoc:** Detaljerte kommentarer på metoder
- ✅ **Footer:** `// API controller for slug-validering`
- ✅ **Inline kommentarer:** Forklarer rate limiting og validering

### Verifisering
```bash
# Test med curl
curl "http://localhost:8000/api/check-slug?slug=test-salon"
# Response: {"available":true,"suggestions":[]}

curl "http://localhost:8000/api/check-slug?slug=existing-slug"
# Response: {"available":false,"suggestions":["existing-slug-1","existing-slug-2","existing-slug-3"]}

# Test rate limiting (kjør 11 ganger raskt)
for i in {1..11}; do curl "http://localhost:8000/api/check-slug?slug=test-$i"; done
# 11. request: 429 Too Many Requests
```

### Betydning
Med denne API-en kan vi nå:
- **Sanntids validering:** Bruker får umiddelbar feedback
- **Bedre UX:** Ingen overraskelser ved submit
- **Forhindre konflikter:** Slug-duplikater oppdages tidlig
- **Forslag:** Hjelper bruker finne ledig alternativ
- **Sikkerhet:** Rate limiting beskytter mot misbruk

---

## Task 3.4: Legg til Alpine.js for slug live preview ✅

**Status:** Fullført  
**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.1, 3.3

### Hva ble gjort
Implementerte Alpine.js logikk for å gi bruker sanntids feedback på slug-tilgjengelighet. Dette ble allerede gjort som en del av Task 3.1, men her er en detaljert oversikt over funksjonaliteten.

#### **Alpine.js State Management**

**x-data objekt:**
```javascript
{
    businessName: '',      // Synkronisert med business_name input
    slug: '',              // Generert eller manuelt redigert slug
    slugAvailable: null,   // true (ledig), false (opptatt), null (ikke sjekket)
    checking: false,       // true når API call pågår
    suggestions: [],       // Array med alternative slugs
    checkTimeout: null     // Timeout ID for debouncing
}
```

#### **Metoder**

**1. generateSlug()**
- Trigger: `@input` på business_name felt
- Konverterer business_name til slug
- Samme logikk som backend SlugService
- Kaller `checkSlugAvailability()` automatisk

**2. checkSlugAvailability()**
- Debounced API call (500ms delay)
- Forhindrer spam av API requests
- Setter `checking = true` under API call
- Oppdaterer `slugAvailable` og `suggestions`
- Error handling med try/catch

**3. useSlug(suggestion)**
- Trigger: Klikk på forslag-chip
- Setter slug til valgt forslag
- Kjører validering på nytt

#### **Visuell Feedback**

**Border colors (dynamisk):**
```javascript
:class="{
    'border-green-300 focus:ring-green-500': slugAvailable === true,
    'border-red-300 focus:ring-red-500': slugAvailable === false,
    'border-gray-300 focus:ring-blue-500': slugAvailable === null
}"
```

**Status ikoner:**
- **Checking:** Spinner (animert)
- **Available:** Grønn checkmark
- **Not available:** Rød X

**Meldinger:**
- **Ledig:** "This URL is available!" (grønn)
- **Opptatt:** "This URL is already taken" (rød)
- **Forslag:** Klikkbare chips med alternativer
- **Hjelpetekst:** "Auto-generated from business name, but you can edit it manually"

#### **Debouncing**

**Hvorfor debouncing?**
- Forhindrer API call ved hver tastetrykk
- Venter 500ms etter siste endring
- Reduserer server-belastning
- Bedre brukeropplevelse (mindre flimring)

**Implementering:**
```javascript
if (this.checkTimeout) {
    clearTimeout(this.checkTimeout);
}

this.checkTimeout = setTimeout(async () => {
    // API call her
}, 500);
```

#### **Forslag-chips**

**Design:**
- Blå bakgrunn (`bg-blue-50`)
- Blå border (`border-blue-200`)
- Hover effekt (`hover:bg-blue-100`)
- Focus ring for accessibility
- Smooth transitions

**Funksjonalitet:**
- Vises kun når slug er opptatt
- Maksimalt 3 forslag (fra backend)
- Klikk setter slug og validerer på nytt
- Type="button" for å forhindre form submit

### Tekniske detaljer

**Fetch API:**
```javascript
const response = await fetch(`/api/check-slug?slug=${encodeURIComponent(this.slug)}`);
const data = await response.json();
```
- Bruker native Fetch API (ingen jQuery)
- `encodeURIComponent()` for sikker URL-encoding
- Async/await for lesbar kode
- Try/catch for error handling

**x-show directives:**
- `x-show="checking"` - Viser spinner
- `x-show="!checking && slugAvailable === true"` - Viser checkmark
- `x-show="!checking && slugAvailable === false"` - Viser X
- `x-show="suggestions.length > 0"` - Viser forslag

**x-init:**
```javascript
x-init="if (businessName) generateSlug()"
```
- Kjører ved page load
- Genererer slug hvis old('business_name') finnes
- Viktig for validation errors (bevarer state)

### Verifisering
```bash
# Manuell testing
1. Gå til /register
2. Skriv "Salong Rosa" i Business Name
3. Observer:
   - Slug blir "salong-rosa" automatisk
   - Spinner vises i 500ms
   - Grønn checkmark vises hvis ledig
4. Endre slug til eksisterende slug
5. Observer:
   - Rød X vises
   - Forslag vises som chips
6. Klikk på forslag
7. Observer:
   - Slug oppdateres
   - Validering kjører på nytt
```

### Betydning
Med Alpine.js implementeringen får vi:
- **Sanntids feedback:** Bruker ser umiddelbart om slug er ledig
- **Ingen page reload:** Alt skjer client-side
- **Minimal JavaScript:** Alpine.js er lett (15kb)
- **Deklarativ kode:** HTML-basert, lett å lese
- **Accessibility:** Keyboard navigation fungerer
- **Progressive enhancement:** Fungerer uten JavaScript (server-side validation)

---

### Neste steg
- Task 3.5: Modifiser RegisteredUserController for tenant-opprettelse

---

**Tid brukt:** ~240 minutter 
**Sist oppdatert:** 2. desember 2025
