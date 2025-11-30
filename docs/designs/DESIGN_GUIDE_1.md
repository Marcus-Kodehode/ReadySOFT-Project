# Design Guide - Multi-tenant Bookingportal

## 🎨 Designfilosofi

**Moderne. Ren. Intuitiv.**

Designet skal være profesjonelt nok for bedriftskunder, men enkelt nok for alle sluttbrukere. Vi prioriterer klarhet over kompleksitet, og funksjonalitet over dekorasjon.

---

## 🎯 Kjerneprinsipper

### 1. **Klarhet først**
- Tydelig hierarki - brukeren skal alltid vite hvor de er
- Konsekvent navigasjon på tvers av alle sider
- Tydelige call-to-actions

### 2. **Responsivt fra bunnen**
- Mobile-first tankesett
- Fungerer perfekt på mobil, tablet og desktop
- Touch-vennlige klikkeflater (min 44x44px)

### 3. **Rask og lett**
- Minimal JavaScript der det ikke trengs
- Alpine.js kun for interaktivitet
- Rask lasting, ingen unødvendige animasjoner

### 4. **Tilgjengelig for alle**
- God kontrast (WCAG AA minimum)
- Tastaturnavigasjon fungerer
- Semantisk HTML

---

## 🎨 Fargepalett

### Primærfarger
```css
/* Hovedfarge - Tillitsvekkende blå */
--primary: #2563eb;      /* Blue-600 */
--primary-hover: #1d4ed8; /* Blue-700 */
--primary-light: #dbeafe; /* Blue-100 */

/* Suksess - Grønn */
--success: #10b981;       /* Emerald-500 */
--success-light: #d1fae5; /* Emerald-100 */

/* Advarsel - Gul */
--warning: #f59e0b;       /* Amber-500 */
--warning-light: #fef3c7; /* Amber-100 */

/* Feil - Rød */
--error: #ef4444;         /* Red-500 */
--error-light: #fee2e2;   /* Red-100 */
```

### Nøytrale farger
```css
/* Tekst og bakgrunner */
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-200: #e5e7eb;
--gray-300: #d1d5db;
--gray-600: #4b5563;
--gray-700: #374151;
--gray-900: #111827;

/* Hvit og svart */
--white: #ffffff;
--black: #000000;
```

### Bruksområder
- **Primær knapp:** `bg-blue-600 hover:bg-blue-700 text-white`
- **Sekundær knapp:** `bg-white border-gray-300 text-gray-700 hover:bg-gray-50`
- **Destruktiv knapp:** `bg-red-600 hover:bg-red-700 text-white`
- **Bakgrunn:** `bg-gray-50` (hele siden), `bg-white` (cards/modaler)
- **Tekst:** `text-gray-900` (overskrifter), `text-gray-600` (brødtekst)

---

## 📝 Typografi

### Font Stack
```css
font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 
             "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
```
*Bruker system-fonter for rask lasting og naturlig OS-følelse*

### Størrelser og vekter
```css
/* Overskrifter */
.text-3xl { font-size: 1.875rem; font-weight: 700; } /* H1 */
.text-2xl { font-size: 1.5rem; font-weight: 700; }   /* H2 */
.text-xl { font-size: 1.25rem; font-weight: 600; }   /* H3 */
.text-lg { font-size: 1.125rem; font-weight: 600; }  /* H4 */

/* Brødtekst */
.text-base { font-size: 1rem; }      /* 16px - standard */
.text-sm { font-size: 0.875rem; }    /* 14px - meta-info */
.text-xs { font-size: 0.75rem; }     /* 12px - labels/badges */

/* Vekter */
.font-bold { font-weight: 700; }     /* Overskrifter */
.font-semibold { font-weight: 600; } /* Underoverskrifter */
.font-medium { font-weight: 500; }   /* Viktig tekst */
.font-normal { font-weight: 400; }   /* Brødtekst */
```

### Linjehøyde
- Overskrifter: `leading-tight` (1.25)
- Brødtekst: `leading-relaxed` (1.625)
- Knappe tekst: `leading-none` (1)

---

## 🧩 Komponenter

### Knapper

**Primær knapp** (Hovedhandling)
```html
<button class="px-4 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
  Opprett booking
</button>
```

**Sekundær knapp**
```html
<button class="px-4 py-2 font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
  Avbryt
</button>
```

**Knapp med ikon**
```html
<button class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
  <svg class="w-5 h-5">...</svg>
  <span>Ny ressurs</span>
</button>
```

**Størrelser:**
- Small: `px-3 py-1.5 text-sm`
- Default: `px-4 py-2 text-base`
- Large: `px-6 py-3 text-lg`

---

### Cards

**Basis card**
```html
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
  <h3 class="mb-2 text-lg font-semibold text-gray-900">Tittel</h3>
  <p class="text-gray-600">Innhold her...</p>
</div>
```

**Card med hover-effekt** (for klikkbare cards)
```html
<div class="p-6 transition-all bg-white border border-gray-200 rounded-lg shadow-sm cursor-pointer hover:shadow-md hover:border-blue-300">
  ...
</div>
```

**Dashboard stat card**
```html
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
  <div class="flex items-center justify-between">
    <div>
      <p class="text-sm font-medium text-gray-600">Aktive bookinger</p>
      <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
    </div>
    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
      <svg class="w-6 h-6 text-blue-600">...</svg>
    </div>
  </div>
</div>
```

---

### Skjemaer

**Input-felt**
```html
<div>
  <label class="block mb-1 text-sm font-medium text-gray-700">
    Navn på ressurs
  </label>
  <input type="text" 
         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
         placeholder="F.eks. Hytte 1">
  <p class="mt-1 text-sm text-gray-500">Velg et beskrivende navn</p>
</div>
```

**Feilmelding i input**
```html
<input type="text" 
       class="w-full px-3 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
<p class="flex items-center gap-1 mt-1 text-sm text-red-600">
  <svg class="w-4 h-4">!</svg>
  Dette feltet er påkrevd
</p>
```

**Select/Dropdown**
```html
<select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
  <option>Velg virksomhetstype</option>
  <option>Hytteutleie</option>
  <option>Frisør</option>
</select>
```

**Checkbox/Radio**
```html
<label class="flex items-center gap-2 cursor-pointer">
  <input type="checkbox" 
         class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
  <span class="text-sm text-gray-700">Jeg godtar vilkårene</span>
</label>
```

---

### Tabeller

**Admin-tabell**
```html
<div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
  <table class="w-full">
    <thead class="border-b border-gray-200 bg-gray-50">
      <tr>
        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
          Kunde
        </th>
        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
          Abonnement
        </th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      <tr class="transition-colors hover:bg-gray-50">
        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
          Salong Rosa
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
            Aktiv
          </span>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

---

### Badges/Status

```html
<!-- Aktiv -->
<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
  Aktiv
</span>

<!-- Inaktiv -->
<span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
  Inaktiv
</span>

<!-- Venter -->
<span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
  Venter
</span>
```

---

### Alerts/Meldinger

**Suksess**
```html
<div class="p-4 border-l-4 border-green-500 rounded bg-green-50">
  <div class="flex items-start gap-3">
    <svg class="flex-shrink-0 w-5 h-5 text-green-500">✓</svg>
    <div>
      <p class="text-sm font-medium text-green-800">
        Booking opprettet!
      </p>
      <p class="mt-1 text-sm text-green-700">
        Du vil motta bekreftelse på SMS.
      </p>
    </div>
  </div>
</div>
```

**Feil**
```html
<div class="p-4 border-l-4 border-red-500 rounded bg-red-50">
  <div class="flex items-start gap-3">
    <svg class="flex-shrink-0 w-5 h-5 text-red-500">!</svg>
    <div>
      <p class="text-sm font-medium text-red-800">
        Noe gikk galt
      </p>
      <p class="mt-1 text-sm text-red-700">
        Vennligst prøv igjen senere.
      </p>
    </div>
  </div>
</div>
```

---

### Modal (Alpine.js)

```html
<div x-data="{ open: false }">
  <!-- Trigger -->
  <button @click="open = true" 
          class="px-4 py-2 text-white bg-blue-600 rounded-lg">
    Åpne modal
  </button>

  <!-- Modal -->
  <div x-show="open" 
       x-cloak
       class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div @click="open = false" 
         class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal content -->
    <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
      <h3 class="mb-4 text-lg font-semibold text-gray-900">
        Bekreft sletting
      </h3>
      <p class="mb-6 text-gray-600">
        Er du sikker på at du vil slette denne ressursen?
      </p>
      <div class="flex justify-end gap-3">
        <button @click="open = false" 
                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
          Avbryt
        </button>
        <button class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
          Slett
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
```

---

## 📐 Layout og Spacing

### Container
```html
<!-- Full-width bakgrunn, sentrert innhold -->
<div class="min-h-screen bg-gray-50">
  <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <!-- Innhold her -->
  </div>
</div>
```

### Spacing Scale (Tailwind)
- **xs:** `space-y-1` / `gap-1` (4px)
- **sm:** `space-y-2` / `gap-2` (8px)
- **md:** `space-y-4` / `gap-4` (16px) - **standard**
- **lg:** `space-y-6` / `gap-6` (24px)
- **xl:** `space-y-8` / `gap-8` (32px)

### Grid Layouts

**Dashboard cards (responsiv)**
```html
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
  <div class="p-6 bg-white rounded-lg shadow-sm">...</div>
  <div class="p-6 bg-white rounded-lg shadow-sm">...</div>
  <div class="p-6 bg-white rounded-lg shadow-sm">...</div>
</div>
```

**To-kolonner layout**
```html
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
  <div>Venstre kolonne</div>
  <div>Høyre kolonne</div>
</div>
```

---

## 🧭 Navigasjon

### Hovednavigasjon (innlogget)

```html
<nav class="bg-white border-b border-gray-200 shadow-sm">
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <svg class="w-8 h-8 text-blue-600">...</svg>
        <span class="text-xl font-bold text-gray-900">BookingPortal</span>
      </div>
      
      <!-- Nav links -->
      <div class="items-center hidden gap-6 md:flex">
        <a href="/dashboard" 
           class="font-medium text-gray-700 hover:text-blue-600">
          Dashboard
        </a>
        <a href="/dashboard/resources" 
           class="font-medium text-gray-700 hover:text-blue-600">
          Ressurser
        </a>
        <a href="/dashboard/bookings" 
           class="font-medium text-gray-700 hover:text-blue-600">
          Bookinger
        </a>
      </div>
      
      <!-- User menu -->
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" 
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900">
          <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
            <span class="text-sm font-medium">JD</span>
          </div>
          <svg class="w-4 h-4">▼</svg>
        </button>
        
        <!-- Dropdown -->
        <div x-show="open" 
             @click.away="open = false"
             x-cloak
             class="absolute right-0 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
          <a href="/dashboard/settings" 
             class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            Innstillinger
          </a>
          <form method="POST" action="/logout">
            <button class="w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-gray-50">
              Logg ut
            </button>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</nav>
```

### Breadcrumbs

```html
<nav class="flex items-center gap-2 mb-6 text-sm text-gray-600">
  <a href="/dashboard" class="hover:text-blue-600">Dashboard</a>
  <svg class="w-4 h-4">›</svg>
  <a href="/dashboard/resources" class="hover:text-blue-600">Ressurser</a>
  <svg class="w-4 h-4">›</svg>
  <span class="font-medium text-gray-900">Hytte 1</span>
</nav>
```

---

## 🎭 States og Interaktivitet

### Hover States
```css
/* Knapper */
hover:bg-blue-700
hover:shadow-md

/* Lenker */
hover:text-blue-600
hover:underline

/* Cards */
hover:shadow-lg
hover:scale-105 transition-transform
```

### Focus States
```css
/* Alltid inkluder focus for tilgjengelighet */
focus:outline-none 
focus:ring-2 
focus:ring-blue-500 
focus:ring-offset-2
```

### Loading States

```html
<!-- Knapp med loading -->
<button disabled 
        class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-400 rounded-lg cursor-not-allowed">
  <svg class="w-4 h-4 animate-spin">↻</svg>
  <span>Laster...</span>
</button>

<!-- Skeleton loader for cards -->
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
  <div class="space-y-4 animate-pulse">
    <div class="w-3/4 h-4 bg-gray-200 rounded"></div>
    <div class="w-1/2 h-4 bg-gray-200 rounded"></div>
  </div>
</div>
```

### Empty States

```html
<div class="p-12 text-center bg-white border-2 border-gray-300 border-dashed rounded-lg shadow-sm">
  <svg class="w-12 h-12 mx-auto mb-4 text-gray-400">📦</svg>
  <h3 class="mb-2 text-lg font-medium text-gray-900">
    Ingen ressurser ennå
  </h3>
  <p class="mb-6 text-gray-600">
    Kom i gang ved å opprette din første booking-ressurs
  </p>
  <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
    Opprett ressurs
  </button>
</div>
```

---

## 📱 Responsive Design

### Breakpoints (Tailwind)
- **sm:** 640px - Små tablets
- **md:** 768px - Tablets
- **lg:** 1024px - Laptops
- **xl:** 1280px - Desktop

### Mobile-first approach
```html
<!-- Start med mobil, legg til større skjermer -->
<div class="text-sm md:text-base lg:text-lg">
  Responsiv tekst
</div>

<!-- Skjul på mobil, vis på desktop -->
<div class="hidden lg:block">
  Bare synlig på stor skjerm
</div>

<!-- Vis på mobil, skjul på desktop -->
<div class="block lg:hidden">
  Bare synlig på mobil
</div>
```

---

## ✅ Tilgjengelighet (a11y)

### Checklist
- [ ] Alle interaktive elementer er tastaturnavigasjonsvennlige
- [ ] Focus states er tydelig synlige
- [ ] Fargekontrast er minimum 4.5:1 for tekst
- [ ] Alt-tekst på alle bilder
- [ ] Aria-labels på ikoner uten tekst
- [ ] Form-labels er koblet til input-felt
- [ ] Feilmeldinger er tydelige og beskrivende

### Eksempler
```html
<!-- Aria-label på ikon-knapp -->
<button aria-label="Lukk modal" class="...">
  <svg>×</svg>
</button>

<!-- Label koblet til input -->
<label for="email" class="...">E-post</label>
<input id="email" type="email" class="...">

<!-- Skip to main content -->
<a href="#main" 
   class="px-4 py-2 text-white bg-blue-600 rounded sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4">
  Hopp til hovedinnhold
</a>
<main id="main">...</main>
```

---

## 🚀 DX (Developer Experience)

### Gjenbrukbare Blade-komponenter

**Lag komponenter for repeterende elementer:**

```bash
# Eksempel på komponent-struktur
resources/views/components/
├── button.blade.php
├── card.blade.php
├── form/
│   ├── input.blade.php
│   ├── select.blade.php
│   └── textarea.blade.php
└── alert.blade.php
```

**Bruk:**
```html
<!-- I stedet for lang Tailwind-streng hver gang -->
<x-button variant="primary">
  Lagre
</x-button>

<x-card>
  <x-slot:title>Overskrift</x-slot>
  Innhold her...
</x-card>
```

### Konsistente CSS-utilities

**Lag egne utilities i `app.css` for ofte-brukte kombinasjoner:**

```css
@layer components {
  .btn {
    @apply px-4 py-2 rounded-lg font-medium transition-colors;
    @apply focus:outline-none focus:ring-2 focus:ring-offset-2;
  }
  
  .btn-primary {
    @apply btn bg-blue-600 text-white hover:bg-blue-700;
    @apply focus:ring-blue-500;
  }
  
  .card {
    @apply bg-white rounded-lg shadow-sm border border-gray-200 p-6;
  }
  
  .input {
    @apply w-full px-3 py-2 border border-gray-300 rounded-lg;
    @apply focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent;
  }
}
```

---

## 📋 Quick Reference

### Ofte brukte kombinasjoner

**Sentrert innhold:**
```html
<div class="flex items-center justify-center min-h-screen">
```

**Card med shadow:**
```html
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
```

**Knapp med ikon:**
```html
<button class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded-lg">
```

**Input-gruppe:**
```html
<div class="space-y-1">
  <label class="block text-sm font-medium text-gray-700">
  <input class="w-full px-3 py-2 border border-gray-300 rounded-lg">
  <p class="text-sm text-gray-500">
</div>
```

**Responsivt grid:**
```html
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
```

---

## 🎨 Eksempel på fullstendig side

### Kunde-dashboard
```html
<!-- Layout wrapper -->
<div class="min-h-screen bg-gray-50">
  
  <!-- Navigation -->
  <nav class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Se navigasjon-seksjonen over -->
  </nav>
  
  <!-- Main content -->
  <main class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    
    <!-- Page header -->
    <div class="mb-8">
      <h1 class="mb-2 text-3xl font-bold text-gray-900">
        Dashboard
      </h1>
      <p class="text-gray-600">
        Oversikt over din virksomhet og bookinger
      </p>
    </div>
    
    <!-- Stats grid -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
      <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <p class="text-sm font-medium text-gray-600">Aktive bookinger</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
      </div>
      <!-- ... flere stats -->
    </div>
    
    <!-- Recent bookings -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
          Nylige bookinger
        </h2>
      </div>
      <table class="w-full">
        <!-- Se tabell-seksjonen over -->
      </table>
    </div>
    
  </main>
  
</div>
```

---

## 💡 Tips og beste praksis

1. **Konsistens er viktigere enn kreativitet**
   - Bruk samme spacing, farger og komponenter overalt

2. **Test på ekte enheter**
   - Sjekk på telefon, ikke bare i dev tools

3. **Marker aktiv side i navigasjon**
   - Bruk `text-blue-600` eller `border-b-2 border-blue-600`

4. **Gi tilbakemelding på brukerhandlinger**
   - Toast-meldinger, loading states, success messages

5. **Bruk semantisk HTML**
   - `<button>` for klikk, `<a>` for navigasjon
   - `<main>`, `<nav>`, `<header>` for struktur

6. **Optimaliser for mobil først**
   - De fleste sluttbrukere vil booke fra mobil

7. **Hold det enkelt**
   - Ikke legg til animasjoner eller effekter uten grunn
   - Funksjonalitet > fancyness

---

**Dette er en levende guide - oppdater den etter hvert som prosjektet utvikler seg! 🎨**