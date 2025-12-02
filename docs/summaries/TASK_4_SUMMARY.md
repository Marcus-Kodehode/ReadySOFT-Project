# Task 4.1 - CheckActiveSubscription Middleware Implementation

## Date: December 2, 2025

## Overview
Implementert middleware som sjekker aktiv subscription før tilgang til beskyttede ruter, med redirect til dedikert inactive-side.

## Files Created/Modified

### 1. CheckActiveSubscription.php
**Path:** `app/Http/Middleware/CheckActiveSubscription.php`

**Funksjonalitet:**
- Sjekker om autentisert bruker har aktiv subscription
- Redirecter til `/subscription/inactive` hvis inaktiv
- Fortsetter til neste middleware hvis aktiv
- Eager loading av subscription for å unngå N+1 queries
- Lar admin-brukere (uten tenant_id) passere

### 2. SubscriptionController.php
**Path:** `app/Http/Controllers/SubscriptionController.php`

**Metode:**
- `inactive()` - Viser inactive subscription-siden

### 3. inactive.blade.php
**Path:** `resources/views/subscription/inactive.blade.php`

**Features:**
- Warning icon og tydelig melding
- Kontaktinformasjon (support@readysoft.no)
- Sign out og back to dashboard knapper
- Responsivt design med Tailwind CSS
- Følger design guide (farger, spacing, typography)

### 4. Route Registration
**Path:** `routes/web.php`

**Endringer:**
- Registrert route: `GET /subscription/inactive` → `subscription.inactive`
- Beskyttet med `auth` middleware

## Acceptance Criteria - Fullført

✅ **Sjekker om auth()->user()->tenant har aktiv subscription**
- Middleware henter tenant med eager loading av subscriptions
- Sjekker om minst én subscription har `active = true`

✅ **Hvis inaktiv: Redirect til /subscription/inactive**
- Implementert redirect med `redirect()->route('subscription.inactive')`
- Viser brukervennlig side med kontaktinformasjon

✅ **Hvis aktiv: Fortsett til neste middleware**
- Returnerer `$next($request)` når subscription er aktiv

✅ **Eager load subscription for å unngå N+1**
- Bruker `with('subscriptions')` for å laste relasjoner i én query
- Sjekker allerede loadede subscriptions uten ekstra database-kall

✅ **Fil-header og footer**
- Alle filer har header med filnavn og plassering
- Footer med norsk beskrivelse av filens formål

## Testing

**Test scenario:**
1. Sett `subscription.active = false` i database
2. Prøv å aksessere `/dashboard`
3. Verifiser redirect til `/subscription/inactive`

**Resultat:** ✅ Fungerer som forventet

## Design Compliance

- Tailwind CSS classes for konsistent styling
- Fargepalett: Yellow (warning), Blue (actions), Gray (neutral)
- Responsivt layout for mobil og desktop
- Accessible focus states og keyboard navigation


---

# Task 4.2 - CheckAdminRole Middleware Implementation

## Date: December 2, 2025

## Overview
Implementert middleware som sjekker om bruker har admin-rolle før tilgang til admin-ruter, med 403 Forbidden for ikke-admin brukere.

## Files Created

### 1. CheckAdminRole.php
**Path:** `app/Http/Middleware/CheckAdminRole.php`

**Funksjonalitet:**
- Sjekker om autentisert bruker har `role === 'admin'`
- Returnerer 403 Forbidden hvis ikke admin
- Redirecter til login hvis ikke autentisert
- Lar kun admin-brukere fortsette til neste middleware

**Implementasjonsdetaljer:**
```php
// Sjekk autentisering
if (!auth()->check()) {
    return redirect()->route('login');
}

// Sjekk admin-rolle
if (auth()->user()->role !== 'admin') {
    abort(403, 'Unauthorized. Admin access required.');
}

// Admin bekreftet, fortsett
return $next($request);
```

## Acceptance Criteria - Fullført

✅ **Sjekker om auth()->user()->role === 'admin'**
- Middleware verifiserer at brukerens rolle er eksakt 'admin'
- Bruker strict comparison (===) for sikkerhet

✅ **Hvis ikke admin: Abort 403**
- Implementert med `abort(403, 'Unauthorized. Admin access required.')`
- Tydelig feilmelding som forklarer hvorfor tilgang nektes

✅ **Fil-header og footer (KUN NYE FILER/ENDRETE FILER)**
- Header med filnavn og plassering
- Norske kommentarer som forklarer middleware-formål
- Footer med beskrivelse av bruksområde

## Design Pattern

Følger samme mønster som `CheckActiveSubscription`:
- Konsistent struktur og navngivning
- Norske kommentarer for dokumentasjon
- Proper error handling og redirects
- Type hints for Request og Response

## Next Steps

**Task 4.3:** Registrere middleware i `bootstrap/app.php`
- Alias: `'admin' => CheckAdminRole::class`
- Vil brukes på alle `/admin/*` ruter
- Sikrer at kun admin-brukere kan aksessere admin-dashboard og tenant-management

## Security Considerations

- **Role-based access control:** Kun brukere med `role = 'admin'` får tilgang
- **Unauthenticated handling:** Redirecter til login før role-sjekk
- **Clear error messages:** 403 med beskrivende melding
- **No bypass:** Middleware må registreres på alle admin-ruter for full beskyttelse

## Database Schema Reference

Fra migration `2025_12_01_000007_add_tenant_fields_to_users_table.php`:
```php
$table->enum('role', ['admin', 'tenant_admin'])
      ->default('tenant_admin')
      ->after('tenant_id');
```

- **Enum values:** 'admin', 'tenant_admin'
- **Default:** 'tenant_admin' (for nye registreringer)
- **Admin users:** Må settes manuelt i database eller via seeder
