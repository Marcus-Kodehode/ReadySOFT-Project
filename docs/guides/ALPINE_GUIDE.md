# 📚 ALPINE.JS v3 – Definitiv Guide

**For enkel interaktivitet uten å røre React, Vue eller tunge rammeverk**

---

## 1. Hva Alpine.js brukes til

Alpine er **"Tailwind for JavaScript"** — enkel, lett og kraftig.

### Typiske bruksomåder:
- ✔ Små UI-ting
- ✔ Toggles
- ✔ Dropdowns
- ✔ Modaler
- ✔ Tabs
- ✔ Animasjoner
- ✔ Reactive states

**Alt uten å bygge en hel SPA.**

Det er perfekt for Laravel Blade-prosjekter.

---

## 2. Hvordan Alpine er installert i Breeze

Når du kjører:

```bash
php artisan breeze:install blade
```

→ legges dette automatisk inn i `resources/js/app.js`:

```javascript
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

### Det betyr:
- ✔ Alpine aktiveres globalt
- ✔ Kan brukes i alle Blade-filer
- ✔ Ingen ekstra config
- ✔ Ingen bundling-problemer (Vite tar hele jobben)

---

## 3. Hvordan du bruker Alpine i Blade

Du trenger **KUN HTML** — ingen JS-filer, ingen imports, ingenting.

### Eksempel: Toggle

```blade
<div x-data="{ open: false }">
    <button x-on:click="open = !open">
        Toggle
    </button>

    <p x-show="open">
        Hei Alpine!
    </p>
</div>
```

**Dette er grunnlaget for alt.**

---

## 4. Vanligste Alpine-direktivene

Du bruker disse **90% av tiden**.

### `x-data`
Oppretter en Alpine-komponent:
```blade
<div x-data="{ count: 0 }">
    <!-- Alt her er inne i komponenten -->
</div>
```

### `x-on:click` (eller `@click`)
Event-håndtering:
```blade
<button @click="count++">+</button>
```

### `x-show`
Vise/skjule elementer:
```blade
<div x-show="isOpen">
    Dette vises når isOpen er true
</div>
```

### `x-bind` (eller `:`)
Binde attributter:
```blade
<img :src="imageUrl">
```

### `x-text`
Sett innerText automatisk:
```blade
<p x-text="count"></p>
```

### `x-model`
Two-way binding (f.eks. input):
```blade
<input type="text" x-model="name">
<p>Hei <span x-text="name"></span>!</p>
```

### `x-transition`
Små animasjoner:
```blade
<div x-show="open" x-transition>
    Animert dukker opp/forsvinner
</div>
```

---

## 5. Det viktigste prinsippet: x-data er "component scope"

Alt du definerer i `x-data` lever **KUN inne i den div-en**.

### Eksempel:

```blade
<div x-data="{ count: 0 }">
    <button @click="count++">+</button>
    <p x-text="count"></p>
</div>
```

**Alt fungerer uten JS-filer — ren HTML.**

---

## 6. Alpine komponenter du kommer til å bruke mye

### Dropdown

```blade
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open">Meny</button>

    <div x-show="open" 
         @click.outside="open = false"
         class="absolute right-0 p-2 mt-2 bg-white rounded shadow">
         <!-- Dropdown items her -->
    </div>
</div>
```

### Modal

```blade
<div x-data="{ open: false }">
    <button @click="open = true">Åpne modal</button>

    <div x-show="open" 
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black/50">

        <div class="p-6 bg-white rounded shadow"
             @click.outside="open = false">
            Hei!
        </div>
    </div>
</div>
```

### Tabs

```blade
<div x-data="{ tab: 'a' }">
    <button @click="tab = 'a'">Tab A</button>
    <button @click="tab = 'b'">Tab B</button>

    <div x-show="tab === 'a'">Innhold A</div>
    <div x-show="tab === 'b'">Innhold B</div>
</div>
```

### Form State (enkelt)

```blade
<div x-data="{ name: '', email: '' }">
    <input x-model="name">
    <input x-model="email">
</div>
```

---

## 7. Alpine + Tailwind → perfekt kombo

En dropdown med Tailwind og Alpine:

```blade
<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" 
            class="px-4 py-2 text-white bg-blue-600 rounded">
        Actions
    </button>

    <div x-show="open" 
         @click.outside="open = false"
         class="absolute w-48 p-2 mt-2 bg-white border border-gray-100 rounded shadow">
        <button class="w-full px-2 py-1 text-left hover:bg-gray-100">Rediger</button>
        <button class="w-full px-2 py-1 text-left hover:bg-gray-100">Slett</button>
    </div>
</div>
```

**Dette ville krevd 50+ linjer JS uten Alpine.**

---

## 8. Alpine + Laravel Blade @props / @foreach

Alpine spiller ekstremt bra med Blade.

### Eksempel med data fra kontroller:

```blade
<div x-data="{ items: @json($users) }">
    <template x-for="user in items">
        <p x-text="user.name"></p>
    </template>
</div>
```

**Dette er supernyttig i dashboards og admin-paneler.**

---

## 9. Alpine moduler (for større komponenter)

Du kan lage funksjoner i `x-data`:

```blade
<div x-data="dropdown()">
    <button @click="toggle()">Toggle</button>
    <div x-show="open">Hei!</div>
</div>

<script>
function dropdown() {
    return {
        open: false,
        toggle() {
            this.open = !this.open;
        }
    }
}
</script>
```

**Men til 95% av dine prosjekter er inline `x-data="{ ... }"` helt nok.**

---

## 10. Vanlige feil og løsninger

### ❌ Alpine fungerer ikke i det hele tatt

**Sjekk at du har dette i `resources/js/app.js`:**

```javascript
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

### ❌ x-show fungerer, men ingen animasjon

**Legg til `x-transition`:**

```blade
<div x-show="open" x-transition>
    Nå animeres det!
</div>
```

### ❌ Dropdown lukker ikke når man klikker utenfor

**Bruk:**

```blade
@click.outside="open = false"
```

### ❌ Data oppdateres ikke

**Husk:** Alpine reagerer kun på ting inne i samme `x-data`-scope.

---

## 11. Rask sjekkliste (når du setter opp nytt prosjekt)

- ✔ Breeze installert
- ✔ `resources/js/app.js` inneholder Alpine-importen
- ✔ `npm run dev` kjører
- ✔ Test med enkel toggle:

```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Test</button>
    <p x-show="open">Works!</p>
</div>
```

**Hvis det funker → alt er klart.**

---

Last updated: November 28, 2025
