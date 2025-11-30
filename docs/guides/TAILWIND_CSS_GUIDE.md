# 📚 TAILWIND CSS v4 – Komplett Guide

**Definiv installasjons- og bruksguide for Laravel, Node-prosjekter og generell bruk**

---

## 1. Hva er nytt i Tailwind 4?

Tailwind 4 er bygget for **Zero Config** – minimalt oppsett, maksimal kraft.

### Hva som er borte (i Tailwind 3):
- ❌ `tailwind.config.js` (ikke nødvendig lenger)
- ❌ `postcss.config.js` (ikke nødvendig lenger)
- ❌ `content:` liste i config
- ❌ "purge CSS" system
- ❌ Plugin-system som før

### Hva som er nytt (i Tailwind 4):
- ✔ Automatic class scanning
- ✔ Embedding rules direkte i CSS
- ✔ Design tokens via CSS-variabler
- ✔ Raskere build engine
- ✔ Minimal konfigurasjon, maksimal hastighet

**Resultat:** MYE enklere oppsett! 🚀

---

## 2. Installasjon (NPM)

### Trinn:
I ditt prosjekt, kjør:

```bash
npm install tailwindcss@latest
```

**Det er alt!** Ingen config-filer trengs.

---

## 3. Lag CSS-filen som aktiverer Tailwind

Du må ha én CSS-fil som aktiverer Tailwind.

### Trinn:
1. Opprett (eller åpne) filen:
   ```
   resources/css/app.css
   ```

2. Legg inn:
   ```css
   @import "tailwindcss";
   ```

3. **INGENTING annet!**

Tailwind 4 bygger hele rammeverket ut fra dette ene importet.

---

## 4. Bruk Tailwind i HTML/Blade-filer

Nå kan du begynne å bruke Tailwind-klasser direkte:

### Eksempel:

```blade
<div class="p-4 text-white bg-blue-600 rounded-xl">
    Hei fra Tailwind 4!
</div>
```

### Hva klassene gjør:
- `p-4` → padding på alle sider (1rem)
- `text-white` → hvit tekst
- `bg-blue-600` → blå bakgrunn
- `rounded-xl` → avrundede hjørner

**Ingen config, ingen scanning, ingen purge – alt bare funker!**

---

## 5. Build og Development

### For utvikling (hot-reload):

```bash
npm run dev
```

Kjør dette i en terminal - det gjenoppbygger CSS automatisk når du endrer kode.

### For produksjon (optimalisert build):

```bash
npm run build
```

Dette kompilerer og minifiserer CSS for best ytelse.

### I Laravel:
Vite styrer alt dette automatisk. Du trenger bare å kjøre kommandoene over.

---

## 6. Design Tokens (det nye systemet)

Tailwind 4 lar deg bruke **CSS-variabler** for å definere globale design-tokens.

### Eksempel:

Lag en global stylesheet-del i `resources/css/app.css`:

```css
@import "tailwindcss";

:root {
  --color-primary: #4f46e5;
  --color-secondary: #ec4899;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --rounded-lg: 0.5rem;
}
```

### Bruk det i HTML:

```blade
<div class="bg-[var(--color-primary)] 
            p-[var(--spacing-md)] 
            rounded-[var(--rounded-lg)]">
    Custom design tokens!
</div>
```

**Dette er den moderne måten å "konfigurere" Tailwind på.**

---

## 7. Egendefinerte farger, spacing, shadows osv.

Tailwind 4 bruker **inline arbitrary values** som standard – ingen config-fil nødvendig.

### Eksempler:

**Egendefinert farge:**
```blade
<div class="bg-[#1e293b]">
    Mørkegrå bakgrunn
</div>
```

**Egendefinert font-size:**
```blade
<p class="text-[14px]">
    Egen størrelse
</p>
```

**Egendefinert padding:**
```blade
<div class="p-[18px]">
    Eget padding
</div>
```

**Egendefinert shadow:**
```blade
<div class="shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
    Egendefinert skygge
</div>
```

**Du kan praktisk talt inline-style hva som helst uten å røre konfigurasjon!**

---

## 8. Lage eget fargetema (f.eks. for brand)

Hvis du vil lage et konsistent fargetema for prosjektet ditt:

### Trinn:
1. Definer brand-farger i CSS:

```css
@import "tailwindcss";

:root {
  --brand-primary: #3b82f6;
  --brand-secondary: #1e40af;
  --brand-dark: #0f172a;
  --brand-light: #f1f5f9;
  --brand-card: #1e293b;
}
```

2. Bruk dem i HTML:

```blade
<div class="bg-[var(--brand-card)] text-[var(--brand-light)] p-6 rounded-xl shadow-lg">
    <h1 class="text-[var(--brand-primary)]">
        Mitt ProsjektNavn
    </h1>
    <p>Stilig design med tema!</p>
</div>
```

**Fordeler:**
- ✔ Konsistent design gjennom hele appen
- ✔ Lett å endre tema (bare oppdater CSS-variablene)
- ✔ Moderne og profesjonelt

---

## 9. Tailwind 4 + Laravel (ditt oppsett)

Siden du bruker Laravel med Breeze og Vite, settes Tailwind 4 opp slik:

### Trinn:

**1. Installer Tailwind:**
```bash
npm install tailwindcss@latest
```

**2. Opprett/oppdater `resources/css/app.css`:**
```css
@import "tailwindcss";

/* Dine egne design tokens */
:root {
  --primary-color: #2563eb;
  --background-color: #0f172a;
}
```

**3. Sjekk at `resources/js/app.js` importerer CSS:**
```javascript
import '../css/app.css';
```

**4. Kjør dev-server:**
```bash
npm run dev
```

**5. Åpne appen:**
```
https://prosjekt.test
```

**Ferdig!** Tailwind-styling fungerer nå.

---

## 10. Vanlige problemer og løsninger

### ❌ Tailwind-klasser virker ikke

**Problem:** Sidene vises uten styling

**Løsning:**
1. Sjekk at `resources/css/app.css` inneholder:
   ```css
   @import "tailwindcss";
   ```

2. Sjekk at `resources/js/app.js` importerer CSS:
   ```javascript
   import '../css/app.css';
   ```

3. Sjekk at `npm run dev` kjører

---

### ❌ CSS oppdateres ikke når jeg gjør endringer

**Problem:** Endringer i CSS vises ikke i nettleseren

**Løsning:**
1. Stopp `npm run dev` (Ctrl+C)
2. Kjør på nytt:
   ```bash
   npm run dev
   ```

3. Hard-refresh nettleseren (Ctrl+Shift+R)

---

### ❌ Komponenter ser rare ut / styling ser dårlig ut

**Problem:** Tailwind-styling ser ut som før, eller er helt borte

**Løsning:**
- Tailwind 4 har ryddet opp i gamle utilities fra versjon 1/2/3
- Hvis du bruker gamle guides på nett, **stol ikke på dem**
- Bruk [offisiell Tailwind 4-dokumentasjon](https://tailwindcss.com/docs)

---

### ❌ Hvordan legger jeg til plugins?

**Problem:** Jeg vil bruke Tailwind-plugins

**Løsning:**
Tailwind 4 har ikke plugin-system slik før. Bruk i stedet:
- ✔ Inline styling (arbitrary values)
- ✔ CSS-variabler
- ✔ Egendefinert CSS
- ✔ Custom utilities i CSS-filen

Eksempel på custom utility i CSS:

```css
@import "tailwindcss";

@layer utilities {
  .my-custom-button {
    @apply px-4 py-2 bg-blue-600 text-white rounded-lg;
  }
}
```

---

## 11. Minimal Starter-pakke for hvert prosjekt

Her er en minimal CSS-fil du kan kopiere hver gang du starter nytt:

```css
/* resources/css/app.css */
@import "tailwindcss";

/* Globale design tokens / brand colors */
:root {
  --brand-primary: #2563eb;
  --brand-secondary: #ec4899;
  --brand-dark: #0f172a;
  --brand-light: #f8fafc;
  --brand-card: #1e293b;
  --brand-text-primary: #f8fafc;
  --brand-text-secondary: #cbd5e1;
}

/* Custom utilities eksempel */
@layer utilities {
  .btn-primary {
    @apply px-4 py-2 bg-[var(--brand-primary)] text-white rounded-lg font-medium hover:opacity-90 transition;
  }
  
  .card {
    @apply bg-[var(--brand-card)] text-[var(--brand-text-primary)] p-6 rounded-lg shadow-lg;
  }
}
```

**Og i HTML:**

```blade
<div class="card">
    <h2 class="text-[var(--brand-primary)] font-bold mb-4">
        Tittel
    </h2>
    <p class="text-[var(--brand-text-secondary)] mb-4">
        Beskrivelse
    </p>
    <button class="btn-primary">
        Klikk meg
    </button>
</div>
```

---

## 12. Responsivt design med Tailwind 4

Tailwind 4 har innebygde breakpoints for responsivt design:

### Mobile-first approach:

```blade
<div class="text-sm md:text-base lg:text-lg xl:text-xl">
    Teksten endrer størrelse basert på skjermstørrelse
</div>
```

### Breakpoints:
- `sm:` → 640px
- `md:` → 768px
- `lg:` → 1024px
- `xl:` → 1280px
- `2xl:` → 1536px

**Eksempel - responsive layout:**

```blade
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    <div class="card">Kort 1</div>
    <div class="card">Kort 2</div>
    <div class="card">Kort 3</div>
</div>
```

---

## 13. Dark Mode med Tailwind 4

Tailwind 4 har innebygd dark mode-support:

### I CSS:

```css
@import "tailwindcss";

@media (prefers-color-scheme: dark) {
  :root {
    --brand-dark: #f8fafc;
    --brand-light: #0f172a;
  }
}
```

### I HTML:

```blade
<div class="bg-[var(--brand-light)] text-[var(--brand-dark)]">
    Denne endrer farge basert på systeminnstillingene
</div>
```

---

## 14. Performance tips

### ✔ Bruk Tailwind i produksjon:
```bash
npm run build
```

Dette minifiserer CSS maksimalt (fra ~100KB til ~20-30KB).

### ✔ Bruk CSS-variabler for globale farger:
Gjør det lettere å endre tema og reduserer redundans.

### ✔ Bruk custom utilities for hyppig brukte kombinasjoner:

```css
.btn-primary {
  @apply px-4 py-2 bg-blue-600 text-white rounded-lg;
}
```

### ✔ Unngå inline styling når mulig:
```blade
<!-- Bra -->
<div class="btn-primary">Knapp</div>

<!-- Dårlig - unødvendig inline -->
<div class="px-4 py-2 text-white bg-blue-600 rounded-lg">Knapp</div>
```

---

## 15. Neste steg

Nå som du forstår Tailwind 4 kan du:

✔ Les [ALPINE_GUIDE.md](./ALPINE_GUIDE.md) for interaktivitet

✔ Les [FILE_STRUCTURE.md](../summaries/FILE_STRUCTURE.md) for prosjektstruktur

✔ Begynn å lage vakre UI-er med Tailwind + Alpine

✔ Lese [offisiell Tailwind-dokumentasjon](https://tailwindcss.com/docs)

---

## Rask sjekkliste

- ✔ Tailwind 4 installert (`npm install tailwindcss@latest`)
- ✔ `resources/css/app.css` har `@import "tailwindcss";`
- ✔ `resources/js/app.js` importerer CSS-filen
- ✔ `npm run dev` kjører
- ✔ Tailwind-klasser fungerer i Blade-filer
- ✔ Design tokens definert (valgfritt, men anbefalt)
- ✔ Du er klar til å style! 🎨

---

**Tips:** Tailwind 4 er kraftig og enkelt - utforsk dokumentasjonen for å finne flere muligheter!

Last updated: November 28, 2025
