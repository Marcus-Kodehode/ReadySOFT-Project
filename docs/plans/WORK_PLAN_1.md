# Arbeidsplan - Multi-tenant Bookingportal

## 📋 Prosjektoversikt

**Varighet:** 2 uker (10 arbeidsdager)  
**Teknologi:** Laravel 12, Breeze, Alpine.js, Tailwind CSS, MySQL
**Mål:** Fungerende multi-tenant bookingportal med SMS-integrasjon

---

## 🎯 Hovedleveranser

- ✅ Multi-tenant struktur med abonnementsystem
- ✅ Booking-objekter med kalender/tilgjengelighet
- ✅ Teletopia SMS-integrasjon
- ✅ Rollebasert tilgang (admin/kunde)
- ✅ Landingsside med kundeliste og slug-baserte undersider

---

## 📅 UKE 1: Grunnstruktur og Multi-tenancy

### **Dag 1 - Mandag: Oppstart og miljøoppsett**

**Mål:** Få prosjektet opp å kjøre

#### Oppgaver
- [ ] Introduksjon til multi-tenant konseptet
- [ ] Installer Laravel 12 med Breeze
- [ ] Sett opp Tailwind CSS og Alpine.js
- [ ] Opprett Git-repository og første branch
- [ ] Test at autentisering fungerer

#### AI-assistanse
- Be om sjekkliste for Laravel-oppsett
- Få forslag til mappestruktur for multi-tenant
- Generer .gitignore og README-mal

#### Leveranse
✓ Fungerende Laravel-installasjon med login/registrering

---

### **Dag 2 - Tirsdag: Database-modellering**

**Mål:** Lage fundament for multi-tenancy

#### Oppgaver
- [ ] Design database-skjema for:
  - `tenants` (id, navn, slug, virksomhetstype, aktiv_status)
  - `plans` (id, navn, beskrivelse, funksjoner)
  - `subscriptions` (id, tenant_id, plan_id, active, active_from, active_to)
- [ ] Lag migrasjoner for alle tabeller
- [ ] Opprett Eloquent-modeller med relasjoner
- [ ] Test relasjonene i tinker

#### AI-assistanse
- Generer migrasjonsfiler
- Få forslag til fornuftige felttyper og indexes
- Hjelp med Eloquent-relasjoner (hasOne, belongsTo, etc.)

#### Leveranse
✓ Komplette migrasjoner og modeller for tenant-struktur

---

### **Dag 3 - Onsdag: Registrering med tenant-opprettelse**

**Mål:** Utvide Breeze til å støtte multi-tenancy

#### Oppgaver
- [ ] Utvid registreringsskjema med:
  - Virksomhetstype (dropdown)
  - Ønsket slug/domenenavn
  - Valg av abonnementsplan
- [ ] Modifiser `RegisterController` til å:
  - Opprette bruker
  - Opprette tilhørende tenant
  - Opprette subscription (sett som aktiv)
  - Generere unik slug
- [ ] Legg til validering (unikt slug, etc.)
- [ ] Test full registreringsflyt

#### AI-assistanse
- Hjelp med å utvide Breeze-controllere
- Forslag til slug-generering (Str::slug)
- Valideringsregler for alle felter

#### Leveranse
✓ Fungerende registrering som oppretter tenant + subscription

---

### **Dag 4 - Torsdag: Middleware og roller**

**Mål:** Sikre tilgangskontroll

#### Oppgaver
- [ ] Lag `CheckActiveSubscription` middleware som:
  - Sjekker om bruker har tenant
  - Verifiserer at subscription er aktiv
  - Redirecter til "Ingen aktiv plan" hvis inaktiv
- [ ] Legg til roller på users-tabellen (eller egen roles-tabell)
  - `admin` (systemadministrator)
  - `tenant_admin` (kunde-admin)
- [ ] Registrer middleware i Kernel
- [ ] Test tilgangskontroll

#### AI-assistanse
- Generer middleware-eksempel
- Forslag til enkel RBAC-struktur
- Hjelp med Gates/Policies hvis ønskelig

#### Leveranse
✓ Fungerende middleware som beskytter booking-funksjoner

---

### **Dag 5 - Fredag: Dashboard-grensesnitt**

**Mål:** Lage oversikt for brukere og admin

#### Oppgaver
- [ ] **Kunde-dashboard** (`/dashboard`):
  - Vis tenant-info (navn, virksomhetstype, slug)
  - Vis abonnementsdetaljer
  - Navigasjon til ressurs-administrasjon
  - SMS-innstillinger (kommer senere)
- [ ] **Admin-dashboard** (`/admin`):
  - Liste alle tenants i tabell
  - Vis abonnementsstatus per tenant
  - Mulighet til å aktivere/deaktivere tenants
- [ ] Style med Tailwind (rene, profesjonelle cards)

#### AI-assistanse
- Få forslag til Tailwind-layouts
- Generer Blade-komponenter (cards, tabeller)
- Hjelp med responsivt design

#### Leveranse
✓ To funksjonelle dashboards med god oversikt

---

## 📅 UKE 2: Booking, SMS og Ferdigstilling

### **Dag 6 - Mandag: Booking-objekter database**

**Mål:** Modellere ressurser og tilgjengelighet

#### Oppgaver
- [ ] Lag tabeller:
  - `resources` (id, tenant_id, navn, beskrivelse, type, kapasitet)
  - `resource_availabilities` (id, resource_id, dag, fra_tid, til_tid)
  - `bookings` (id, resource_id, kunde_navn, kunde_epost, dato, fra_tid, til_tid)
- [ ] Opprett modeller med relasjoner
- [ ] Test relasjoner i tinker

#### AI-assistanse
- Forslag til enkel, men utvidbar modell for tilgjengelighet
- Hjelp med Eloquent-scopes (f.eks. `available()`)
- Generer seed-data for testing

#### Leveranse
✓ Komplett database-struktur for bookingsystem

---

### **Dag 7 - Tirsdag: CRUD for ressurser**

**Mål:** La tenants administrere sine booking-objekter

#### Oppgaver
- [ ] Lag ressurs-administrasjon (`/dashboard/resources`):
  - Liste alle ressurser
  - Opprett ny ressurs (form)
  - Rediger eksisterende ressurs
  - Slett ressurs
- [ ] Implementer enkel tilgjengelighet:
  - Definér åpningstider per dag
  - Eller enkleste versjon: fast tid 08:00-16:00
- [ ] Valider at ressurs tilhører riktig tenant

#### AI-assistanse
- Generer CRUD-controller med Resource Controller pattern
- Hjelp med Blade-forms og Tailwind-styling
- Forslag til kalender/tid-velger (Alpine.js eller enkel select)

#### Leveranse
✓ Fungerende ressurs-administrasjon med tilgjengelighet

---

### **Dag 8 - Onsdag: Offentlig bookingside (slug)**

**Mål:** La eksterne kunder booke via slug-URL

#### Oppgaver
- [ ] Opprett offentlig rute `/{slug}`:
  - Slå opp tenant via slug
  - Håndter "ikke funnet" hvis slug ikke eksisterer
- [ ] Vis tenant-info og alle tilgjengelige ressurser
- [ ] Lag bookingskjema:
  - Velg ressurs (dropdown)
  - Velg dato (date picker)
  - Velg tid basert på tilgjengelighet
  - Input: kunde navn, epost, telefon
- [ ] Valider mot dobbeltbooking
- [ ] Lagre booking i database
- [ ] Vis bekreftelsesside

#### AI-assistanse
- Generer routing og controller-logikk
- Hjelp med konflikt-sjekk for bookinger
- Forslag til brukervennlig datepicker (Alpine.js)

#### Leveranse
✓ Fungerende offentlig bookingside per tenant

---

### **Dag 9 - Torsdag: SMS-integrasjon**

**Mål:** Koble til Teletopia SMS API

#### Oppgaver
- [ ] Lag tabell `sms_settings`:
  - tenant_id
  - api_key (encrypted)
  - andre innstillinger
- [ ] Admin-grensesnitt for SMS:
  - Form for å lagre API-nøkkel
  - Test-SMS funksjon (input: telefonnummer)
- [ ] Opprett `TeletopiaSmsService`:
  - Hent API-nøkkel fra database
  - Send SMS via Teletopia API
  - Håndter feil og returner status
- [ ] Test SMS-sending fra admin-panel

#### AI-assistanse
- Generer service-klasse med error-handling
- Hjelp med API-dokumentasjon tolkning
- Forslag til kryptering av API-nøkkel i database

#### Leveranse
✓ Fungerende SMS-integrasjon med test-funksjon

---

### **Dag 10 - Fredag: Landingsside og ferdigstilling**

**Mål:** Fullføre alle komponenter og forberede demo

#### Oppgaver
- [ ] **Landingsside** (`/`):
  - Forklarende tekst om tjenesten
  - Liste over alle aktive tenants
  - Lenker til hver tenant via `/{slug}`
  - Eventuell søk/filter (hvis tid)
- [ ] **Opprydding**:
  - Rydd i routes/web.php
  - Legg til kommentarer i kompleks kode
  - Sjekk at alle views er konsistente
- [ ] **Testing**:
  - Test full brukerflyt fra A-Å
  - Test alle roller og tilganger
  - Test edge cases (ugyldig slug, dobbelbook, etc.)
- [ ] **Dokumentasjon**:
  - Oppdater README med setup-instruksjoner
  - Beskriv hovedfunksjoner
  - Legg til skjermbilder (hvis tid)
- [ ] **Demo-forberedelse**:
  - Lag demo-script/manus
  - Seed eksempel-data
  - Test full demo-flyt

#### AI-assistanse
- Generer README-struktur
- Hjelp med demo-manus (punktvis)
- Forslag til test-cases

#### Leveranse
✓ Komplett, fungerende prototype klar til presentasjon

---

## ✅ Daglige rutiner

### Hver morgen:
1. 🎯 Gjennomgå dagens mål
2. 📋 Bryt ned oppgaver i små steg
3. 🤖 Klargjør AI-prompts du trenger

### I løpet av dagen:
- ✍️ Commit ofte med beskrivende meldinger
- 🧪 Test kontinuerlig (ikke vent til slutten)
- 💬 Notér spørsmål til veileder
- 📝 Dokumenter mens du koder

### Hver kveld:
- ✅ Sjekk av hva som er ferdig
- 🔍 Identifiser blokkere for neste dag
- 💾 Push kode til Git

---

## 🤖 Effektiv bruk av AI

### Bruk AI til:
- ✅ Genere boilerplate-kode (migrasjoner, modeller, controllers)
- ✅ Forklare konsepter (multi-tenancy, middleware, policies)
- ✅ Foreslå best practices
- ✅ Finne feil i kode
- ✅ Skrive tests og dokumentasjon

### AI skal IKKE:
- ❌ Erstatte din forståelse
- ❌ Brukes uten å lese output
- ❌ Kopiere kode blindt uten testing

### Alltid:
1. Les AI-generert kode nøye
2. Forstå hva koden gjør
3. Test funksjonaliteten
4. Tilpass til prosjektets stil

---

## 🎯 Suksesskriterier

### Minimum fungerende produkt (MVP):
- [x] Registrering oppretter tenant + subscription
- [x] Middleware blokkerer inaktive abonnement
- [x] Tenants kan lage ressurser
- [x] Offentlig bookingside fungerer (`/{slug}`)
- [x] SMS kan sendes fra admin
- [x] Landingsside viser alle tenants

### Pluss hvis tid:
- [ ] Booking-bekreftelse via SMS automatisk
- [ ] Søk/filter på landingsside
- [ ] Kalender-visning av bookinger
- [ ] Email-varsling ved booking

---

## 🚨 Viktige påminnelser

1. **Sikkerhet:**
   - Aldri hardkod API-nøkler
   - Valider all input
   - Sjekk tenant-tilhørighet i alle queries

2. **Multi-tenancy:**
   - Alltid filtrer på `tenant_id`
   - Test at tenants ikke ser hverandres data

3. **Git:**
   - Commit minst 2-3 ganger per dag
   - Beskrivende commit-meldinger
   - Ikke commit .env eller hemmeligheter

4. **AI:**
   - Bruk som verktøy, ikke orakel
   - Dobbelsjekk sikkerhetskritisk kode
   - Spør veileder ved tvil

---

## 📞 Når du står fast

1. Prøv å løse selv i 15-20 min
2. Spør AI om alternative tilnærminger
3. Google feilmeldinger
4. Kontakt veileder med:
   - Hva du prøvde
   - Hva som skjedde
   - Hva du forventet

---

**Lykke til! 🚀 Du har dette!**