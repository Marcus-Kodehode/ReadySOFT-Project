# Kravspesifikasjon - Multi-tenant Bookingportal

## 📌 Prosjektsammendrag

En moderne, brukervennlig multi-tenant bookingportal hvor hver kunde får sin egen underside for å motta bookinger. Systemet skal være intuitivt nok for alle aldre og tekniske nivåer, samtidig som det leverer profesjonelle funksjoner og engasjerende brukeropplevelser.

**Målgruppe:**
- **Bedriftskunder (tenants):** Hytteutleiere, frisører, behandlere, romutleiere
- **Sluttbrukere:** Alle som ønsker å booke - fra 18 til 80+ år, alle tekniske nivåer
- **System-administratorer:** Teknisk personell som administrerer plattformen

---

## 🎯 Overordnede prinsipper

### Brukervennlighet
- ✅ **Selvforklarende** - Brukeren skal forstå hva de skal gjøre uten veiledning
- ✅ **Rask å lære** - Nye brukere skal kunne gjøre en booking på under 2 minutter
- ✅ **Tilgivende** - Tydelige feilmeldinger og mulighet til å angre handlinger
- ✅ **Tilgjengelig** - WCAG AA standard minimum, fungerer med skjermlesere

### Ytelse
- ✅ **Rask lasting** - Første side lastes på under 2 sekunder
- ✅ **Responsivt** - Umiddelbar tilbakemelding på alle handlinger
- ✅ **Optimalisert** - Fungerer på langsomme mobilnett

### Konsistens
- ✅ **Ensartet design** - Same look & feel på alle sider
- ✅ **Forutsigbar oppførsel** - Like elementer oppfører seg likt
- ✅ **Konsistent språk** - Samme terminologi overalt

---

## ⚠️ Anti-mønstre å unngå (Typiske AI-feil)

### ❌ UNNGÅ DISSE:

**1. Overflødig tekst og forklaringer**
- ❌ Ikke: "Vennligst klikk på knappen nedenfor for å fortsette med registreringen"
- ✅ Gjør: Knapp med "Neste" - selvforklarende

**2. Generiske placeholder-tekster**
- ❌ Ikke: "Lorem ipsum dolor sit amet..."
- ✅ Gjør: Realistisk innhold som "Hytte Solstrand - Ledig hele sommeren"

**3. Over-teknisk språk**
- ❌ Ikke: "Autentiseringsfeil ved validering av credentials"
- ✅ Gjør: "Feil brukernavn eller passord"

**4. Unødvendige modaler og bekreftelser**
- ❌ Ikke: Modal for hver eneste handling
- ✅ Gjør: Inline bekreftelser, toast-meldinger

**5. Komplekse navigasjonsstrukturer**
- ❌ Ikke: Dype menyer med 4-5 nivåer
- ✅ Gjør: Flat struktur med maks 2-3 klikk til alt

**6. Overdreven animasjon**
- ❌ Ikke: Alt fader, spinner, slider inn/ut
- ✅ Gjør: Subtile, meningsfulle overganger (150-200ms)

**7. Skjulte funksjoner**
- ❌ Ikke: Viktige funksjoner bak hover-menyer eller ukjente ikoner
- ✅ Gjør: Tydelige knapper med tekst, ikoner som støtte

**8. Inkonsistent spacing og alignment**
- ❌ Ikke: Tilfeldig padding, ulike marginer på like elementer
- ✅ Gjør: Følg 4px/8px grid-system konsekvent

---

## 🎨 "Wow-faktorer" (Engasjerende elementer)

### 1. **Sanntids tilgjengelighetsvisning**
- 📅 Interaktiv kalender som farges basert på tilgjengelighet
- 🟢 Grønn = ledig, 🟡 Gul = få plasser, 🔴 Rød = fullbooket
- Oppdateres live når andre booker (via polling eller websockets)

### 2. **Smooth mikro-animasjoner**
- ✨ Kort "success confetti" når booking fullføres
- 📲 Skaleeffekt når nye bookinger kommer inn (dashboard)
- 🔄 Smooth overganger mellom views (fade + slide)

### 3. **Personalisert velkomst**
- 👋 Første gang bruker logger inn: "Velkommen! La oss sette opp din første ressurs"
- 📊 Dashboard som viser relevant data for akkurat deres virksomhetstype
- 🎯 Kontekstuelle tips basert på hvor langt de har kommet

### 4. **Visuell progresjon**
- 📈 Onboarding-bar som viser "2 av 4 steg fullført"
- ✅ Checkliste: "Opprett ressurs ✓, Send test-SMS ✓, Motta første booking..."
- 🏆 Feire milepæler: "Din 10. booking! 🎉"

### 5. **Smart søk og filtrering**
- 🔍 Landingsside med instant search (søk mens du skriver)
- 🏷️ Filter på virksomhetstype med visuell feedback
- 📍 Eventuel lokasjonssøk med kart-visning

### 6. **Smarte standarder**
- 🤖 Foreslå populære åpningstider basert på virksomhetstype
- 📝 Auto-generering av slug basert på firmanavn
- ⚡ Quick actions: "Kopier bookinglink" med ett klikk

### 7. **Live preview**
- 👁️ Se hvordan din bookingside ser ut mens du redigerer
- 📱 Device preview: Se mobil/tablet/desktop side om side
- 🎨 Eventuell fargevalg for egen bookingside

### 8. **Delbare elementer**
- 🔗 "Del din bookingside" med ferdig tekst og QR-kode
- 📸 Generer sosiale medier-kort for deling
- 📋 Kopier bookinglink med ett klikk + "Kopiert!"-animasjon

---

## 📋 Funksjonelle krav

### F1: Multi-tenant struktur

#### F1.1: Registrering og opprettelse
**Prioritet:** Kritisk | **Bruker:** Ny kunde

**Krav:**
- [ ] Bruker registrerer seg med: e-post, passord, firmanavn
- [ ] Velger virksomhetstype fra dropdown (min 5 typer)
- [ ] System genererer unik slug basert på firmanavn
- [ ] Bruker kan redigere slug før den låses
- [ ] System oppretter tenant + subscription i én transaksjon
- [ ] Subscription settes til aktiv automatisk (ingen betaling fase 1)
- [ ] Bruker redirectes til onboarding-flyt etter registrering

**Suksesskriterier:**
- ✅ Prosessen tar maks 2 minutter
- ✅ Slug valideres i sanntid (visuell feedback hvis opptatt)
- ✅ Feilmeldinger er tydelige og spesifikke
- ✅ Fungerer på mobil uten issues

**UX-detaljer:**
- Viser preview av bookingside-URL mens bruker skriver slug
- Progress bar: "Steg 1 av 3"
- Auto-kompletterer virksomhetstype etter første bokstav

---

#### F1.2: Abonnementssystem
**Prioritet:** Kritisk | **Bruker:** Tenant, System-admin

**Krav:**
- [ ] Tabell for plans med: navn, beskrivelse, features (JSON)
- [ ] Tabell for subscriptions: tenant_id, plan_id, active, active_from, active_to
- [ ] Middleware sjekker subscription.active før tilgang til beskyttede ruter
- [ ] Inaktive brukere redirectes til "Aktiver abonnement"-side
- [ ] Admin kan aktivere/deaktivere subscriptions fra admin-dashboard

**Suksesskriterier:**
- ✅ Middleware blokkerer tilgang konsekvent
- ✅ Ingen mulighet til å omgå subscription-sjekk
- ✅ Tydelig melding til bruker hvis inaktiv

**UX-detaljer:**
- Vis abonnementsstatus prominent i dashboard
- Countdown hvis abonnement utløper snart: "14 dager igjen"
- Call-to-action hvis inaktiv: "Aktiver abonnement for å fortsette"

---

### F2: Booking-objekter og kalender

#### F2.1: Ressurs-administrasjon
**Prioritet:** Kritisk | **Bruker:** Tenant-admin

**Krav:**
- [ ] CRUD for ressurser: navn, beskrivelse, type, kapasitet
- [ ] Hver ressurs tilhører én tenant (tenant_id)
- [ ] Kan laste opp bilde for ressurs (valgfritt)
- [ ] Sorterbar liste over ressurser (drag & drop rekkefølge)
- [ ] Kan aktivere/deaktivere ressurs uten å slette

**Suksesskriterier:**
- ✅ Opprettelse tar under 30 sekunder
- ✅ Validering hindrer duplikate navn innenfor samme tenant
- ✅ Sletting krever bekreftelse (modal)
- ✅ Liste viser status (aktiv/inaktiv) med badge

**UX-detaljer:**
- Empty state med illustrasjon og "Opprett din første ressurs"-knapp
- Quick actions: "Dupliser ressurs" for like ressurser
- Bulk actions: "Deaktiver valgte" hvis flere ressurser
- Inline editing: Klikk på navn for å redigere direkte i listen

---

#### F2.2: Tilgjengelighet og kalender
**Prioritet:** Kritisk | **Bruker:** Tenant-admin

**Krav:**
- [ ] Definere åpningstider per dag (mandag-søndag)
- [ ] Mulighet for "Samme hver dag" quick-setup
- [ ] Markere spesifikke dager som stengt (helligdager, ferie)
- [ ] Sette minimum/maksimum bookingstid (f.eks. min 30 min, maks 4 timer)
- [ ] Buffer-tid mellom bookinger (valgfritt, f.eks. 15 min opprydding)

**Suksesskriterier:**
- ✅ Visuell kalendervisning (uke eller måned)
- ✅ Farger indikerer tilgjengelighet (grønn/gul/rød)
- ✅ Kan klikke på dag for å endre åpningstider
- ✅ "Smart defaults" basert på virksomhetstype

**UX-detaljer:**
- Toggle: "Kopier fra mandag til alle hverdager"
- Visuell timeline som viser åpningstider (08:00 ---- 16:00)
- Quick presets: "Standard kontortid (08-16)", "Kveldstid (16-22)"
- Advarsel hvis bookinger eksisterer før endring av tilgjengelighet

---

#### F2.3: Booking-prosess (offentlig side)
**Prioritet:** Kritisk | **Bruker:** Sluttbruker (ikke innlogget)

**Krav:**
- [ ] Offentlig tilgjengelig via /{slug}
- [ ] Viser tenant-info: navn, type, kort beskrivelse
- [ ] Liste over tilgjengelige ressurser med bilde
- [ ] Kalendervisning som viser ledige dager
- [ ] Velg ressurs → velg dato → velg tidspunkt (steg-for-steg)
- [ ] Input: navn, e-post, telefon, valgfri beskjed
- [ ] Sanntids validering (sjekk for konflikter)
- [ ] Bekreftelsesside med detaljer og bookingID

**Suksesskriterier:**
- ✅ Fullført booking på under 2 minutter
- ✅ Mobiloptimalisert (stor touch-targets)
- ✅ Visuell feedback ved hvert steg
- ✅ Kan ikke dobbeltbooke samme ressurs/tid
- ✅ Fungerer uten JavaScript (graceful degradation)

**UX-detaljer:**
- Progress indicator: "1 av 3: Velg ressurs"
- Disable tidligere datoer automatisk
- Vis "Bare X plasser igjen" hvis begrenset kapasitet
- Animert checkmark når booking er vellykket
- "Legg til i kalender"-knapp (iCal/Google Calendar)
- QR-kode med bookingdetaljer på bekreftelsesside

**Wow-faktor:**
- 🎉 Kort confetti-animasjon ved vellykket booking
- 📱 Realtime oppdatering: Hvis noen booker mens du ser på, oppdateres kalenderen
- 🔍 "Populære tider" indikator basert på historikk

---

### F3: Rollebasert tilgang

#### F3.1: Roller og tilganger
**Prioritet:** Kritisk | **Bruker:** System-admin, Tenant-admin

**Krav:**
- [ ] Minimum to roller: `admin` (system), `tenant_admin` (kunde)
- [ ] System-admin kan:
  - Se alle tenants
  - Aktivere/deaktivere tenants
  - Se alle bookinger (read-only)
  - Endre abonnementer
- [ ] Tenant-admin kan:
  - Administrere egne ressurser
  - Se egne bookinger
  - Endre SMS-innstillinger
  - Se dashboard-statistikk
- [ ] Isolasjon: Tenants ser aldri andre tenants' data

**Suksesskriterier:**
- ✅ Middleware håndhever roller konsekvent
- ✅ Unauthorized tilgang gir 403 med tydelig melding
- ✅ UI skjuler funksjoner bruker ikke har tilgang til

**UX-detaljer:**
- System-admin får annen navigasjon (admin-panel synlig)
- Tenant-admin ser tenant-navn prominent i header
- Ingen forvirrende "Permission denied" - heller redirect til dashboard

---

### F4: Dashboards

#### F4.1: Kunde-dashboard
**Prioritet:** Høy | **Bruker:** Tenant-admin

**Krav:**
- [ ] Oversiktskort (cards):
  - Antall bookinger i dag / denne uken
  - Antall aktive ressurser
  - Abonnementsstatus
  - Neste kommende booking
- [ ] Liste over kommende bookinger (5 siste)
- [ ] Quick actions:
  - "Ny ressurs"
  - "SMS-innstillinger"
  - "Del bookingside"
- [ ] Link til full bookingside (/{slug})

**Suksesskriterier:**
- ✅ Laster på under 1 sekund
- ✅ Responsivt grid (1 col mobil, 3 col desktop)
- ✅ Tydelig call-to-action hvis ingen ressurser
- ✅ Data oppdateres uten full page reload

**UX-detaljer:**
- Velkomstmelding med navn: "Hei, [Navn]! 👋"
- Visualiser bookingtrend med enkel graf (siste 7 dager)
- "Bookinglink kopiert!"-melding med animasjon
- Onboarding checklist for nye brukere:
  - ✅ Opprett ressurs
  - ☐ Sett opp SMS
  - ☐ Motta første booking

**Wow-faktor:**
- 📊 Mini-graf som animeres inn når siden lastes
- 🔔 Notification badge hvis nye bookinger siden sist besøk
- 🎯 Personaliserte tips: "Ingen bookinger denne uken? Prøv å dele linken på sosiale medier!"

---

#### F4.2: System-admin dashboard
**Prioritet:** Middels | **Bruker:** System-admin

**Krav:**
- [ ] Oversiktskort:
  - Totalt antall tenants
  - Aktive vs inaktive tenants
  - Totalt antall bookinger
  - Nye registreringer denne måneden
- [ ] Tabell over alle tenants:
  - Navn, slug, virksomhetstype, status, registrert dato
  - Sortérbar på alle kolonner
  - Søk på navn/slug
  - Quick actions: "Vis detaljer", "Aktiver/deaktiver"
- [ ] Filter: Vis kun aktive / inaktive / alle

**Suksesskriterier:**
- ✅ Håndterer 100+ tenants uten ytelsesfall
- ✅ Søk/filter oppdateres instant
- ✅ Paginering hvis mange tenants

**UX-detaljer:**
- Export til CSV-knapp for tenant-liste
- Bulk actions: "Deaktiver valgte tenants"
- Inline status toggle (on/off switch)
- Fargekoding: Grønn badge = aktiv, grå = inaktiv

---

### F5: Teletopia SMS-integrasjon

#### F5.1: SMS-innstillinger
**Prioritet:** Høy | **Bruker:** Tenant-admin

**Krav:**
- [ ] Tabell for SMS-innstillinger per tenant
- [ ] Kryptert lagring av API-nøkkel i database
- [ ] Form for å legge inn/endre API-nøkkel
- [ ] Test-SMS funksjon:
  - Input: telefonnummer
  - Output: "SMS sendt!" eller feilmelding
  - Logging av test-SMS i database

**Suksesskriterier:**
- ✅ API-nøkkel maskeres i UI (••••••••nøkkel)
- ✅ Test-SMS sendes på under 3 sekunder
- ✅ Tydelig feilmelding hvis feil API-nøkkel
- ✅ Success-melding med grønn checkmark

**UX-detaljer:**
- Hjelpetekst: "Hvor finner jeg min API-nøkkel?" med link
- Placeholder i telefonnr-felt: "+47 XXX XX XXX"
- Formattering av telefonnummer (legger til +47 hvis mangler)
- "Send test-SMS til meg"-knapp (bruker innlogget brukers nr)

**Wow-faktor:**
- 📲 Animasjon av "SMS-sending" (3 prikker som blinker)
- ✅ Konfetti ved første vellykkede test-SMS
- 📊 Viser statistikk: "X SMS sendt denne måneden"

---

#### F5.2: Automatisk SMS-utsending
**Prioritet:** Lav (nice-to-have) | **Bruker:** Sluttbruker

**Krav:**
- [ ] Send SMS-bekreftelse ved ny booking
- [ ] Mal for SMS-tekst (kan tilpasses per tenant)
- [ ] Variabler: {navn}, {ressurs}, {dato}, {tid}, {bookingID}
- [ ] SMS sendes asynkront (queue job)
- [ ] Logging av sendte SMS

**Suksesskriterier:**
- ✅ SMS sendes innen 1 minutt etter booking
- ✅ Graceful fail hvis SMS feiler (booking fortsatt OK)
- ✅ Retry-logikk hvis API midlertidig nede

**UX-detaljer:**
- Checkbox: "Send SMS-bekreftelse" (default på)
- Preview av SMS før aktivering
- Fallback til e-post hvis SMS feiler

---

### F6: Landingsside

#### F6.1: Offentlig forside
**Prioritet:** Middels | **Bruker:** Alle (ikke innlogget)

**Krav:**
- [ ] Hero-seksjon med:
  - Overskrift og beskrivelse av tjenesten
  - Call-to-action: "Kom i gang gratis"
  - Visuelt element (illustrasjon/bilde)
- [ ] Liste over alle aktive tenants:
  - Kort (card) per tenant med navn, type, beskrivelse
  - Link til bookingside (/{slug})
- [ ] Søkefelt: Filtrer tenants i sanntid
- [ ] Filter på virksomhetstype (chips/tags)
- [ ] Footer med: Om oss, kontakt, personvern

**Suksesskriterier:**
- ✅ Første visning laster på under 2 sekunder
- ✅ Søk/filter oppdateres instant (ingen delay)
- ✅ Minimum 8 tenants synlig før scrolling (desktop)
- ✅ Engasjerende design som skiller seg ut

**UX-detaljer:**
- Tenant-cards har hover-effekt (løft + shadow)
- "Book nå"-knapp prominent på hver card
- Badge viser virksomhetstype
- Smooth scroll til tenant-liste fra hero
- Paginering eller "Last mer"-knapp hvis mange tenants

**Wow-faktor:**
- 🔍 Instant search med highlight av matchende ord
- ✨ Smooth fade-in av cards ved scroll
- 🗺️ Eventuell kartvisning av tenants (hvis lokasjon lagres)
- 📊 "Mest populære"-badge på mest bookede tenants
- 🎨 Gradient hero-bakgrunn med subtil animasjon

---

### F7: Notifikasjoner og feedback

#### F7.1: Toast-meldinger
**Prioritet:** Høy | **Bruker:** Alle

**Krav:**
- [ ] Toast system for:
  - Success: "Ressurs opprettet", "Booking vellykket"
  - Error: "Noe gikk galt", spesifikk feilmelding
  - Info: "Endringer lagret"
  - Warning: "Denne ressursen har aktive bookinger"
- [ ] Plassering: Topp høyre hjørne
- [ ] Auto-dismiss etter 4 sekunder
- [ ] Kan lukkes manuelt (X-knapp)
- [ ] Ikke blokkere UI (overlay)

**Suksesskriterier:**
- ✅ Tydelige ikoner og farger
- ✅ Lesbar tekst (ikke for liten)
- ✅ Smooth slide-in animasjon
- ✅ Kan stacks flere toasts

**UX-detaljer:**
- Success: Grønn med checkmark-ikon
- Error: Rød med warning-ikon
- Progressbar som viser countdown til auto-dismiss
- Link i toast hvis relevant: "Se bookingen"

---

#### F7.2: Inline validering
**Prioritet:** Høy | **Bruker:** Alle

**Krav:**
- [ ] Sanntids validering på skjemaer:
  - E-post: Sjekk format
  - Slug: Sjekk tilgjengelighet
  - Telefon: Sjekk format
  - Påkrevde felt: Vis feil ved blur
- [ ] Visuell feedback:
  - Grønn border + checkmark hvis OK
  - Rød border + feilmelding hvis feil
- [ ] Error messages under felt (ikke modal)

**Suksesskriterier:**
- ✅ Validering kjører uten merkbar delay
- ✅ Feilmeldinger er spesifikke og hjelpsomme
- ✅ Knapper disables hvis skjema invalid

**UX-detaljer:**
- "E-postadressen ser ikke riktig ut" istedenfor "Invalid email"
- "Dette navnet er allerede tatt, prøv [forslag]" ved slug-konflikt
- Viser karakter-telling: "48/200 tegn"
- Auto-formattering: Fjern mellomrom i e-post, formater telefonnr

---

### F8: Ytelse og teknisk

#### F8.1: Lasting og responsivitet
**Prioritet:** Kritisk | **Bruker:** Alle

**Krav:**
- [ ] Første side (landingsside) lastes på under 2 sekunder
- [ ] Dashboard lastes på under 1 sekund
- [ ] Bookingside lastes på under 1.5 sekunder
- [ ] Lazy loading av bilder
- [ ] Optimaliserte database-queries (eager loading)
- [ ] Caching av landingsside-tenants (5 min)

**Suksesskriterier:**
- ✅ Lighthouse score > 90 på ytelse
- ✅ Fungerer på 3G nettverk
- ✅ Ingen "lag" ved interaksjon

**Tekniske detaljer:**
- N+1 query problem unngås (eager load relasjoner)
- Index på tenant.slug for rask oppslag
- Minifisert CSS og JS i produksjon

---

#### F8.2: Sikkerhet
**Prioritet:** Kritisk | **Bruker:** Utvikler/System

**Krav:**
- [ ] CSRF-beskyttelse på alle POST/PUT/DELETE
- [ ] SQL injection-beskyttelse (Eloquent)
- [ ] XSS-beskyttelse (escape output)
- [ ] Rate limiting på offentlige endepunkter
- [ ] Kryptert lagring av sensitive data (API-nøkler)
- [ ] Tenant-isolasjon: Kan aldri se andre tenants' data
- [ ] Validering av file uploads (hvis implementert)

**Suksesskriterier:**
- ✅ Ingen sensitiv data i URL/query params
- ✅ Middleware håndhever tenant-tilgang konsekvent
- ✅ API-nøkler aldri logges eller vises i frontend

**Tekniske detaljer:**
- Global scope på Eloquent modeller for tenant_id
- Policy-sjekk før visning av ressurser
- Environment-baserte secrets (.env)

---

#### F8.3: Feilhåndtering
**Prioritet:** Høy | **Bruker:** Alle

**Krav:**
- [ ] Custom 404-side (ikke funnet)
- [ ] Custom 403-side (ingen tilgang)
- [ ] Custom 500-side (server error)
- [ ] Logging av alle feil til fil
- [ ] Graceful degradation hvis JS feiler
- [ ] Fallback hvis API-kall feiler

**Suksesskriterier:**
- ✅ Feilsider er brukervennlige (ikke teknisk sjargong)
- ✅ Alltid vis vei tilbake (link til forside/dashboard)
- ✅ Brukeren mister aldri data ved feil

**UX-detaljer:**
- 404: "Vi fant ikke siden du lette etter. Kanskje du mente...?"
- 403: "Du har ikke tilgang til denne siden. Kontakt admin hvis dette er feil."
- 500: "Oops, noe gikk galt. Vi jobber med saken. Prøv igjen om litt."
- Alle med illustrasjon og lenke til hjem

---

## 🎨 Design- og UX-krav

### D1: Visuell konsistens

**Krav:**
- [ ] Samme spacing-system brukt overalt (4px/8px grid)
- [ ] Konsistent fargebruk (se design guide)
- [ ] Same typography hierarchy på alle sider
- [ ] Like elementer ser like ut (knapper, cards, inputs)
- [ ] Consistent icon-sett (én kilde, f.eks. Heroicons)

**Suksesskriterier:**
- ✅ En designer skal kunne kjenne igjen systemet på alle sider
- ✅ Nye sider følger samme mal som eksisterende

---

### D2: Responsivt design

**Krav:**
- [ ] Fungerer perfekt på:
  - Mobil (320px - 640px)
  - Tablet (641px - 1024px)
  - Desktop (1025px+)
- [ ] Touch-vennlig på mobil (min 44x44px targets)
- [ ] Hamburger-meny på mobil
- [ ] Stack cards vertikalt på mobil
- [ ] Lesbar tekst på alle skjermstørrelser (min 16px)

**Suksesskriterier:**
- ✅ Ingen horisontal scroll nødvendig
- ✅ Alle funksjoner tilgjengelig på mobil
- ✅ Testet på ekte enheter

---

### D3: Tilgjengelighet (a11y)

**Krav:**
- [ ] WCAG AA kontrast minimum (4.5:1)
- [ ] Alle interaktive elementer tastaturnavigasjonsvennlige
- [ ] Focus states tydelig synlige
- [ ] Alt-tekst på alle bilder
- [ ] Aria-labels på ikoner uten tekst
- [ ] Skjermleser-vennlige labels på skjemaer
- [ ] Ingen "bare-farge" indikatorer (bruk også ikoner/tekst)

**Suksesskriterier:**
- ✅ Kan navigere hele siden med kun tastatur
- ✅ Skjermleser kan lese all info riktig
- ✅ axe DevTools gir null kritiske feil

---

## 📱 Brukerreiser (User Journeys)

### Journey 1: Ny kunde registrerer seg

**Mål:** Få første ressurs oppe og kunne motta bookinger

1. **Registrering** (2 min)
   - Klikker "Kom i gang" på landingsside
   - Fyller inn e-post, passord, firmanavn
   - Velger virksomhetstype
   - Redigerer slug (forslag: firma-navn)
   - Submit → Automatisk innlogget

2. **Onboarding** (3 min)
   - Velkomstskjerm: "La oss sette opp din første ressurs!"
   - Quick-setup: Navn, type, åpningstider
   - "Smart defaults" foreslått basert på virksomhetstype
   - Submit → "Gratulerer! Din bookingside er klar"

3. **Dele bookingside** (1 min)
   - Dashboard viser "Del din bookingside"
   - Klikk → Link kopieres + QR-kode vises
   - Toast: "Link kopiert! 🎉"

**Total tid:** 6 minutter fra landing til delt bookinglink

**Suksesskriterier:**
- ✅ 80% fullfører uten å forlate
- ✅ Ingen forvirring om hva som skal gjøres

---

### Journey 2: Sluttbruker booker time

**Mål:** Gjennomføre booking på under 2 minutter

1. **Finne bookingside** (30 sek)
   - Mottar link eller finner via landingsside-søk
   - Klikker seg inn på /{slug}
   - Ser oversikt over ressurser

2. **Velge og booke** (1 min)
   - Klikker på ønsket ressurs
   - Velger dato fra kalender (grønne dager)
   - Velger tid fra tilgjengelige slots
   - Fyller inn: navn, e-post, telefon
   - Submit

3. **Bekreftelse** (30 sek)
   - Bekreftelsesside med detaljer
   - Konfetti-animasjon 🎉
   - "Legg til i kalender"-knapp
   - QR-kode med bookinginfo

**Total tid:** Under 2 minutter

**Suksesskriterier:**
- ✅ 90% som starter booking fullfører
- ✅ Funger på mobil med én hånd
- ✅ Ingen forvirrende steg

---

### Journey 3: Admin overvåker system

**Mål:** Sjekke status på alle tenants

1. **Login som admin** (30 sek)
   - Logger inn
   - Ser system-admin dashboard

2. **Oversikt** (1 min)
   - Ser totalt antall tenants, bookinger
   - Sorterer tenants etter registreringsdato
   - Ser hvilke som er inaktive

3. **Handling** (30 sek)
   - Klikker "Aktiver" på inaktiv tenant
   - Bekreftelse: "Tenant aktivert"
   - Status oppdateres umiddelbart

**Total tid:** 2 minutter

---

## 🧪 Testscenarier

### Kritiske testcases

#### T1: Registrering og tenant-opprettelse
**Steg:**
1. Gå til /register
2. Fyll inn e-post, passord, firmanavn
3. Velg virksomhetstype
4. Rediger slug
5. Submit

**Forventet:**
- ✅ Bruker opprettes i `users`
- ✅ Tenant opprettes i `tenants` med unik slug
- ✅ Subscription opprettes og settes til aktiv
- ✅ Bruker redirectes til dashboard
- ✅ Toast: "Velkommen! La oss komme i gang"

**Feilscenarier:**
- Slug allerede tatt → Feilmelding med forslag
- E-post allerede brukt → Feilmelding
- Feil i prosess → Rollback (ingen data lagres)

---

#### T2: Booking-flyt
**Steg:**
1. Gå til /{slug}
2. Klikk på ressurs
3. Velg ledig dato
4. Velg ledig tid
5. Fyll inn kontaktinfo
6. Submit

**Forventet:**
- ✅ Booking lagres i `bookings`
- ✅ Bekreftelsesside vises
- ✅ Kalender oppdateres (tiden er nå opptatt)
- ✅ SMS sendes (hvis aktivert)

**Feilscenarier:**
- Dobbeltbooking → Feilmelding: "Denne tiden er dessverre opptatt"
- Ugyldig e-post → Inline validering stopper submit
- API-feil → Graceful error, booking lagres likevel

---

#### T3: Middleware-beskyttelse
**Steg:**
1. Opprett bruker med inaktiv subscription
2. Logg inn
3. Prøv å gå til /dashboard/resources

**Forventet:**
- ✅ Middleware blokkerer tilgang
- ✅ Redirect til "Aktiver abonnement"-side
- ✅ Tydelig melding om hvorfor

**Feilscenarier:**
- Kan ikke omgå med direkte URL
- Ingen database-queries kjøres før sjekk

---

## ✅ Akseptansekriterier (Definition of Done)

### En feature er ferdig når:

- [ ] **Funksjonalitet**
  - Oppfyller alle krav i spesifikasjonen
  - Fungerer som beskrevet i user journey
  - Håndterer feilscenarier gracefully

- [ ] **Kode-kvalitet**
  - Følger Laravel beste praksis
  - Ingen hardkodede verdier (bruk config/database)
  - Kommentarer på kompleks logikk
  - Konsistente navnekonvensjoner

- [ ] **Design**
  - Følger design guide
  - Konsistent med resten av systemet
  - Responsivt på alle skjermstørrelser
  - Tilgjengelig (a11y checks)

- [ ] **Testing**
  - Manuelt testet på mobil og desktop
  - Alle edge cases testet
  - Feilscenarier håndteres korrekt
  - Testet uten JavaScript (graceful degradation)

- [ ] **Dokumentasjon**
  - README oppdatert hvis nødvendig
  - Inline kommentarer på kompleks kode
  - Commit message beskriver endringen

---

## 🚀 Prioritering og MVP-scope

### MUST HAVE (MVP - Uke 1-2)
✅ Multi-tenant registrering  
✅ Abonnement-middleware  
✅ Ressurs CRUD  
✅ Offentlig bookingside  
✅ Booking-prosess  
✅ Kunde-dashboard  
✅ Admin-dashboard (basis)  
✅ SMS test-funksjon  
✅ Landingsside  

### SHOULD HAVE (Post-MVP - Uke 3+)
⚡ Automatisk SMS ved booking  
⚡ Avansert kalender med drag-drop  
⚡ E-post-notifikasjoner  
⚡ Booking-statistikk og grafer  
⚡ Export til CSV  
⚡ Bulk actions  

### COULD HAVE (Fremtid)
💡 Multi-språk støtte  
💡 Stripe/Vipps betaling  
💡 Kalenderintegrasjon (Google Calendar)  
💡 Kart-visning av tenants  
💡 Kunde-reviews og rating  
💡 Automatisk reminder-SMS  

### WON'T HAVE (Utenfor scope)
❌ Native mobilapp  
❌ Video chat-integrasjon  
❌ Advanced CRM-funksjoner  
❌ Multi-language admin  

---

## 📊 Målbare suksessmål

### Brukeropplevelse
- 📈 **Registreringstid:** Snitt under 3 minutter
- 📈 **Booking completion rate:** Over 85%
- 📈 **Mobile usage:** Minimum 60% av bookinger fra mobil
- 📈 **Return rate:** 70% logger inn igjen innen 7 dager

### Ytelse
- ⚡ **Landingsside:** < 2 sek første load
- ⚡ **Dashboard:** < 1 sek
- ⚡ **Bookingside:** < 1.5 sek
- ⚡ **Lighthouse score:** > 90

### Kvalitet
- ✅ **Zero kritiske bugs** ved launch
- ✅ **WCAG AA compliance** på alle sider
- ✅ **100% av user journeys fungerer** uten feil

---

## 📝 Vedlegg: Terminologi

**Tenant** = Kunde/virksomhet som bruker systemet  
**Resource** = Booking-objekt (hytte, stol, rom, behandler)  
**Slug** = Unik URL-del for tenant (f.eks. /salong-rosa)  
**Subscription** = Abonnement knyttet til tenant  
**Availability** = Tilgjengelighet for ressurs  
**Booking** = Reservasjon gjort av sluttbruker  
**Toast** = Liten notifikasjon som vises midlertidig  
**Badge** = Liten farget label (aktiv/inaktiv)  
**Modal** = Popup-vindu over innhold  
**Empty state** = Visning når ingen data finnes  

---

## 🎯 Oppsummering

Dette prosjektet skal levere en **moderne, brukervennlig multi-tenant bookingportal** som:

✨ **Er enkel å bruke** for alle aldre og tekniske nivåer  
✨ **Laster raskt** og fungerer på alle enheter  
✨ **Ser profesjonell ut** med konsistent design  
✨ **Har wow-faktorer** som engasjerer brukere  
✨ **Er sikker** og beskytter tenant-data  
✨ **Skalerer** fra 10 til 10,000 tenants  

**Nøkkelen til suksess:** Fokuser på brukervennlighet, ytelse og konsistens - ikke kompleksitet.

---

**Versjon:** 1.0  
**Dato:** November 2025  
**Status:** Godkjent for utvikling ✅