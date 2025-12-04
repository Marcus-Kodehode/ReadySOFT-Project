# Tasks - ReadySoft Booking Portal

## Oversikt

Denne task-listen følger en praktisk rekkefølge som gir deg noe testbart tidlig samtidig som den følger beste praksis. Tasks er organisert i faser hvor hver fase bygger på forrige.

**Prinsipper:**
- Testbart tidlig (se resultater raskt)
- Inkrementell utvikling (små, fungerende steg)
- Database først, deretter logikk, til slutt UI
- Hver task skal være fullførbar på 30-90 minutter

**Fil-konvensjoner:**
- Header: `// File: path/to/file.php`
- Footer: `// Beskrivelse av hva filen gjør`
- Kommentarer: Norsk
- Brukersynlig tekst: Engelsk

---
(START HERE)
## FASE 1: Database Foundation (Dag 1)

### Task 1.1: Opprett database-migrasjoner for core tabeller

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Ingen

**Beskrivelse:**  
Lag migrasjoner for tenants, plans, subscriptions tabeller som er grunnlaget for multi-tenancy.

**Filer som opprettes:**
- `database/migrations/YYYY_MM_DD_000001_create_tenants_table.php`
- `database/migrations/YYYY_MM_DD_000002_create_plans_table.php`
- `database/migrations/YYYY_MM_DD_000003_create_subscriptions_table.php`

**Akseptansekriterier:**
- [x] Tenants tabell har: id, name, slug (unique), business_type, description, active, timestamps





- [x] Plans tabell har: id, name, description, features (json), timestamps





- [x] Subscriptions tabell har: id, tenant_id (FK), plan_id (FK), active, active_from, active_to, timestamps




- [x] Indexes på: tenants.slug, tenants.active, subscriptions.tenant_id




- [x] Foreign keys med cascade on delete









- [x] `php artisan migrate` kjører uten feil














**Testing:**
```bash
php artisan migrate
# Sjekk i MySQL Workbench at tabellene er opprettet
```

---

### Task 1.2: Opprett database-migrasjoner for booking-tabeller

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.1

**Beskrivelse:**  
Lag migrasjoner for resources, resource_availabilities, bookings tabeller.

**Filer som opprettes:**
- `database/migrations/YYYY_MM_DD_000004_create_resources_table.php`
- `database/migrations/YYYY_MM_DD_000005_create_resource_availabilities_table.php`
- `database/migrations/YYYY_MM_DD_000006_create_bookings_table.php`

**Akseptansekriterier:**
- [x] Resources tabell har: id, tenant_id (FK), name, description, type, capacity, active, timestamps





- [x] Resource_availabilities tabell har: id, resource_id (FK), day_of_week, start_time, end_time, timestamps




- [x] Bookings tabell har: id, resource_id (FK), customer_name, customer_email, customer_phone, booking_date, start_time, end_time, notes, status, timestamps





- [x] Indexes på: resources.tenant_id, bookings.resource_id, bookings.booking_date





- [x] Foreign keys med cascade on delete
- [x] `php artisan migrate` kjører uten feil

---

### Task 1.3: Utvid users tabell med tenant_id og role

**Prioritet:** Kritisk  
**Estimat:** 20 min  
**Avhengigheter:** Task 1.1

**Beskrivelse:**  
Lag migration for å legge til tenant_id og role kolonner i users tabell.

**Filer som opprettes:**
- `database/migrations/YYYY_MM_DD_000007_add_tenant_fields_to_users_table.php`

**Akseptansekriterier:**
- [x] Legger til: tenant_id (nullable, FK), role (enum: 'admin', 'tenant_admin', default 'tenant_admin')






- [x] Index på tenant_id





- [x] Foreign key til tenants.id





- [x] `php artisan migrate` kjører uten feil

---

### Task 1.4: Opprett Eloquent modeller med relasjoner

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 1.1, 1.2, 1.3

**Beskrivelse:**  
Lag Eloquent modeller for alle tabeller med korrekte relasjoner og fillable fields.

**Filer som opprettes:**
- `app/Models/Tenant.php`
- `app/Models/Plan.php`
- `app/Models/Subscription.php`
- `app/Models/Resource.php`
- `app/Models/ResourceAvailability.php`
- `app/Models/Booking.php`

**Akseptansekriterier:**
- [x] Tenant model: hasMany(subscriptions, resources, users)





- [x] Plan model: hasMany(subscriptions)





- [x] Subscription model: belongsTo(tenant, plan)








- [x] Resource model: belongsTo(tenant), hasMany(availabilities, bookings)





- [x] ResourceAvailability model: belongsTo(resource)




- [x] Booking model: belongsTo(resource)





- [x] User model: belongsTo(tenant)





- [x] Fillable fields definert på alle modeller





- [x] Casts definert (active → boolean, features → array, etc.)





- [x] Fil-header og footer på alle filer





**Testing:**
```bash
php artisan tinker
>>> App\Models\Tenant::count()
>>> App\Models\Plan::count()
```

✅ TEST VELYKKET

---


## FASE 2: Seed Data og Testing (Dag 1-2)

### Task 2.1: Opprett database seeder for plans

**Prioritet:** Høy  
**Estimat:** 20 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag seeder som oppretter en "Basic" plan som alle nye tenants får automatisk.

**Filer som opprettes:**
- `database/seeders/PlanSeeder.php`

**Akseptansekriterier:**
- [x] Oppretter minimum én plan: "Basic Plan"





- [x] Features kan være tom eller enkel JSON: {"max_resources": 10}





- [x] Seeder er idempotent (kan kjøres flere ganger)




- [ ] Fil-header og footer














**Testing:**
```bash
php artisan db:seed --class=PlanSeeder
# Sjekk i Workbench at plan er opprettet 
```

✅ TEST VELYKKET

---

### Task 2.2: Opprett factory for testing

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag factories for å kunne generere test-data enkelt.

**Filer som opprettes:**
- `database/factories/TenantFactory.php`
- `database/factories/ResourceFactory.php`
- `database/factories/BookingFactory.php`

**Akseptansekriterier:**
- [X] TenantFactory genererer: name, slug (unique), business_type, active



- [x] ResourceFactory genererer: name, description, type, capacity



- [x] BookingFactory genererer: customer_name, customer_email, customer_phone, booking_date, times



- [x] Fil-header og footer

**Testing:**
```bash
php artisan tinker
>>> App\Models\Tenant::factory()->create()
```

✅ TEST VELYKKET

---

## FASE 3: Multi-tenant Registrering (Dag 2)

### Task 3.1: Utvid registreringsskjema med tenant-felter

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.4, 2.1

**Beskrivelse:**  
Modifiser Breeze sitt registreringsskjema til å inkludere business_name, business_type og slug.

**Filer som endres:**
- `resources/views/auth/register.blade.php`

**Akseptansekriterier:**
- [x] Skjema inneholder: name, email, password, password_confirmation, business_name, business_type (dropdown)





- [x] Business type dropdown har minimum: "Cabin Rental", "Hair Salon", "Spa & Wellness", "Room Rental", "Other"




- [x] Slug genereres automatisk fra business_name (live preview)





- [x] Slug kan redigeres manuelt




- [x] Slug valideres i sanntid (visuell feedback hvis opptatt)





- [x] Følger design guide (Tailwind classes)





- [x] Brukersynlig tekst på engelsk





- [x] Fil-header og footer

---

### Task 3.2: Opprett SlugService for slug-generering

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.1

**Beskrivelse:**  
Lag en service class som håndterer slug-generering og validering.

**Filer som opprettes:**
- `app/Services/SlugService.php`

**Akseptansekriterier:**
- [x] Metode: generateSlug($name) - konverterer til lowercase, erstatter mellomrom med bindestrek
- [x] Metode: isSlugAvailable($slug) - sjekker om slug er ledig
- [x] Metode: suggestAlternatives($slug) - foreslår alternativer hvis opptatt (slug-1, slug-2, etc.)
- [x] Håndterer spesialtegn (æ, ø, å → ae, o, a)
- [x] Fil-header og footer med norske kommentarer

**Testing:**
```bash
php artisan tinker
>>> $service = new App\Services\SlugService();
>>> $service->generateSlug('Salong Rosa')
=> "salong-rosa"
```

✅ TEST VELYKKET

---

### Task 3.3: Opprett API endpoint for slug-validering

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.2

**Beskrivelse:**  
Lag en API route som validerer slug i sanntid (for live feedback i registreringsskjema).

**Filer som opprettes:**
- `app/Http/Controllers/Api/SlugController.php`

**Filer som endres:**
- `routes/web.php` (eller `routes/api.php`)

**Akseptansekriterier:**
- [x] Route: GET /api/check-slug?slug={slug}
- [x] Returnerer JSON: {"available": true/false, "suggestions": [...]}
- [x] Rate limiting: Max 10 requests per minutt
- [x] Fil-header og footer

**Testing:**
```bash
curl "http://localhost:8000/api/check-slug?slug=test-salon" #RETURN TO THIS!
```

---

### Task 3.4: Legg til Alpine.js for slug live preview

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 3.1, 3.3

**Beskrivelse:**  
Implementer Alpine.js logikk for å vise slug preview og validering i sanntid.

**Filer som endres:**
- `resources/views/auth/register.blade.php`

**Akseptansekriterier:**
- [x] x-data holder: businessName, slug, slugAvailable, checking
- [x] Watch på businessName genererer slug automatisk
- [x] Debounced API call til /api/check-slug
- [x] Visuell feedback: Grønn checkmark hvis ledig, rød X hvis opptatt
- [x] Viser forslag hvis opptatt
- [x] Bruker kan overstyre auto-generert slug

---

### Task 3.5: Modifiser RegisteredUserController for tenant-opprettelse

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 3.1, 3.2, 2.1

**Beskrivelse:**  
Utvid Breeze sin RegisteredUserController til å opprette Tenant og Subscription samtidig med User.

**Filer som endres:**
- `app/Http/Controllers/Auth/RegisteredUserController.php`

**Akseptansekriterier:**
- [x] Validering: business_name (required, 3-255), business_type (required), slug (required, unique)




- [x] Database transaksjon: Opprett Tenant → Opprett User med tenant_id → Opprett Subscription





- [x] Subscription settes til active=true, plan_id=1 (Basic plan)
- [x] Hvis noe feiler: Rollback alt
- [x] Redirect til /dashboard etter suksess
- [x] Flash message: "Welcome! Let's get started"
- [x] Fil-header og footer med norske kommentarer

**Testing:**
- Registrer ny bruker via /register
- Sjekk at tenant, user og subscription opprettes
- Sjekk at du redirectes til dashboard

---

## FASE 4: Middleware og Tilgangskontroll (Dag 2-3)

### Task 4.1: Opprett CheckActiveSubscription middleware

**Prioritet:** Kritisk  
**Estimat:** 30 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag middleware som sjekker om bruker har aktiv subscription før tilgang til beskyttede ruter.

**Filer som opprettes:**
- `app/Http/Middleware/CheckActiveSubscription.php`

**Akseptansekriterier:**
- [x] Sjekker om auth()->user()->tenant har aktiv subscription





- [x] Hvis inaktiv: Redirect til /subscription/inactive





- [x] Hvis aktiv: Fortsett til neste middleware



- [x] Eager load subscription for å unngå N+1

- [x] Fil-header og footer

**Testing:**
- Sett subscription.active = false i database
- Prøv å aksessere /dashboard
- Skal redirectes til /subscription/inactive

---

### Task 4.2: Opprett CheckAdminRole middleware

**Prioritet:** Høy  
**Estimat:** 20 min  
**Avhengigheter:** Task 1.3

**Beskrivelse:**  
Lag middleware som sjekker om bruker har admin-rolle.

**Filer som opprettes:**
- `app/Http/Middleware/CheckAdminRole.php`

**Akseptansekriterier:**
- [x] Sjekker om auth()->user()->role === 'admin'




- [x] Hvis ikke admin: Abort 403

- [x] Fil-header og footer (KUN NYE FILER/ENDRETE FILER)






---

### Task 4.3: Registrer middleware i Kernel

**Prioritet:** Kritisk  
**Estimat:** 15 min  
**Avhengigheter:** Task 4.1, 4.2

**Beskrivelse:**  
Registrer de nye middleware i Laravel sin Kernel.

**Filer som endres:**
- `bootstrap/app.php` (Laravel 12)

**Akseptansekriterier:**
- [x] Middleware alias: 'subscription' => CheckActiveSubscription




- [x] Middleware alias: 'admin' => CheckAdminRole

- [x] Dokumentert i kommentarer



---

### Task 4.4: Opprett "Inactive Subscription" side

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 4.1

**Beskrivelse:**  
Lag en enkel side som vises når subscription er inaktiv.

**Filer som opprettes:**
- `resources/views/subscription/inactive.blade.php`
- `app/Http/Controllers/SubscriptionController.php` (kun show metode)

**Filer som endres:**
- `routes/web.php`

**Akseptansekriterier:**
- [x] Route: GET /subscription/inactive





- [x] Viser melding: "Your subscription is inactive"




- [x] Forklaring: "Please contact support to activate your account"




- [x] Link til support email eller kontaktskjema




- [x] Følger design guide




- [x] Fil-header og footer (KUN NYE FILER/ENDRETE FILER)

---

## FASE 5: Tenant Dashboard (Dag 3)

### Task 5.1: Opprett DashboardController

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 4.1, 1.4

**Beskrivelse:**  
Lag controller som henter data for tenant dashboard. Denne controlleren skal samle statistikk og kommende bookinger for innlogget tenant.

**Filer som opprettes:**
- `app/Http/Controllers/DashboardController.php`

**Akseptansekriterier:**
- [x] Metode: index() returnerer dashboard view med data





- [x] Data: bookings_today (count), bookings_this_week (count), active_resources (count), subscription_status (boolean), upcoming_bookings (5 siste med resource eager loaded)




- [x] Optimaliserte queries: Bruk count() for statistikk, limit(5) for bookinger, with('resource') for eager loading




- [x] Fil-header: `// File: app/Http/Controllers/DashboardController.php`




- [x] Fil-footer: `// Controller for tenant dashboard - henter statistikk og kommende bookinger`

---

### Task 5.2: Opprett dashboard view

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 5.1

**Beskrivelse:**  
Lag dashboard view med stat cards og quick actions. Dette er hovedsiden tenant ser etter innlogging.

**Filer som opprettes:**
- `resources/views/dashboard.blade.php` (erstatt Breeze default)

**Akseptansekriterier:**
- [x] Velkomstmelding øverst: "Welcome, {{ auth()->user()->name }}!" med text-2xl font-bold




- [x] 4 stat cards i grid: "Bookings Today" (blue icon), "Bookings This Week" (green icon), "Active Resources" (purple icon), "Subscription Status" (badge)




- [x] Liste over upcoming bookings: Tabell med Resource, Customer, Date, Time (max 5 rader)




- [x] Quick actions seksjon: 3 knapper - "New Resource" (primary), "SMS Settings" (secondary), "Share Booking Page" (secondary med copy icon)




- [x] "Share Booking Page" bruker Alpine.js x-data med copyToClipboard() metode
- [x] Responsivt grid: grid-cols-1 md:grid-cols-2 lg:grid-cols-4 for stat cards
- [x] Tailwind classes: bg-white, rounded-lg, shadow-sm for cards, p-6 for padding
- [x] Alle tekster på engelsk: "Welcome", "Bookings Today", "Active Resources", etc.
- [x] Fil-header: `{{-- File: resources/views/dashboard.blade.php --}}`
- [x] Fil-footer: `{{-- Tenant dashboard - viser statistikk og quick actions --}}`

---

### Task 5.3: Legg til "Copy to Clipboard" funksjonalitet

**Prioritet:** Lav  
**Estimat:** 20 min  
**Avhengigheter:** Task 5.2

**Beskrivelse:**  
Implementer Alpine.js logikk for å kopiere booking link til clipboard.

**Filer som endres:**
- `resources/views/dashboard.blade.php`

**Akseptansekriterier:**
- [x] Knapp: "Share Booking Page"

- [x] Klikk kopierer URL: {{ url('/' . auth()->user()->tenant->slug) }}
- [x] Toast melding: "Link copied!" (implemented as button text change)
- [x] Bruker Alpine.js og navigator.clipboard API

---

## FASE 6: Ressurs CRUD (Dag 3-4)

### Task 6.1: Opprett ResourceController

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 1.4, 4.1

**Beskrivelse:**  
Lag resource controller med full CRUD funksjonalitet for booking-ressurser (hytter, stoler, rom, etc.).

**Filer som opprettes:**
- `app/Http/Controllers/ResourceController.php`

**Akseptansekriterier:**
- [x] Metoder: index() - liste ressurser, create() - vis form, store() - lagre ny, edit($id) - vis edit form, update($id) - lagre endringer, destroy($id) - slett





- [x] Global scope i index(): Resource::where('tenant_id', auth()->user()->tenant_id)





- [x] Validering i store/update: name (required, max:255, unique:resources,name,NULL,id,tenant_id), type (required), capacity (required, integer, min:1)





- [x] Eager loading i index/edit: Resource::with('availabilities')




- [x] Flash messages: session()->flash('success', 'Resource created successfully') / session()->flash('error', 'Failed to create resource')









- [x] Fil-header: `// File: app/Http/Controllers/ResourceController.php`




- [X] Fil-footer: `// CRUD controller for booking resources - håndterer hytter, stoler, rom, etc.`

---

### Task 6.2: Opprett resource index view

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 6.1

**Beskrivelse:**  
Lag liste-visning av alle ressurser for tenant. Viser tabell på desktop, cards på mobil.

**Filer som opprettes:**
- `resources/views/resources/index.blade.php`

**Akseptansekriterier:**
- [x] Tabell med kolonner: Name (text-gray-900 font-medium), Type (text-gray-600), Capacity (text-gray-600), Status (badge), Actions (flex gap-2)



- [x] Status badge: Active (bg-green-100 text-green-800) / Inactive (bg-gray-100 text-gray-800) med px-2 py-1 rounded-full




- [x] Actions: Edit (text-blue-600 hover:text-blue-800), Delete (text-red-600 hover:text-red-800)






- [x] "New Resource" knapp øverst høyre: bg-blue-600 text-white px-4 py-2 rounded-lg




- [x] Empty state hvis @if($resources->isEmpty()): Illustrasjon, "No resources yet", "Create your first resource to start receiving bookings", "Create Resource" knapp




- [x] Responsivt: hidden md:table for tabell, block md:hidden for cards





- [x] Tailwind: bg-white rounded-lg shadow-sm border border-gray-200 for container





- [x] Fil-header: `{{-- File: resources/views/resources/index.blade.php --}}`


- [x] Fil-footer: `{{-- Resource list view - viser alle ressurser for tenant --}}`


---

### Task 6.3: Opprett resource create/edit form

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 6.1

**Beskrivelse:**  
Lag skjema for å opprette og redigere ressurser. Bruker partial for å unngå duplisering.

**Filer som opprettes:**
- `resources/views/resources/create.blade.php` - wrapper med "Create Resource" tittel
- `resources/views/resources/edit.blade.php` - wrapper med "Edit Resource" tittel
- `resources/views/resources/_form.blade.php` - selve skjemaet (gjenbrukbart)

**Akseptansekriterier:**
- [x] Felter i _form.blade.php: name (text input, required), description (textarea, rows="4"), type (select dropdown), capacity (number input, min="1", default="1")





- [x] Type dropdown options: <option value="Cabin">Cabin</option>, "Chair", "Room", "Treatment Room", "Other"




- [x] Inline validering med Alpine.js: x-data="{ name: '', errors: {} }", @blur validering, viser feilmelding under felt




- [x] Submit knapp: create.blade.php har "Create Resource", edit.blade.php har "Update Resource" (bg-blue-600 text-white)




- [x] Cancel knapp: href="{{ route('resources.index') }}" (bg-white border border-gray-300 text-gray-700)
- [x] Tailwind form styling: w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500





- [x] Fil-header på alle 3 filer: `{{-- File: resources/views/resources/[filename] --}}`
- [x] Fil-footer: create: "Create form", edit: "Edit form", _form: "Shared form partial for create/edit"

---

### Task 6.4: Legg til delete funksjonalitet med modal

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 6.2

**Beskrivelse:**  
Implementer delete med bekreftelse i modal.

**Filer som endres:**
- `resources/views/resources/index.blade.php`

**Akseptansekriterier:**
- [x] Delete knapp åpner modal (Alpine.js)





- [x] Modal spør: "Are you sure you want to delete this resource?"






- [x] Advarsel: "All bookings for this resource will also be deleted"


- [ ] Confirm knapp sender DELETE request
- [x] Cancel knapp lukker modal


- [x] Følger design guide for modal


---


## FASE 7: Resource Availability (Dag 4)

### Task 7.1: Opprett availability management i resource form

**Prioritet:** Høy  
**Estimat:** 60 min  
**Avhengigheter:** Task 6.3

**Beskrivelse:**  
Utvid resource form til å inkludere åpningstider per ukedag.

**Filer som endres:**
- `resources/views/resources/_form.blade.php`
- `app/Http/Controllers/ResourceController.php`

**Akseptansekriterier:**
- [x] Seksjon: "Opening Hours"



- [x] For hver ukedag (Monday-Sunday): Checkbox (enabled), start_time, end_time
- [x] Quick setup: "Same hours every day" checkbox
- [x] Default: 09:00 - 17:00
- [x] Validering: end_time må være etter start_time
- [x] Store metode lagrer availabilities i resource_availabilities tabell
- [x] Update metode oppdaterer eksisterende availabilities
- [x] Alpine.js for "same hours" funksjonalitet

---

### Task 7.2: Opprett AvailabilityService

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Task 7.1

**Beskrivelse:**  
Lag service class som håndterer availability-logikk.

**Filer som opprettes:**
- `app/Services/AvailabilityService.php`

**Akseptansekriterier:**
- [x] Metode: getAvailableSlots($resource, $date) - returnerer ledige tider for en dato
- [x] Metode: isTimeSlotAvailable($resource, $date, $start_time, $end_time) - sjekker om tid er ledig
- [x] Tar hensyn til: åpningstider, eksisterende bookinger
- [x] Returnerer array av time slots (f.eks. ["09:00", "09:30", "10:00", ...])
- [x] Fil-header og footer

**Merk:** Servicen er fullstendig implementert og fungerer korrekt i manuell testing. 
Se `docs/reports/TASK_7.2_PROBLEM_REPORT.md` for detaljer om test-problemer.

---

## FASE 8: Offentlig Bookingside (Dag 4-5)

### Task 8.1: Opprett PublicBookingController

**Prioritet:** Kritisk  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag controller som håndterer offentlig bookingside (/{slug}) og booking-prosess. Ingen autentisering påkrevd.

**Filer som opprettes:**
- `app/Http/Controllers/PublicBookingController.php`

**Akseptansekriterier:**
- [ ] Metode: show($slug) - finn tenant via Tenant::where('slug', $slug)->firstOrFail(), eager load resources, returner view('public.booking', compact('tenant'))
- [ ] Metode: store(Request $request, $slug) - valider input, sjekk konflikt, lagre booking, returner redirect til confirmation
- [ ] Validering: resource_id (required, exists:resources,id), booking_date (required, date, after:today), start_time (required, date_format:H:i), end_time (required, date_format:H:i, after:start_time), customer_name (required, max:255), customer_email (required, email), customer_phone (required, regex:/^[+]?[0-9]{8,15}$/)
- [ ] Konflikt-sjekk: Booking::where('resource_id', $resource_id)->where('booking_date', $date)->whereBetween('start_time', [$start, $end])->exists()
- [ ] Returnerer: redirect()->route('booking.confirmation', ['id' => $booking->id]) ved suksess
- [ ] Ingen auth middleware
- [ ] Rate limiting i route: ->middleware('throttle:10,60') (10 requests per time)
- [ ] Fil-header: `// File: app/Http/Controllers/PublicBookingController.php`
- [ ] Fil-footer: `// Public booking controller - håndterer offentlig bookingside uten autentisering`

---

### Task 8.2: Opprett tenant bookingside view

**Prioritet:** Kritisk  
**Estimat:** 60 min  
**Avhengigheter:** Task 8.1

**Beskrivelse:**  
Lag offentlig bookingside som viser tenant-info og ressurser. Dette er siden kunder ser på /{slug}.

**Filer som opprettes:**
- `resources/views/public/booking.blade.php`

**Akseptansekriterier:**
- [ ] Header seksjon: <h1 class="text-3xl font-bold text-gray-900">{{ $tenant->name }}</h1>, <p class="text-lg text-gray-600">{{ $tenant->business_type }}</p>
- [ ] Beskrivelse: @if($tenant->description) <p class="mt-4 text-gray-700">{{ $tenant->description }}</p> @endif
- [ ] Grid av resource cards: <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
- [ ] Hver card: bg-white rounded-lg shadow-sm border p-6, viser name (font-semibold text-lg), description (text-gray-600 text-sm), capacity (text-gray-500 text-xs), "Book Now" knapp (bg-blue-600 text-white w-full)
- [ ] Klikk på "Book Now": @click="openModal({{ $resource->id }})" (Alpine.js)
- [ ] Responsivt: 1 col mobil, 2 col tablet, 3 col desktop
- [ ] Tailwind container: max-w-7xl mx-auto px-4 py-8
- [ ] Fil-header: `{{-- File: resources/views/public/booking.blade.php --}}`
- [ ] Fil-footer: `{{-- Public booking page - viser tenant info og ressurser for booking --}}`

---

### Task 8.3: Opprett booking modal med Alpine.js

**Prioritet:** Kritisk  
**Estimat:** 90 min  
**Avhengigheter:** Task 8.2, 7.2

**Beskrivelse:**  
Implementer booking modal med dato-velger, tid-velger og kunde-info.

**Filer som endres:**
- `resources/views/public/booking.blade.php`

**Akseptansekriterier:**
- [ ] Modal åpnes ved klikk på "Book Now"
- [ ] Steg 1: Velg dato (date input, kun fremtidige datoer)
- [ ] Steg 2: Velg tid (dropdown med ledige slots fra AvailabilityService)
- [ ] Steg 3: Kunde-info (name, email, phone, notes)
- [ ] Inline validering på alle felter
- [ ] Submit knapp disabled til alle felter er gyldige
- [ ] Loading state ved submit
- [ ] Alpine.js for modal og form state
- [ ] Følger design guide

---

### Task 8.4: Opprett booking bekreftelsesside

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 8.1

**Beskrivelse:**  
Lag bekreftelsesside som vises etter vellykket booking.

**Filer som opprettes:**
- `resources/views/public/booking-confirmation.blade.php`

**Akseptansekriterier:**
- [ ] Success melding: "Booking Confirmed!"
- [ ] Viser: Booking ID, Resource name, Date, Time, Customer name
- [ ] Melding: "You will receive a confirmation via email/SMS"
- [ ] Knapp: "Book Another" (går tilbake til /{slug})
- [ ] Følger design guide
- [ ] Fil-header og footer

---

### Task 8.5: Legg til 404 side for ugyldig slug

**Prioritet:** Middels  
**Estimat:** 20 min  
**Avhengigheter:** Task 8.1

**Beskrivelse:**  
Lag custom 404 side for når slug ikke finnes.

**Filer som opprettes:**
- `resources/views/errors/404.blade.php`

**Akseptansekriterier:**
- [ ] Melding: "Tenant Not Found"
- [ ] Forklaring: "The page you're looking for doesn't exist"
- [ ] Link: "Go to Home Page"
- [ ] Følger design guide
- [ ] Fil-header og footer

---

## FASE 9: Booking Management (Dag 5)

### Task 9.1: Opprett BookingController for tenant

**Prioritet:** Høy  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.4, 4.1

**Beskrivelse:**  
Lag controller for tenant å se og administrere bookinger for sine ressurser.

**Filer som opprettes:**
- `app/Http/Controllers/BookingController.php`

**Akseptansekriterier:**
- [ ] Metode: index(Request $request) - hent bookinger via Resource::where('tenant_id', auth()->user()->tenant_id)->pluck('id'), filtrer med $request->filter ('upcoming'/'past'/'all'), sorter orderBy('booking_date', 'desc')
- [ ] Metode: show($id) - finn booking, sjekk at booking->resource->tenant_id === auth()->user()->tenant_id, returner view
- [ ] Metode: updateStatus($id, Request $request) - valider status (in:pending,confirmed,cancelled), oppdater booking->status, returner redirect med flash message
- [ ] Filtrering: if($filter === 'upcoming') whereDate('booking_date', '>=', now()), if($filter === 'past') whereDate('booking_date', '<', now())
- [ ] Sortering: ->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')
- [ ] Eager loading: Booking::with('resource')->whereIn('resource_id', $resourceIds)
- [ ] Fil-header: `// File: app/Http/Controllers/BookingController.php`
- [ ] Fil-footer: `// Booking management controller - tenant administrerer bookinger for sine ressurser`

---

### Task 9.2: Opprett booking list view

**Prioritet:** Høy  
**Estimat:** 45 min  
**Avhengigheter:** Task 9.1

**Beskrivelse:**  
Lag liste-visning av bookinger for tenant.

**Filer som opprettes:**
- `resources/views/bookings/index.blade.php`

**Akseptansekriterier:**
- [ ] Tabell: Booking ID, Resource, Customer, Date, Time, Status, Actions
- [ ] Status badge: Confirmed (grønn), Pending (gul), Cancelled (rød)
- [ ] Filter tabs: Upcoming, Past, All
- [ ] Actions: View Details, Cancel
- [ ] Paginering (20 per side)
- [ ] Responsivt design
- [ ] Følger design guide
- [ ] Fil-header og footer

---

### Task 9.3: Opprett booking detail view

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 9.1

**Beskrivelse:**  
Lag detaljvisning for én booking.

**Filer som opprettes:**
- `resources/views/bookings/show.blade.php`

**Akseptansekriterier:**
- [ ] Viser all info: Resource, Date, Time, Customer (name, email, phone), Notes, Status
- [ ] Knapper: "Confirm", "Cancel" (hvis ikke allerede cancelled)
- [ ] Tilbake-knapp til liste
- [ ] Følger design guide
- [ ] Fil-header og footer

---

## FASE 10: Admin Dashboard (Dag 5-6)

### Task 10.1: Opprett AdminController

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Task 4.2

**Beskrivelse:**  
Lag controller for admin dashboard. Kun tilgjengelig for brukere med role='admin'.

**Filer som opprettes:**
- `app/Http/Controllers/AdminController.php`

**Akseptansekriterier:**
- [ ] Metode: index() - hent statistikk: Tenant::count(), Tenant::where('active', true)->count(), Tenant::where('active', false)->count(), Booking::count(), returner view('admin.dashboard', compact('total_tenants', 'active_tenants', 'inactive_tenants', 'total_bookings'))
- [ ] Metode: tenants(Request $request) - hent alle tenants med søk/filter: Tenant::when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")), when($request->filter === 'active', fn($q) => $q->where('active', true)), paginate(20)
- [ ] Metode: toggleTenantStatus($id) - finn tenant, toggle active status: $tenant->update(['active' => !$tenant->active]), returner back() med flash message
- [ ] Data variabler: $total_tenants, $active_tenants, $inactive_tenants, $total_bookings (alle integers)
- [ ] Middleware: Må ha 'admin' middleware på alle routes
- [ ] Fil-header: `// File: app/Http/Controllers/AdminController.php`
- [ ] Fil-footer: `// Admin controller - system administrator dashboard og tenant management`

---

### Task 10.2: Opprett admin dashboard view

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Task 10.1

**Beskrivelse:**  
Lag admin dashboard med oversikt.

**Filer som opprettes:**
- `resources/views/admin/dashboard.blade.php`

**Akseptansekriterier:**
- [ ] 4 stat cards: Total Tenants, Active, Inactive, Total Bookings
- [ ] Link til tenant management
- [ ] Følger design guide
- [ ] Fil-header og footer

---

### Task 10.3: Opprett tenant management view

**Prioritet:** Middels  
**Estimat:** 60 min  
**Avhengigheter:** Task 10.1

**Beskrivelse:**  
Lag liste-visning av alle tenants for admin.

**Filer som opprettes:**
- `resources/views/admin/tenants.blade.php`

**Akseptansekriterier:**
- [ ] Tabell: Name, Slug, Business Type, Status, Created, Actions
- [ ] Status toggle (inline switch med Alpine.js)
- [ ] Søk på name eller slug
- [ ] Filter: Active / Inactive / All
- [ ] Sortering på alle kolonner
- [ ] Paginering (20 per side)
- [ ] Følger design guide
- [ ] Fil-header og footer

---


## FASE 11: SMS Integration (Dag 6)

### Task 11.1: Opprett SMS settings tabell migration

**Prioritet:** Høy  
**Estimat:** 20 min  
**Avhengigheter:** Task 1.1

**Beskrivelse:**  
Lag migration for SMS settings tabell.

**Filer som opprettes:**
- `database/migrations/YYYY_MM_DD_000008_create_sms_settings_table.php`

**Akseptansekriterier:**
- [ ] Kolonner: id, tenant_id (FK, unique), api_key (text), enabled (boolean), timestamps
- [ ] Foreign key til tenants.id
- [ ] `php artisan migrate` kjører uten feil

---

### Task 11.2: Opprett SmsSettings model

**Prioritet:** Høy  
**Estimat:** 20 min  
**Avhengigheter:** Task 11.1

**Beskrivelse:**  
Lag Eloquent model for SMS settings.

**Filer som opprettes:**
- `app/Models/SmsSettings.php`

**Akseptansekriterier:**
- [ ] Relationship: belongsTo(tenant)
- [ ] Casts: api_key → encrypted, enabled → boolean
- [ ] Fillable: api_key, enabled
- [ ] Fil-header og footer

---

### Task 11.3: Opprett TeletopiaSmsService

**Prioritet:** Høy  
**Estimat:** 60 min  
**Avhengigheter:** Task 11.2

**Beskrivelse:**  
Lag service class for Teletopia SMS API integrasjon. Håndterer sending av SMS via Teletopia sitt API.

**Filer som opprettes:**
- `app/Services/TeletopiaSmsService.php`

**Akseptansekriterier:**
- [ ] Metode: sendSms($tenantId, $phoneNumber, $message) - hent SmsSettings::where('tenant_id', $tenantId)->first(), sjekk enabled, send HTTP POST til Teletopia API, returner ['success' => true/false, 'message' => '...']
- [ ] Henter API-nøkkel: $settings = SmsSettings::where('tenant_id', $tenantId)->first(), $apiKey = $settings->api_key (automatisk dekryptert via cast)
- [ ] HTTP client: use Illuminate\Support\Facades\Http; Http::timeout(5)->withHeaders(['Authorization' => "Bearer {$apiKey}"])->post('https://api.teletopia.no/sms/send', ['to' => $phoneNumber, 'message' => $message])
- [ ] Error handling: try-catch, hvis exception returner ['success' => false, 'message' => $e->getMessage()], hvis HTTP error returner ['success' => false, 'message' => 'Failed to send SMS']
- [ ] Logging: Log::info("SMS sent to {$phoneNumber}", ['tenant_id' => $tenantId, 'success' => $success])
- [ ] Timeout: Http::timeout(5) (5 sekunder)
- [ ] Fil-header: `// File: app/Services/TeletopiaSmsService.php`
- [ ] Fil-footer: `// Teletopia SMS service - sender SMS via Teletopia API med error handling og logging`

**API Endpoint:**
```
POST https://api.teletopia.no/sms/send
Headers: Authorization: Bearer {api_key}
Body: {"to": "+4712345678", "message": "Your message"}
```

---

### Task 11.4: Opprett SmsController

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Task 11.3

**Beskrivelse:**  
Lag controller for SMS settings og test-funksjon.

**Filer som opprettes:**
- `app/Http/Controllers/SmsController.php`

**Akseptansekriterier:**
- [ ] Metode: index() - vis SMS settings side
- [ ] Metode: update() - lagre API-nøkkel
- [ ] Metode: test() - send test SMS
- [ ] Validering: api_key (required), phone_number (required for test)
- [ ] Middleware: subscription
- [ ] Fil-header og footer

---

### Task 11.5: Opprett SMS settings view

**Prioritet:** Høy  
**Estimat:** 45 min  
**Avhengigheter:** Task 11.4

**Beskrivelse:**  
Lag SMS settings side med API-nøkkel form og test-funksjon.

**Filer som opprettes:**
- `resources/views/sms/index.blade.php`

**Akseptansekriterier:**
- [ ] Form: API Key (password input, maskert)
- [ ] Checkbox: "Enable SMS notifications"
- [ ] Save knapp
- [ ] Seksjon: "Test SMS"
- [ ] Input: Phone number
- [ ] Knapp: "Send Test SMS"
- [ ] Loading state ved test (Alpine.js)
- [ ] Success/error melding
- [ ] Hjelpetekst: "Where to find your API key?" med link
- [ ] Følger design guide
- [ ] Fil-header og footer

---

## FASE 12: Landingsside (Dag 6-7)

### Task 12.1: Opprett LandingController

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag controller for landingsside.

**Filer som opprettes:**
- `app/Http/Controllers/LandingController.php`

**Akseptansekriterier:**
- [ ] Metode: index() - henter alle aktive tenants
- [ ] Caching: Cache tenant list i 5 minutter
- [ ] Sortering: Nyeste først
- [ ] Fil-header og footer

---

### Task 12.2: Opprett landingsside view

**Prioritet:** Middels  
**Estimat:** 90 min  
**Avhengigheter:** Task 12.1ask 12.1

**Beskrivelse:**  
Lag landingsside med hero og tenant listing. Dette er forsiden (/) som alle besøkende ser.

**Filer som opprettes:**
- `resources/views/welcome.blade.php` (erstatt Laravel default)

**Akseptansekriterier:**
- [ ] Hero seksjon: <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">, <h1 class="text-4xl md:text-5xl font-bold">"Book Your Next Experience"</h1>, <p class="text-xl mt-4">"Find and book services from trusted providers"</p>, <a href="{{ route('register') }}" class="mt-8 inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold">"Get Started"</a>
- [ ] Tenant grid: <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">, @foreach($tenants as $tenant), card med bg-white rounded-lg shadow-sm p-6
- [ ] Hver tenant card: <h3 class="text-lg font-semibold">{{ $tenant->name }}</h3>, <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $tenant->business_type }}</span>, <p class="text-gray-600 text-sm mt-2">{{ Str::limit($tenant->description, 100) }}</p>, <a href="/{{ $tenant->slug }}" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg">"Book Now"</a>
- [ ] Responsivt grid: grid-cols-1 (mobil), md:grid-cols-2 (tablet), lg:grid-cols-3 (desktop)
- [ ] Footer: <footer class="bg-gray-800 text-white py-8 mt-20">, links til About, Contact, Privacy (href="#" placeholder)
- [ ] Tailwind: max-w-7xl mx-auto px-4 for container
- [ ] Fil-header: `{{-- File: resources/views/welcome.blade.php --}}`
- [ ] Fil-footer: `{{-- Landing page - viser hero og liste over alle aktive tenants --}}`

---

### Task 12.3: Legg til søk og filter funksjonalitet

**Prioritet:** Lav  
**Estimat:** 45 min  
**Avhengigheter:** Task 12.2

**Beskrivelse:**  
Implementer søk og filter på landingsside med Alpine.js.

**Filer som endres:**
- `resources/views/welcome.blade.php`

**Akseptansekriterier:**
- [ ] Søkefelt: Filter på tenant name (live search)
- [ ] Filter chips: Business types (klikk for å filtrere)
- [ ] Alpine.js for client-side filtering
- [ ] Smooth transitions ved filtering
- [ ] "No results" melding hvis ingen match

---

## FASE 13: Navigation og Layout (Dag 7)

### Task 13.1: Opprett hovednavigasjon for tenant

**Prioritet:** Høy  
**Estimat:** 45 min  
**Avhengigheter:** Task 5.2

**Beskrivelse:**  
Lag hovednavigasjon for innloggede tenant-brukere.

**Filer som endres:**
- `resources/views/layouts/navigation.blade.php`

**Akseptansekriterier:**
- [ ] Logo og app navn
- [ ] Nav links: Dashboard, Resources, Bookings, SMS Settings
- [ ] User dropdown: Profile, Settings, Logout
- [ ] Aktiv link highlightet
- [ ] Hamburger menu på mobil (Alpine.js)
- [ ] Følger design guide
- [ ] Fil-header og footer

---

### Task 13.2: Opprett admin navigation

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Task 10.2

**Beskrivelse:**  
Lag separat navigasjon for admin-brukere.

**Filer som opprettes:**
- `resources/views/layouts/admin-navigation.blade.php`

**Akseptansekriterier:**
- [ ] Logo og "Admin Panel"
- [ ] Nav links: Dashboard, Tenants
- [ ] User dropdown: Logout
- [ ] Følger design guide
- [ ] Fil-header og footer

---

### Task 13.3: Opprett Blade components for gjenbrukbare elementer

**Prioritet:** Middels  
**Estimat:** 60 min  
**Avhengigheter:** Ingen (kan gjøres når som helst)

**Beskrivelse:**  
Lag Blade components for ofte brukte UI-elementer.

**Filer som opprettes:**
- `resources/views/components/button.blade.php`
- `resources/views/components/card.blade.php`
- `resources/views/components/badge.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/components/modal.blade.php`

**Akseptansekriterier:**
- [ ] Button: Props for variant (primary, secondary, danger), size
- [ ] Card: Slot for content, optional header/footer
- [ ] Badge: Props for color (success, warning, error, info)
- [ ] Alert: Props for type (success, error, warning, info)
- [ ] Modal: Alpine.js powered, props for title
- [ ] Følger design guide
- [ ] Fil-header og footer

**Bruk:**
```blade
<x-button variant="primary">Save</x-button>
<x-badge color="success">Active</x-badge>
```

---

## FASE 14: Routes og Policies (Dag 7)

### Task 14.1: Organisér alle routes

**Prioritet:** Høy  
**Estimat:** 30 min  
**Avhengigheter:** Alle controller tasks

**Beskrivelse:**  
Organisér alle routes i web.php med kommentarer og middleware.

**Filer som endres:**
- `routes/web.php`

**Akseptansekriterier:**
- [ ] Gruppering: Public, Auth, Tenant (subscription middleware), Admin (admin middleware)
- [ ] Kommentarer for hver seksjon
- [ ] Resource routes for ResourceController, BookingController
- [ ] Named routes for alle viktige ruter
- [ ] Rate limiting på public booking route

**Struktur:**
```php
// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/{slug}', [PublicBookingController::class, 'show'])->name('booking.show');
Route::post('/{slug}/bookings', [PublicBookingController::class, 'store'])->name('booking.store');

// Auth Routes (Breeze)
require __DIR__.'/auth.php';

// Tenant Routes (Protected)
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dashboard/resources', ResourceController::class);
    // ...
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    // ...
});
```

---

### Task 14.2: Opprett Policies for authorization

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Task 1.4

**Beskrivelse:**  
Lag policies for å sikre at brukere kun kan aksessere sine egne ressurser.

**Filer som opprettes:**
- `app/Policies/ResourcePolicy.php`
- `app/Policies/BookingPolicy.php`

**Akseptansekriterier:**
- [ ] ResourcePolicy: view, update, delete sjekker at resource.tenant_id === user.tenant_id
- [ ] BookingPolicy: view, update sjekker at booking.resource.tenant_id === user.tenant_id
- [ ] Registrert i AuthServiceProvider
- [ ] Fil-header og footer

---

## FASE 15: Polish og Testing (Dag 7-8)

### Task 15.1: Legg til toast notification system

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Ingen

**Beskrivelse:**  
Implementer global toast notification system med Alpine.js.

**Filer som opprettes:**
- `resources/views/components/toast.blade.php`

**Filer som endres:**
- `resources/views/layouts/app.blade.php`

**Akseptansekriterier:**
- [ ] Toast component i layout (topp høyre hjørne)
- [ ] Alpine.js event listener: @notify.window
- [ ] Auto-dismiss etter 4 sekunder
- [ ] Kan lukkes manuelt
- [ ] Smooth slide-in/out animasjon
- [ ] Følger design guide

**Bruk:**
```blade
<script>
window.dispatchEvent(new CustomEvent('notify', {
    detail: 'Resource created successfully!'
}));
</script>
```

---

### Task 15.2: Legg til loading states

**Prioritet:** Lav  
**Estimat:** 30 min  
**Avhengigheter:** Alle form tasks

**Beskrivelse:**  
Legg til loading states på alle forms og knapper.

**Filer som endres:**
- Alle views med forms

**Akseptansekriterier:**
- [ ] Submit knapper viser "Loading..." tekst og spinner ved submit
- [ ] Knapper disables ved submit
- [ ] Alpine.js x-data for loading state
- [ ] Følger design guide

---

### Task 15.3: Valider alle forms med inline feedback

**Prioritet:** Høy  
**Estimat:** 60 min  
**Avhengigheter:** Alle form tasks

**Beskrivelse:**  
Sørg for at alle forms har inline validering og tydelige feilmeldinger.

**Filer som endres:**
- Alle views med forms

**Akseptansekriterier:**
- [ ] Alle påkrevde felter markert med *
- [ ] Inline validering ved blur
- [ ] Feilmeldinger under felt (ikke modal)
- [ ] Grønn border + checkmark hvis OK
- [ ] Rød border + feilmelding hvis feil
- [ ] Submit knapp disabled hvis form invalid

---

### Task 15.4: Test alle brukerreiser

**Prioritet:** Kritisk  
**Estimat:** 120 min  
**Avhengigheter:** Alle tasks

**Beskrivelse:**  
Manuelt test alle hovedbrukerreiser end-to-end.

**Test cases:**
1. **Registrering til første booking:**
   - Registrer ny tenant
   - Opprett ressurs med åpningstider
   - Gå til /{slug}
   - Gjør booking
   - Verifiser booking vises i dashboard

2. **Admin workflow:**
   - Logg inn som admin
   - Se alle tenants
   - Deaktiver en tenant
   - Verifiser tenant ikke kan aksessere dashboard

3. **SMS test:**
   - Legg inn API-nøkkel
   - Send test SMS
   - Verifiser SMS mottas

4. **Edge cases:**
   - Prøv å booke opptatt tid
   - Prøv å aksessere annen tenant sine ressurser
   - Prøv ugyldig slug
   - Test på mobil

**Akseptansekriterier:**
- [ ] Alle brukerreiser fungerer uten feil
- [ ] Alle edge cases håndteres gracefully
- [ ] Ingen console errors
- [ ] Fungerer på mobil og desktop

---

### Task 15.5: Optimaliser database queries

**Prioritet:** Middels  
**Estimat:** 45 min  
**Avhengigheter:** Alle controller tasks

**Beskrivelse:**  
Gjennomgå alle controllers og optimaliser queries.

**Akseptansekriterier:**
- [ ] Eager loading brukt overalt (with())
- [ ] Ingen N+1 query problemer
- [ ] Select kun nødvendige kolonner hvor relevant
- [ ] Indexes på alle foreign keys
- [ ] Test med Laravel Debugbar eller Telescope

---

### Task 15.6: Legg til seed data for demo

**Prioritet:** Lav  
**Estimat:** 30 min  
**Avhengigheter:** Task 2.2

**Beskrivelse:**  
Lag seeder som fyller database med demo-data.

**Filer som opprettes:**
- `database/seeders/DemoSeeder.php`

**Akseptansekriterier:**
- [ ] Oppretter 5 demo tenants med forskjellige business types
- [ ] Hver tenant har 2-3 ressurser
- [ ] Hver ressurs har åpningstider
- [ ] Noen bookinger (både kommende og tidligere)
- [ ] Én admin bruker
- [ ] Kan kjøres med: `php artisan db:seed --class=DemoSeeder`

---

## FASE 16: Dokumentasjon og Deployment Prep (Dag 8)

### Task 16.1: Oppdater README.md

**Prioritet:** Middels  
**Estimat:** 30 min  
**Avhengigheter:** Alle tasks

**Beskrivelse:**  
Oppdater README med setup-instruksjoner og feature-oversikt.

**Filer som endres:**
- `README.md`

**Akseptansekriterier:**
- [ ] Prosjektbeskrivelse
- [ ] Features liste
- [ ] Setup instruksjoner (database, .env, migrations, seeding)
- [ ] Hvordan kjøre prosjektet
- [ ] Test-brukere (admin og tenant)
- [ ] Teknologi stack

---

### Task 16.2: Lag .env.example med alle nødvendige variabler

**Prioritet:** Høy  
**Estimat:** 15 min  
**Avhengigheter:** Alle tasks

**Beskrivelse:**  
Sørg for at .env.example inneholder alle nødvendige environment variabler.

**Filer som endres:**
- `.env.example`

**Akseptansekriterier:**
- [ ] APP_* variabler
- [ ] DB_* variabler
- [ ] TELETOPIA_API_URL (hvis brukt)
- [ ] Kommentarer for viktige variabler

---

### Task 16.3: Cleanup og code review

**Prioritet:** Middels  
**Estimat:** 60 min  
**Avhengigheter:** Alle tasks

**Beskrivelse:**  
Gjennomgå all kode for konsistens og kvalitet.

**Sjekkliste:**
- [ ] Alle filer har header og footer
- [ ] Konsistente navnekonvensjoner
- [ ] Ingen hardkodede verdier
- [ ] Ingen commented-out kode
- [ ] Konsistent bruk av Tailwind classes
- [ ] Alle brukersynlige tekster på engelsk
- [ ] Alle kommentarer på norsk

---

## Oppsummering

**Total estimert tid:** 7-8 dager (ca. 40-50 timer)

**Kritiske milepæler:**
- Dag 1-2: Database og multi-tenant registrering fungerer
- Dag 3-4: Ressurs CRUD og offentlig bookingside fungerer
- Dag 5-6: Booking management og admin dashboard fungerer
- Dag 7-8: SMS, landingsside og polish

**Testing-punkter:**
- Etter Fase 3: Test registrering og tenant-opprettelse
- Etter Fase 6: Test ressurs CRUD
- Etter Fase 8: Test full booking-flyt
- Etter Fase 11: Test SMS-integrasjon
- Etter Fase 15: Full end-to-end testing

**Neste steg:**
Start med Task 1.1 og jobb deg gjennom listen sekvensielt. Hver task skal være fullførbar på 30-90 minutter.

---

**Version:** 1.0  
**Last Updated:** December 2025  
**Status:** Ready for Implementation
