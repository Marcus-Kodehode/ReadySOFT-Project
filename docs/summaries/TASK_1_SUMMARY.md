# Task 1 - Database Foundation

## Oversikt
Fase 1 etablerer databasestrukturen for multi-tenant systemet. Dette er grunnmuren for hele applikasjonen.

---

## Task 1.1: Database-migrasjoner for core tabeller ✅

**Status:** Fullført  

### Hva ble gjort
Opprettet og verifisert tre kritiske database-tabeller som utgjør kjernen i multi-tenant systemet:

#### 1. **Tenants tabell**
- Representerer hver kunde som får sin egen bookingside
- Unik `slug` for URL-routing (f.eks. `/salong-rosa`)
- `business_type` for kategorisering
- `active` status for å kontrollere tilgang

#### 2. **Plans tabell**
- Definerer abonnementsplaner (f.eks. "Basic Plan")
- JSON `features` felt for fleksibel konfigurasjon
- Grunnlag for fremtidig prismodell

#### 3. **Subscriptions tabell**
- Kobler tenants til plans
- `active` status kontrollerer systemtilgang
- Foreign keys med cascade delete sikrer dataintegritet

### Tekniske detaljer
```sql
-- Indexes for ytelse
tenants: slug (unique), active
subscriptions: tenant_id + active (compound)

-- Foreign keys
subscriptions.tenant_id → tenants.id (cascade)
subscriptions.plan_id → plans.id (cascade)
```

### Verifisering
```bash
php artisan migrate:fresh  # ✅ Kjørte uten feil
php artisan db:table tenants  # ✅ Struktur korrekt
php artisan db:table plans  # ✅ Struktur korrekt
php artisan db:table subscriptions  # ✅ Struktur korrekt
```

---

## Task 1.2: Database-migrasjoner for booking-tabeller ✅

**Status:** Fullført  

### Hva ble gjort
Opprettet og verifisert tre kritiske database-tabeller for booking-funksjonaliteten:

#### 1. **Resources tabell**
- Lagrer bookbare ressurser (hytter, stoler, rom, behandlingsrom)
- Tilhører en tenant via `tenant_id` foreign key
- Har `type`, `capacity` og `active` status
- Cascade delete når tenant slettes

#### 2. **Resource_availabilities tabell**
- Definerer åpningstider per ukedag for hver ressurs
- `day_of_week` (0=Sunday, 6=Saturday)
- `start_time` og `end_time` for åpningstider
- Cascade delete når ressurs slettes

#### 3. **Bookings tabell**
- Lagrer alle bookinger med kunde-informasjon
- `customer_name`, `customer_email`, `customer_phone`
- `booking_date`, `start_time`, `end_time`
- Status: `pending`, `confirmed`, `cancelled` (default: confirmed)
- Cascade delete når ressurs slettes

### Tekniske detaljer
```sql
-- Indexes for ytelse
resources: tenant_id, tenant_id + active (compound)
resource_availabilities: resource_id + day_of_week (compound)
bookings: resource_id, booking_date

-- Foreign keys med cascade
resources.tenant_id → tenants.id (cascade)
resource_availabilities.resource_id → resources.id (cascade)
bookings.resource_id → resources.id (cascade)
```

### Cascade-effekt
```
Tenant slettet
  └─> Resources slettet (cascade)
      ├─> Resource_availabilities slettet (cascade)
      └─> Bookings slettet (cascade)
```

### Verifisering
```bash
php artisan migrate:status  # ✅ Alle migrasjoner kjørt
php artisan db:table resources  # ✅ Struktur og FK korrekt
php artisan db:table resource_availabilities  # ✅ Struktur og FK korrekt
php artisan db:table bookings  # ✅ Struktur og FK korrekt
```

---

## Task 1.3: Utvid users tabell med tenant_id og role ✅

**Status:** Fullført  

### Hva ble gjort
Utvidet den eksisterende `users` tabell med multi-tenant funksjonalitet gjennom en ny migrasjon:

#### Nye kolonner
- **`tenant_id`** (nullable, foreign key)
  - Kobler brukere til deres tenant
  - Nullable fordi admin-brukere ikke tilhører en tenant
  - Foreign key til `tenants.id` med cascade delete
  
- **`role`** (enum: 'admin', 'tenant_admin')
  - Default: `tenant_admin` for vanlige kunder
  - `admin` for system-administratorer
  - Brukes av middleware for tilgangskontroll

### Tekniske detaljer
```sql
-- Ny kolonne
users.tenant_id → tenants.id (nullable, cascade)
users.role ENUM('admin', 'tenant_admin') DEFAULT 'tenant_admin'

-- Index for ytelse
INDEX idx_tenant_id (tenant_id)
```

### Cascade-effekt
```
Tenant slettet
  └─> Users slettet (cascade)
      └─> Alle brukere tilknyttet tenant fjernes
```

### Verifisering
```bash
php artisan migrate:fresh  # ✅ Alle migrasjoner kjørt uten feil
php artisan migrate:status  # ✅ Migration 1.3 kjørt i batch 1
```

### Betydning
Denne migrasjonen gjør det mulig å:
- Skille mellom admin og tenant_admin roller
- Isolere brukere per tenant
- Implementere rollebasert tilgangskontroll
- Sikre at admin-brukere kan administrere alle tenants

---

---

## Task 1.4: Eloquent modeller med relasjoner ✅

**Status:** Fullført  

### Hva ble gjort
Opprettet alle Eloquent modeller med korrekte relasjoner, fillable fields, casts, og dokumentasjon. Hver modell har fil-header og footer som forklarer dens rolle i systemet.

#### 1. **Tenant Model** (`app/Models/Tenant.php`)
- **Fillable:** name, slug, business_type, description, active
- **Casts:** active → boolean
- **Relasjoner:**
  - `hasMany(Subscription)` - En tenant kan ha flere subscriptions
  - `hasMany(Resource)` - En tenant kan ha flere ressurser
  - `hasMany(User)` - En tenant kan ha flere brukere
- **Rolle:** Representerer en kunde/bedrift med egen bookingside (/{slug})

#### 2. **Plan Model** (`app/Models/Plan.php`)
- **Fillable:** name, description, features
- **Casts:** features → array (JSON)
- **Relasjoner:**
  - `hasMany(Subscription)` - En plan kan ha flere subscriptions
- **Rolle:** Definerer abonnementsplaner som tenants kan abonnere på

#### 3. **Subscription Model** (`app/Models/Subscription.php`)
- **Fillable:** tenant_id, plan_id, active, active_from, active_to
- **Casts:** 
  - active → boolean
  - active_from → datetime
  - active_to → datetime
- **Relasjoner:**
  - `belongsTo(Tenant)` - Subscription tilhører en tenant
  - `belongsTo(Plan)` - Subscription tilhører en plan
- **Rolle:** Kobler tenants til plans og håndterer aktiv status for tilgangskontroll

#### 4. **Resource Model** (`app/Models/Resource.php`)
- **Fillable:** tenant_id, name, description, type, capacity, active
- **Casts:** 
  - active → boolean
  - capacity → integer
- **Relasjoner:**
  - `belongsTo(Tenant)` - Resource tilhører en tenant
  - `hasMany(ResourceAvailability)` - En ressurs har flere åpningstider
  - `hasMany(Booking)` - En ressurs kan ha flere bookinger
- **Rolle:** Representerer bookbare ressurser (hytter, stoler, rom, etc.)

#### 5. **ResourceAvailability Model** (`app/Models/ResourceAvailability.php`)
- **Fillable:** resource_id, day_of_week, start_time, end_time
- **Casts:** day_of_week → integer
- **Relasjoner:**
  - `belongsTo(Resource)` - Availability tilhører en ressurs
- **Rolle:** Definerer åpningstider per ukedag (0=Sunday, 6=Saturday)

#### 6. **Booking Model** (`app/Models/Booking.php`)
- **Fillable:** resource_id, customer_name, customer_email, customer_phone, booking_date, start_time, end_time, notes, status
- **Casts:** booking_date → date
- **Relasjoner:**
  - `belongsTo(Resource)` - Booking tilhører en ressurs
- **Rolle:** Representerer bookinger med kunde-info og status (pending/confirmed/cancelled)

#### 7. **User Model** (`app/Models/User.php`)
- **Fillable:** name, email, password, tenant_id, role
- **Hidden:** password, remember_token
- **Casts:** email_verified_at → datetime, password → hashed
- **Relasjoner:**
  - `belongsTo(Tenant)` - User tilhører en tenant (nullable for admin)
- **Rolle:** Representerer brukere med rolle (admin/tenant_admin)

### Relasjonsoversikt
```
Tenant (1) ──┬─> (N) Subscriptions ──> (1) Plan
             ├─> (N) Resources ──┬─> (N) ResourceAvailabilities
             │                   └─> (N) Bookings
             └─> (N) Users
```

### Dokumentasjon
Alle filer har:
- ✅ **Header:** `// File: app/Models/ModelName.php`
- ✅ **PHPDoc:** Beskrivelse av modellens rolle
- ✅ **Footer:** Norsk kommentar som forklarer modellens funksjon

### Verifisering
```bash
php artisan tinker
>>> App\Models\Tenant::count()  # ✅ Fungerer
>>> App\Models\Plan::count()    # ✅ Fungerer
>>> $tenant = App\Models\Tenant::first()
>>> $tenant->subscriptions      # ✅ Relasjon fungerer
>>> $tenant->resources          # ✅ Relasjon fungerer
>>> $tenant->users              # ✅ Relasjon fungerer
```

### Betydning
Med disse modellene på plass kan vi nå:
- Opprette og administrere tenants med subscriptions
- Håndtere ressurser med åpningstider
- Lagre og administrere bookinger
- Implementere tenant-isolasjon gjennom relasjoner
- Bruke Eloquent for type-safe database-operasjoner

---

### Neste steg
- Fase 2: Seed Data og Testing
- Task 2.1: Database seeder for plans

---

**Tid brukt:** ~10-12 timer
**Sist oppdatert:** 1. desember 2025
