# Requirements - ReadySoft Booking Portal

## Prosjektoversikt

ReadySoft er en multi-tenant bookingportal hvor hver kunde får sin egen underside (/{slug}) for å motta bookinger. Systemet skal være brukervennlig for alle aldre, raskt, sikkert og integrert med SMS-varsling.

**Målgruppe:**
- Bedriftskunder (tenants): Hytteutleiere, frisører, behandlere, romutleiere
- Sluttbrukere: Alle som ønsker å booke (18-80+ år)
- System-administratorer: Teknisk personell

---

## Funksjonelle Krav

### FR-1: Brukerregistrering og Tenant-opprettelse

**Prioritet:** Kritisk  
**Bruker:** Ny kunde

**Beskrivelse:**  
Når en ny kunde registrerer seg, skal systemet opprette både en bruker-konto og en tilhørende tenant med subscription i én transaksjon.

**Akseptansekriterier:**
- [x] Registreringsskjema inneholder: name, email, password, business_name, business_type (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE)



- [x] System genererer unik slug basert på business_name (f.eks. "Salong Rosa" → "salong-rosa") (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)



- [x] Bruker kan se preview av slug mens de skriver (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)



- [x] Slug valideres i sanntid (visuell feedback hvis opptatt) (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)



- [ ] Ved submit opprettes: User, Tenant, Subscription (active=true) i én transaksjon (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)
- [ ] Bruker redirectes til dashboard etter vellykket registrering (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)
- [ ] Feilhåndtering: Hvis noe feiler, rulles alt tilbake (ingen delvis data) (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)
- [ ] Prosessen tar maksimalt 2 minutter (PASS PÅ OG JOBB SYKRONISERT MED task.md (TASK 3) LISTEN, TING SKAL FUNGERE SAMMEN OG IKKE DUPLISERES OM DET ER NOE SOM ER GJORT ALLEREDE LEGG TIL KORT AVSNITT I TASK_FR1_SUMMARY.md FILEN OM HVA SOM ER GJORT)

**Validering:**
- Email: Må være gyldig format og unik
- Password: Minimum 8 tegn
- Business name: Påkrevd, 3-255 tegn
- Business type: Må velges fra dropdown
- Slug: Må være unik, kun lowercase, tall og bindestrek

---

### FR-2: Abonnementssystem og Tilgangskontroll

**Prioritet:** Kritisk  
**Bruker:** Tenant, System-admin

**Beskrivelse:**  
Systemet skal ha et abonnementssystem som kontrollerer tilgang til booking-funksjoner. Kun tenants med aktiv subscription kan bruke systemet.

**Akseptansekriterier:**
- [ ] Tabell `plans` inneholder minimum én plan (f.eks. "Basic")
- [ ] Tabell `subscriptions` kobler tenant til plan med active-status
- [ ] Middleware `CheckActiveSubscription` sjekker subscription før tilgang til beskyttede ruter
- [ ] Inaktive brukere redirectes til "Activate Subscription" side
- [ ] Admin kan aktivere/deaktivere subscriptions fra admin-dashboard
- [ ] Subscription-status vises prominent i tenant dashboard
- [ ] Ingen mulighet til å omgå subscription-sjekk

**Teknisk:**
- Middleware registreres i Kernel
- Middleware kjører før alle /dashboard/* ruter
- Database-query optimalisert (eager loading)

---

### FR-3: Ressurs-administrasjon (CRUD)

**Prioritet:** Kritisk  
**Bruker:** Tenant-admin

**Beskrivelse:**  
Tenants skal kunne opprette og administrere booking-ressurser (hytter, stoler, rom, etc.) med tilhørende tilgjengelighet.

**Akseptansekriterier:**
- [ ] Liste-visning viser alle ressurser for innlogget tenant
- [ ] Opprett-skjema: name, description, type, capacity
- [ ] Rediger-skjema: samme felter som opprett
- [ ] Slett-funksjon med bekreftelse (modal)
- [ ] Kan aktivere/deaktivere ressurs uten å slette
- [ ] Tilgjengelighet: Definere åpningstider per ukedag (start_time, end_time)
- [ ] Quick-setup: "Same hours every day" checkbox
- [ ] Validering: Navn må være unikt innenfor tenant
- [ ] Empty state: Hvis ingen ressurser, vis "Create your first resource" melding
- [ ] Opprettelse tar under 30 sekunder

**Teknisk:**
- Global scope sikrer tenant-isolasjon
- Eager loading av availabilities
- Soft deletes (valgfritt)

---

### FR-4: Offentlig Bookingside (/{slug})

**Prioritet:** Kritisk  
**Bruker:** Sluttbruker (ikke innlogget)

**Beskrivelse:**  
Hver tenant skal ha en offentlig tilgjengelig bookingside via deres unike slug hvor kunder kan se tilgjengelige ressurser og gjøre bookinger.

**Akseptansekriterier:**
- [ ] URL /{slug} viser tenant sin bookingside
- [ ] Hvis slug ikke finnes: 404-side med "Tenant not found"
- [ ] Viser tenant-info: name, business_type, description
- [ ] Liste over aktive ressurser med navn og beskrivelse
- [ ] Klikk på ressurs åpner booking-skjema
- [ ] Booking-skjema: velg dato, velg tid, customer_name, customer_email, customer_phone, notes (valgfri)
- [ ] Kalender viser kun ledige datoer (grønne)
- [ ] Tidspunkter vises basert på resource_availabilities
- [ ] Sanntids validering: Sjekk for konflikter før lagring
- [ ] Bekreftelsesside viser: booking_id, ressurs, dato, tid, kunde-info
- [ ] Fullført booking på under 2 minutter
- [ ] Mobiloptimalisert (store touch-targets)

**Validering:**
- Dato: Må være i fremtiden
- Tid: Må være innenfor åpningstider
- Email: Gyldig format
- Phone: Gyldig format (norsk eller internasjonalt)
- Konflikt-sjekk: Ingen overlappende bookinger

**Teknisk:**
- Ingen autentisering påkrevd
- CSRF-beskyttelse på POST
- Rate limiting for å forhindre spam

---

### FR-5: Booking-administrasjon

**Prioritet:** Høy  
**Bruker:** Tenant-admin

**Beskrivelse:**  
Tenants skal kunne se og administrere alle bookinger for sine ressurser.

**Akseptansekriterier:**
- [ ] Liste-visning viser alle bookinger for tenant sine ressurser
- [ ] Sortering: Nyeste først (default)
- [ ] Filtrering: Kommende / Tidligere / Alle
- [ ] Viser: booking_id, resource_name, customer_name, booking_date, start_time, status
- [ ] Kan endre status: pending → confirmed, confirmed → cancelled
- [ ] Kan se detaljer: customer_email, customer_phone, notes
- [ ] Paginering hvis mange bookinger (20 per side)
- [ ] Søk på customer_name eller customer_email

**Teknisk:**
- Eager loading av resource relationship
- Index på resource_id og booking_date

---

### FR-6: Tenant Dashboard

**Prioritet:** Høy  
**Bruker:** Tenant-admin

**Beskrivelse:**  
Dashboard skal gi tenant en rask oversikt over sin virksomhet og quick actions.

**Akseptansekriterier:**
- [ ] Stat cards viser:
  - Antall bookinger i dag
  - Antall bookinger denne uken
  - Antall aktive ressurser
  - Subscription status
- [ ] Liste over neste 5 kommende bookinger
- [ ] Quick actions:
  - "New Resource" knapp
  - "SMS Settings" knapp
  - "Share Booking Page" knapp (kopierer link)
- [ ] Link til full bookingside (/{slug})
- [ ] Velkomstmelding: "Welcome, [Name]!"
- [ ] Laster på under 1 sekund

**Teknisk:**
- Optimaliserte queries (count, limit)
- Caching av statistikk (valgfritt)

---

### FR-7: Admin Dashboard

**Prioritet:** Middels  
**Bruker:** System-admin

**Beskrivelse:**  
System-admin skal kunne overvåke og administrere alle tenants i systemet.

**Akseptansekriterier:**
- [ ] Stat cards viser:
  - Totalt antall tenants
  - Aktive tenants
  - Inaktive tenants
  - Totalt antall bookinger (alle tenants)
- [ ] Tabell over alle tenants:
  - Kolonner: name, slug, business_type, active, created_at
  - Sortérbar på alle kolonner
  - Søk på name eller slug
- [ ] Quick actions per tenant:
  - Toggle active/inactive (inline switch)
  - "View Details" link
- [ ] Filter: Vis kun aktive / inaktive / alle
- [ ] Paginering (20 per side)

**Teknisk:**
- Kun tilgjengelig for users med role='admin'
- Middleware: CheckAdminRole

---

### FR-8: SMS-integrasjon (Teletopia)

**Prioritet:** Høy  
**Bruker:** Tenant-admin

**Beskrivelse:**  
Tenants skal kunne konfigurere SMS-varsling via Teletopia API og teste at det fungerer.

**Akseptansekriterier:**
- [ ] SMS Settings side: /dashboard/sms
- [ ] Form for å lagre API-nøkkel (encrypted i database)
- [ ] API-nøkkel maskeres i UI (••••••••key)
- [ ] Test-SMS funksjon:
  - Input: phone_number
  - Button: "Send Test SMS"
  - Output: Success melding eller feilmelding
- [ ] Test-SMS sendes på under 3 sekunder
- [ ] Logging av test-SMS i database (valgfritt)
- [ ] Hjelpetekst: "Where to find your API key?" med link

**Validering:**
- API-nøkkel: Påkrevd
- Phone number: Gyldig format

**Teknisk:**
- Service class: TeletopiaSmsService
- Error handling: Graceful fail hvis API nede
- Encryption: Laravel's encrypt() helper

---

### FR-9: Landingsside

**Prioritet:** Middels  
**Bruker:** Alle (ikke innlogget)

**Beskrivelse:**  
Offentlig forside som viser alle aktive tenants og lar brukere finne og navigere til booking-sider.

**Akseptansekriterier:**
- [ ] Hero-seksjon:
  - Overskrift: "Book Your Next Experience"
  - Beskrivelse: Kort om tjenesten
  - CTA: "Get Started" (går til /register)
- [ ] Liste over alle aktive tenants:
  - Card per tenant: name, business_type, description (kort)
  - "Book Now" knapp (går til /{slug})
- [ ] Søkefelt: Filter tenants i sanntid (navn eller type)
- [ ] Filter på business_type (chips/tags)
- [ ] Responsivt grid: 1 col mobil, 2 col tablet, 3 col desktop
- [ ] Laster på under 2 sekunder
- [ ] Footer: About, Contact, Privacy

**Teknisk:**
- Cache tenant list (5 min)
- Lazy loading av bilder (hvis implementert)

---

### FR-10: Rollebasert Tilgang

**Prioritet:** Kritisk  
**Bruker:** System

**Beskrivelse:**  
Systemet skal ha to roller med forskjellige tilgangsnivåer.

**Akseptansekriterier:**
- [ ] Rolle: `admin` (system-administrator)
  - Tilgang til /admin/*
  - Kan se alle tenants
  - Kan aktivere/deaktivere tenants
  - Kan se alle bookinger (read-only)
- [ ] Rolle: `tenant_admin` (kunde-admin)
  - Tilgang til /dashboard/*
  - Kan kun se egne ressurser og bookinger
  - Kan ikke se andre tenants
- [ ] Middleware: CheckAdminRole for /admin/*
- [ ] Middleware: CheckActiveSubscription for /dashboard/*
- [ ] Unauthorized tilgang gir 403 med tydelig melding
- [ ] UI skjuler funksjoner bruker ikke har tilgang til

**Teknisk:**
- Role kolonne i users tabell
- Middleware sjekker role
- Blade directives: @admin, @tenant

---

## Ikke-funksjonelle Krav

### NFR-1: Ytelse

**Akseptansekriterier:**
- [ ] Landingsside laster på < 2 sekunder
- [ ] Dashboard laster på < 1 sekund
- [ ] Bookingside laster på < 1.5 sekunder
- [ ] Ingen merkbar lag ved interaksjon
- [ ] Fungerer på 3G nettverk

**Teknisk:**
- Database indexes på kritiske kolonner
- Eager loading for å unngå N+1
- Asset minification i produksjon

---

### NFR-2: Sikkerhet

**Akseptansekriterier:**
- [ ] CSRF-beskyttelse på alle POST/PUT/DELETE
- [ ] SQL injection-beskyttelse (Eloquent)
- [ ] XSS-beskyttelse (escape output)
- [ ] Rate limiting på offentlige endepunkter
- [ ] Kryptert lagring av API-nøkler
- [ ] Tenant-isolasjon: Kan aldri se andre tenants' data
- [ ] Passord hashet med bcrypt

**Teknisk:**
- Global scopes på modeller
- Policies for resource access
- Environment-baserte secrets

---

### NFR-3: Brukervennlighet

**Akseptansekriterier:**
- [ ] Selvforklarende UI (ingen veiledning nødvendig)
- [ ] Konsistent design på alle sider
- [ ] Tydelige feilmeldinger (ikke teknisk sjargong)
- [ ] Inline validering på skjemaer
- [ ] Toast-meldinger for handlinger
- [ ] Mobiloptimalisert (touch-vennlig)
- [ ] Tastaturnavigasjon fungerer

---

### NFR-4: Tilgjengelighet

**Akseptansekriterier:**
- [ ] WCAG AA kontrast minimum (4.5:1)
- [ ] Alle interaktive elementer tastaturnavigasjonsvennlige
- [ ] Focus states tydelig synlige
- [ ] Alt-tekst på alle bilder
- [ ] Aria-labels på ikoner uten tekst
- [ ] Skjermleser-vennlige labels på skjemaer

---

### NFR-5: Responsivt Design

**Akseptansekriterier:**
- [ ] Fungerer perfekt på mobil (320px - 640px)
- [ ] Fungerer perfekt på tablet (641px - 1024px)
- [ ] Fungerer perfekt på desktop (1025px+)
- [ ] Touch-vennlig på mobil (min 44x44px targets)
- [ ] Ingen horisontal scroll nødvendig
- [ ] Lesbar tekst på alle skjermstørrelser (min 16px)

---

## Brukerreiser

### Journey 1: Ny kunde registrerer seg og oppretter første ressurs

**Mål:** Få første ressurs oppe og kunne motta bookinger

**Steg:**
1. Besøker landingsside (/)
2. Klikker "Get Started"
3. Fyller inn registreringsskjema
4. Ser preview av slug
5. Submitter skjema
6. Redirectes til dashboard
7. Ser "Create your first resource" melding
8. Klikker "New Resource"
9. Fyller inn ressurs-info og åpningstider
10. Submitter
11. Ser ressurs i liste
12. Klikker "Share Booking Page"
13. Kopierer link

**Forventet tid:** 5-6 minutter  
**Suksesskriterium:** 80% fullfører uten å forlate

---

### Journey 2: Sluttbruker booker time

**Mål:** Gjennomføre booking på under 2 minutter

**Steg:**
1. Mottar link eller finner via landingsside
2. Klikker seg inn på /{slug}
3. Ser oversikt over ressurser
4. Klikker på ønsket ressurs
5. Velger dato fra kalender
6. Velger tid fra tilgjengelige slots
7. Fyller inn: navn, epost, telefon
8. Submitter
9. Ser bekreftelsesside med detaljer

**Forventet tid:** Under 2 minutter  
**Suksesskriterium:** 90% som starter booking fullfører

---

### Journey 3: Admin overvåker system

**Mål:** Sjekke status på alle tenants

**Steg:**
1. Logger inn som admin
2. Ser admin dashboard
3. Ser totalt antall tenants og bookinger
4. Sorterer tenants etter registreringsdato
5. Ser hvilke som er inaktive
6. Klikker toggle for å aktivere inaktiv tenant
7. Ser bekreftelse

**Forventet tid:** 2 minutter  
**Suksesskriterium:** Alle handlinger fungerer uten feil

---

## Testscenarier

### TS-1: Registrering og tenant-opprettelse

**Steg:**
1. Gå til /register
2. Fyll inn alle felter
3. Submit

**Forventet:**
- User opprettes i `users`
- Tenant opprettes i `tenants` med unik slug
- Subscription opprettes og settes til active
- Bruker redirectes til /dashboard
- Toast: "Welcome! Let's get started"

**Feilscenarier:**
- Slug allerede tatt → Feilmelding med forslag
- Email allerede brukt → Feilmelding
- Feil i prosess → Rollback (ingen data lagres)

---

### TS-2: Booking-flyt

**Steg:**
1. Gå til /{slug}
2. Klikk på ressurs
3. Velg ledig dato
4. Velg ledig tid
5. Fyll inn kontaktinfo
6. Submit

**Forventet:**
- Booking lagres i `bookings`
- Bekreftelsesside vises
- Kalender oppdateres (tiden er nå opptatt)

**Feilscenarier:**
- Dobbeltbooking → Feilmelding: "This time is no longer available"
- Ugyldig email → Inline validering stopper submit
- Dato i fortiden → Validering stopper submit

---

### TS-3: Middleware-beskyttelse

**Steg:**
1. Opprett bruker med inaktiv subscription
2. Logg inn
3. Prøv å gå til /dashboard/resources

**Forventet:**
- Middleware blokkerer tilgang
- Redirect til "Activate Subscription" side
- Tydelig melding om hvorfor

**Feilscenarier:**
- Kan ikke omgå med direkte URL
- Ingen database-queries kjøres før sjekk

---

### TS-4: Tenant-isolasjon

**Steg:**
1. Opprett to tenants (A og B)
2. Logg inn som tenant A
3. Prøv å aksessere tenant B sine ressurser (via URL manipulation)

**Forventet:**
- 403 Forbidden eller 404 Not Found
- Ingen data fra tenant B vises
- Logging av forsøk (valgfritt)

---

### TS-5: SMS-test

**Steg:**
1. Gå til /dashboard/sms
2. Legg inn API-nøkkel
3. Legg inn telefonnummer
4. Klikk "Send Test SMS"

**Forventet:**
- SMS sendes på under 3 sekunder
- Success melding vises
- API-nøkkel lagres encrypted

**Feilscenarier:**
- Ugyldig API-nøkkel → Feilmelding fra API
- Ugyldig telefonnummer → Validering stopper submit
- API nede → Graceful error melding

---

## Akseptansekriterier for MVP

En feature er ferdig når:

- [ ] **Funksjonalitet**
  - Oppfyller alle krav i spesifikasjonen
  - Fungerer som beskrevet i user journey
  - Håndterer feilscenarier gracefully

- [ ] **Kode-kvalitet**
  - Følger Laravel beste praksis
  - Ingen hardkodede verdier
  - Kommentarer på norsk for kompleks logikk
  - Konsistente navnekonvensjoner

- [ ] **Design**
  - Følger design guide
  - Konsistent med resten av systemet
  - Responsivt på alle skjermstørrelser
  - Brukersynlig tekst på engelsk

- [ ] **Testing**
  - Manuelt testet på mobil og desktop
  - Alle edge cases testet
  - Feilscenarier håndteres korrekt

- [ ] **Dokumentasjon**
  - Fil-header med navn og plassering
  - Fil-footer med beskrivelse
  - Inline kommentarer på kompleks kode

---

## MVP Scope

### MUST HAVE (MVP)
✅ Multi-tenant registrering  
✅ Abonnement-middleware  
✅ Ressurs CRUD  
✅ Offentlig bookingside  
✅ Booking-prosess  
✅ Tenant-dashboard  
✅ Admin-dashboard (basis)  
✅ SMS test-funksjon  
✅ Landingsside  

### POST-MVP
⚡ Automatisk SMS ved booking  
⚡ Avansert kalender med drag-drop  
⚡ E-post-notifikasjoner  
⚡ Booking-statistikk og grafer  
⚡ Export til CSV  

### FUTURE
💡 Multi-språk støtte (i18n)  
💡 Stripe/Vipps betaling  
💡 Kalenderintegrasjon (Google Calendar)  
💡 Kunde-reviews og rating  

---

**Version:** 1.0  
**Last Updated:** December 2025  
**Status:** Ready for Implementation
