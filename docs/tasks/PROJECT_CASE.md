# 📋 ReadySoft Project Case

**Multi-Tenant Booking Portal – Prosjektspecifikasjon**

---

## 1. Prosjektbeskrivelse

### Formål
Utvikle en enkel, men skalerbar **multi-tenant bookingportal** basert på moderne web-teknologi.

### Hva systemet gjør:

**For Kunder (Tenants):**
- ✔ Registrerer seg og velger virksomhetstype (hytteutleie, frisør, etc.)
- ✔ Får sin egen underside med custom slug (f.eks. `/salong-rosa`)
- ✔ Definerer booking-objekter (hytter, frisørstoler, behandlere, rom, osv.)
- ✔ Setter opp tilgjengelighet i kalender
- ✔ Får eget dashboard for administrasjon
- ✔ Kan aktivere SMS-integrasjon med Teletopia

**For Sluttbrukere:**
- ✔ Besøker kundens slug-side
- ✔ Ser tilgjengelige ressurser
- ✔ Booker direkte via enkelt skjema
- ✔ Får bekreftelse på booking

**For System-Admin:**
- ✔ Oversikt over alle registrerte kunder
- ✔ Administrering av abonnement
- ✔ Systeminnstillinger

### Rolle i Jobbloop
Kandidaten er **juniorutvikler under Jobbloop** med faglig veiledning og skal aktivt bruke AI-verktøy til planlegging, kodeforslag, dokumentasjon og feilsøking – men alltid forstå, kvalitetssikre og teste det som leveres.

---

## 2. Tech Stack

### Backend
- **Laravel 12**
  - Auth, routing, Eloquent ORM, migrasjoner
  - Middleware, policies, service-klasser
  - Multi-tenant-støtte via `tenant_id`-felt (ikke avansert SaaS-framework denne gangen)

### Frontend & Styling
- **Laravel Breeze** – Grunnleggende pålogging/registrering
- **Alpine.js** – Lettvekts interaktivitet (skjemaer, modaler, kalender-interaksjon)
- **Tailwind CSS** – Styling av alle grensesnitt

### Database
- **MySQL 8.0**
- All config og innstillinger som brukerne kan påvirke lagres i databasen
- Ingen hardkoding av per-tenant-data i `.env`

---

## 3. Hovedleveranser

### 3.1. Multi-Tenant Struktur og Abonnement

#### Modellering
- **Tenants-tabell:**
  - navn, virksomhetstype, slug, active-status
  - Relasjon til users (tenant_id på bruker eller pivot-tabell)

- **Plans-tabell:**
  - Standard abonnementstyper

- **Subscriptions-tabell:**
  - Knytter tenant til plan
  - aktiv-flag/datoer

#### Middleware
- Sjekker at innlogget bruker tilhører en tenant med aktivt abonnement
- Redirecte/vis beskjed hvis ikke aktiv

#### Registreringsflyt
1. Opprette bruker
2. Opprette tenant
3. Velge plan
4. Aktivere tjenesten (subscription.active = true)

---

### 3.2. Booking-Objekter og Kalender

#### Ressurser
- **resources-tabell:**
  - navn, beskrivelse, kapasitet, type
  - tenant_id (hver tenant har egne ressurser)

#### Tilgjengelighet
- **resource_availabilities-tabell:**
  - dato, fra–til, eventuelt mønster (daglig/ukentlig)
  - Alternativt: enkelt oppsett (f.eks. alle dager 08–16)

#### Bookings
- **bookings-tabell:**
  - Knyttet til resource, dato/tid
  - Sluttbruker-informasjon (navn, epost, etc.)

#### Kunde-Underside (Slug)
- Offentlig rute: `/{slug}`
- Viser oversikt over ressursene
- Booking-skjema der eksterne kunder kan booke

---

### 3.3. Teletopia SMS-Integrasjon

#### Database-Innstillinger
- **sms_settings** eller **tenant_settings-tabell:**
  - Teletopia API-nøkkel per tenant
  - Lagres i database, IKKE i `.env`

#### Admin-Funksjon
Tenant-admin må kunne:
- Lagre/endre Teletopia API-nøkkel
- Sende test-SMS fra admin-grensesnittet
  - Input: telefonnummer
  - Output: tilbakemelding om SMS sendt/feilet

#### SMS-Service
- Egen service-klasse: `TeletopiaSmsService`
- Håndterer API-kall mot Teletopia
- Klar til framtidige funksjoner (booking-bekreftelser)

---

### 3.4. Rollebasert Tilgang og Dashboards

#### Roller
- **admin** – Systemadministrator (oversikt over alle tenants)
- **customer / tenant_admin** – Kundeadmin hos hver tenant
- *Evt. underroller for ansatte hos kundens på sikt*

#### Kunde-Dashboard
- Oversikt over abonnement
- Status på konto
- Antall registrerte booking-objekter
- Snarveier til administrasjon:
  - Ressurser
  - Tilgjengelighet
  - SMS-innstillinger

#### System-Admin-Dashboard
- Liste over alle tenants
- Oversikt over abonnement
- Enkle innstillinger pr. tenant (aktiv/deaktiv)

---

### 3.5. Landingsside med Kundeliste

**Landingsside (/):**
- ✔ Presenterer systemet (kort tekst)
- ✔ Viser oversikt over alle registrerte og aktive kunder
- ✔ Viser lenker til hver kundes underside via slug
- ✔ Evt. enkel filtrering/søk (valgfritt hvis tid)

**Eksempel:** `/salong-rosa` – Sluttbruker kan booke direkte hos Rosa sin frisørsalong

---

## 4. 2-Ukers Fremdriftsplan

**Totalt:** 10 arbeidsdager (5 dager per uke)

---

### Uke 1 – Struktur, Registrering, Abonnement og Grunnleggende Multi-Tenancy

#### **Dag 1 – Oppstart, Miljø og Domeneforståelse**

**Oppgaver:**
- Introduksjon til prosjekt, multi-tenant-konsept og ønsket funksjonalitet
- Sette opp Laravel 12 + Breeze + Tailwind + Alpine
- Opprette Git-branch for prosjektet

**AI-bruk:**
- Generere sjekkliste for oppsett
- Forslag til mappestruktur for multi-tenant-løsning

---

#### **Dag 2 – Modellering av Tenants, Brukere og Planer**

**Oppgaver:**
- Designe tabeller og relasjoner for:
  - tenants (kunder)
  - plans
  - subscriptions
- Lage migrasjoner, modeller og Eloquent-relasjoner

**AI-bruk:**
- Generere forslag til migrasjonsfiler og modeller
- Hjelp til å formulere vernuftige felter (slug, active_from, active_to, status)

---

#### **Dag 3 – Registrering og Opprettelse av Tenant + Abonnement**

**Oppgaver:**
- Utvide Breeze-registreringen:
  - Når ny bruker registrerer seg → opprettes en tenant automatisk
  - Velg virksomhetstype og slug
  - Opprett første subscription (aktiv)
- Implementere enkel skjemaflyt for virksomhetstype og plan

**AI-bruk:**
- Hjelp til å utvide Breeze-controllere og views
- Forslag til validering og slug-generering

---

#### **Dag 4 – Middleware for Aktivt Abonnement + Rolle-Oppsett**

**Oppgaver:**
- Implementere middleware:
  - Sjekk om bruker har tenant med aktiv subscription
  - Redirecte/vis beskjed hvis ikke
- Innføre roller:
  - role-felt på users eller egen roles-tabell

**AI-bruk:**
- Generere eksempel-middleware
- Forslag til enkel RBAC-struktur

---

#### **Dag 5 – Dashboards (Kunde og Admin) – Første Versjon**

**Oppgaver:**
- Lage ruter og views for:
  - **Kunde-dashboard:** Vis tenant-info, abonnement, virksomhetstype
  - **Admin-dashboard:** Liste over all tenants og deres abonnement
- Oversiktlig layout med Tailwind

**AI-bruk:**
- Forslag til Tailwind-layout
- Blade-partials for gjenbrukbare komponenter (kort, tabeller)

---

### Uke 2 – Booking-Objekter, Kalender, SMS og Landingsside

#### **Dag 6 – Modellering av Booking-Objekter og Tilgjengelighet**

**Oppgaver:**
- Definere tabeller:
  - resources (booking-objekter, tenant_id)
  - resource_availabilities (tilgjengelighet)
- Lage migrasjoner, modeller og relasjoner

**AI-bruk:**
- Hjelp til å velge enkel, men utvidbar modell
- Forslag til relasjoner og Eloquent-scopes

---

#### **Dag 7 – CRUD for Booking-Objekter og Enkel Kalenderlogikk**

**Oppgaver:**
- Admin-grensesnitt for tenants:
  - Opprett/rediger/slett booking-objekter
- Enkel kalender-/tilgjengelighetslogikk:
  - Definere åpningstider pr. dag
  - Eller: hvilke dager ressursen er tilgjengelig

**AI-bruk:**
- Eksempel på ressurs-admin-UI i Blade + Tailwind
- Hjelp til enkel kalender-/datologikk i kontrollere

---

#### **Dag 8 – Kunde-Underside (Slug) og Booking-Skjema**

**Oppgaver:**
- Offentlig rute: `/{slug}`
  - Slå opp tenant via slug
  - Vis liste over tilknyttede booking-objekter
- Implementere booking-skjema:
  - Sluttbruker velger ressurs og dato/tid
  - Lagre i bookings-tabell
  - Vis bekreftelse

**AI-bruk:**
- Routing- og controller-logikk for slug-baserte sider
- Validering og enkel konflikt-sjekk (ikke dobbeltbook)

---

#### **Dag 9 – Teletopia SMS-Integrasjon og Innstillinger**

**Oppgaver:**
- Lage tabell for sms_settings:
  - tenant_id, API-nøkkel, osv.
- Admin for tenant:
  - Skjema for lagre/oppdatere API-nøkkel
  - Test-SMS-funksjon til valgt nummer
- Implementere TeletopiaSmsService:
  - Les API-nøkkel fra database
  - Utfør API-kall mot Teletopia

**AI-bruk:**
- Forslag til service-klasse og error-håndtering
- Pseudo-kall mot Teletopia (kandidaten legger inn faktiske endpoints)

---

#### **Dag 10 – Landingsside, Opprydding, Tester og Presentasjon**

**Oppgaver:**
- Implementere landingsside (/):
  - Forklaring på tjenesten
  - Liste over aktive tenants med lenker til /{slug}
- Opprydding:
  - Rydde routes, kontrollere, views
  - Kommentere og dokumentere
- Enkle tester:
  - Registrering og tenant-tilknytning
  - Abonnement-middleware
  - Booking-opprettelse
  - Booking via slug-side
  - Test-SMS med Teletopia
- Forberedelse av demo/presentasjon

**AI-bruk:**
- Forslag til README-struktur
- Skisse til demo-manus

---

## 5. Bruk av AI – Forventninger og Retningslinjer

### Kandidaten skal:
- ✔ Bruke AI til:
  - Bryte ned oppgaver i mindre steg
  - Få kodeforslag til migrasjoner, modeller, controllere, views, services
  - Få forklaringer på konsepter
  - Hjelp til dokumentasjon

- ✔ Alltid:
  - Lese gjennom og forstå AI-generert kode
  - Tilpasse kode til prosjektets konvensjoner
  - Teste funksjonalitet før commit

### Veileder skal:
- ✔ Kvalitetssikre kritiske deler (auth, autorisasjon, multi-tenant-isolasjon)
- ✔ Gi tilbakemelding på arkitekturvalg og AI-bruk

---

## 6. Suksesskriterier

### Overordnet
Kandidaten leverer en fungerende prototype på en multi-tenant bookingportal med:
- ✔ Bruker kan registrere seg, opprette tenant og aktivere abonnement
- ✔ Middleware håndhever at kun aktive abonnement har tilgang
- ✔ Tenants kan opprette booking-objekter med tilgjengelighet
- ✔ Sluttbrukere kan gå til kundens slug-side og gjøre booking
- ✔ Teletopia-integrasjon med test-SMS-funksjon
- ✔ Alle innstillinger (inkl. Teletopia API-nøkkel) ligger i databasen

### Spesifikke Suksesskriterier

#### 1. Multi-Tenancy og Abonnement
- tenants, plans, subscriptions er korrekt modellert
- Middleware avviser brukere uten aktivt abonnement

#### 2. Booking og Kalender
- Ressurser kan opprettes pr. tenant
- Tilgjengelighetregler fungerer
- Bookings lagres og vises oversiktlig

#### 3. Teletopia SMS
- API-nøkkel lagres i database
- Test-SMS kan sendes fra admin-UI med tydelig tilbakemelding

#### 4. Rollebasert Tilgang og Dashboards
- Minst to roller med forskjellig tilgangsnivå
- Kunde-dashboard og admin-dashboard er funksjonelle

#### 5. Landingsside og Slug-Baserte Undersider
- Landingssiden viser liste over tenants med lenker
- Sluttbruker kan gå til /{slug}, se ressurser og gjøre booking

#### 6. Læring og AI-Bruk
- Kandidaten viser forståelse for multi-tenant-prinsipper
- Kan peke på situasjoner der AI hjalp og der det måtte korrigeres

---

**Status:** Under utvikling 🚀

Last updated: November 28, 2025
