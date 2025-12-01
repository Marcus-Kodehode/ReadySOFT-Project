# Task 1 - Database Foundation

## Oversikt
Fase 1 etablerer databasestrukturen for multi-tenant systemet. Dette er grunnmuren for hele applikasjonen.

---

## Task 1.1: Database-migrasjoner for core tabeller ✅

**Status:** Fullført  
**Tid brukt:** ~15 min (verifisering)

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
**Tid brukt:** ~20 min (verifisering)

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

### Neste steg
- Task 1.3: Utvide users tabell med tenant_id og role
- Task 1.4: Eloquent modeller med relasjoner

---

**Sist oppdatert:** 1. desember 2025
