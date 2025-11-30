# ReadySoft Project - File Descriptions

En detaljert forklaring av hver fil i prosjektet. Siden du er ny til Laravel, har jeg inkludert enkle forklaringer av hva hver fil gjør og hvorfor den er der.

---

## 🔧 Root Level Configuration Files

### `artisan`
**Hva det er:** Et PHP-skript som er Laravel sitt kommandolinje-verktøy (CLI).

**Hva det gjør:** Lar deg kjøre kommandoer for å administrere appen din fra terminalen, f.eks:
- `php artisan serve` - starter webserveren
- `php artisan migrate` - kjører database-endringer
- `php artisan make:controller` - lager nye kontrollers

**Hvorfor det trengs:** Det er som "remote control" for å kontrollere Laravel-appen fra kommandolinjen.

---

### `composer.json` & `composer.lock`
**Hva det er:** Composer er PHP sin "package manager" (pakkebehandler), som npm/yarn for Node.js.

**`composer.json`:**
- Liste over alle PHP-pakker som prosjektet trenger (f.eks Laravel, Pest, etc.)
- Definerer versjoner av pakker
- Du endrer denne når du vil legge til nye PHP-pakker

**`composer.lock`:**
- En "fryst" versjon som sikrer at samme pakker installeres hver gang
- Brukes av Git til å holde alle utviklere på samme versjon
- **IKKE rediger denne manuelt** - den oppdateres automatisk av Composer

**Hvorfor det trengs:** For å håndtere alle PHP-avhengigheter i prosjektet på en organisert måte.

---

### `package.json` & `package-lock.json`
**Hva det er:** npm sin konfigurasjonsfil, tilsvarende composer.json men for JavaScript/Node.js.

**`package.json`:**
- Liste over alle npm-pakker (Tailwind CSS, Vite, Alpine.js, osv)
- Definerer NPM-skript som `npm run dev` og `npm run build`
- Du endrer denne når du vil legge til nye JavaScript-pakker

**`package-lock.json`:**
- Sikrer at samme versjon av pakker installeres hver gang
- Tilsvarende composer.lock

**Hvorfor det trengs:** For å håndtere alle frontend-avhengigheter (CSS, JavaScript, byggetools).

---

### `phpunit.xml`
**Hva det er:** Konfigurasjonen for Pest, som er testverktøyet ditt.

**Hva det gjør:** Definerer:
- Hvilken database skal brukes for testing
- Hvor test-filene befinner seg
- Miljøvariabeler for testing

**Hvorfor det trengs:** For å kunne kjøre `php artisan test` eller `./vendor/bin/pest` og teste koden din.

---

### `vite.config.js`
**Hva det er:** Konfigurasjonen for Vite, som er "build tool" for frontend-koden din.

**Hva det gjør:**
- Definerer at Tailwind CSS skal kompileres
- Definerer at JavaScript skal bundlizeres (slåes sammen til færre filer)
- Definerer hvor output-filene skal lagres (public/build)
- Setter opp dev-server for rask utvikling

**Hvorfor det trengs:** For å konvertere dine CSS- og JS-filer til optimalisert, produksjonsklar format.

---

### `postcss.config.js`
**Hva det er:** Konfigurasjonen for PostCSS, som prosesserer CSS.

**Hva det gjør:**
- Instruerer Tailwind CSS om å skanne alle `.php` og `.js`-filene for Tailwind-klasser
- Fjerner CSS som ikke brukes (tree-shaking)
- Gjør CSS mindre og raskere

**Hvorfor det trengs:** For at Tailwind CSS skal fungere og for å gjøre CSS-filen så liten som mulig.

---

### `tailwind.config.js`
**Hva det er:** Konfigurasjonen for Tailwind CSS-ramme.

**Hva det gjør:**
- Definerer hvilke farger, fonter og andre design-tokens som skal brukes
- Definerer custom styling-regler
- Konfigurerer responsive breakpoints

**Hvorfor det trengs:** For å tilpasse Tailwind CSS til ditt prosjekts design.

---

### `.editorconfig`
**Hva det er:** En fil som definerer editor-innstillinger.

**Hva det gjør:**
- Definerer at alle filer skal bruke 4 mellomrom for indentering
- Definerer file encoding (UTF-8)
- Sikrer at alle utviklere bruker samme formattering

**Hvorfor det trengs:** For at koden skal se lik ut uansett hvilken editor du bruker.

---

### `.env` & `.env.example`
**Hva det er:** Environment (miljø) variabler - hemmeligheter og innstillinger.

**`.env.example`:**
- En template som viser hvilke variabler som trengs
- **ALDRI committest til Git** - det er bare en mal

**`.env`:**
- Din lokale kopi med faktiske verdier
- Inneholder sensitivt som:
  - Database-passord
  - API-nøkler
  - App-navn og secret
- **ALDRI committest til Git** - opprettet lokalt

**Eksempler på variabler:**
```
APP_NAME=ReadySoft                  # Navn på appen
APP_ENV=local                       # Miljø (local, production, etc)
APP_DEBUG=true                      # Vis feil på nettsiden (bare lokalt!)
DB_HOST=localhost                   # Database-server
DB_PASSWORD=secret123               # Database-passord
```

**Hvorfor det trengs:** For å lagre hemmeligheter og innstillinger som ikke skal være i koden.

---

### `.gitignore`
**Hva det er:** En fil som sier til Git hvilke filer som IKKE skal lagres i Git.

**Inneholder typisk:**
- `/vendor` - PHP-pakker
- `/node_modules` - JavaScript-pakker
- `.env` - hemmeligheter
- `/storage/logs` - loggfiler
- `/public/build` - kompilerte filer

**Hvorfor det trengs:** For å ikke fylle Git-repoet ditt med store mapper og hemmeligheter.

---

### `.gitattributes`
**Hva det er:** Instruksjoner til Git om hvordan håndtere visse filer.

**Eksempler:**
- Forteller Git at `.sh`-skript skal ha Unix-linjeskift (ikke Windows)
- Forteller Git å ikke exportere test-mapper

**Hvorfor det trengs:** For at Git skal håndtere filer korrekt uansett operativsystem.

---

### `.nvmrc`
**Hva det er:** En fil som definerer hvilken Node.js versjon prosjektet bruker.

**Innhold:** Vanligvis noe som `18.17.0` eller `20.0.0`

**Hvorfor det trengs:** For at alle utviklere bruker samme Node.js versjon (hvis de bruker nvm).

---

### `README.md`
**Hva det er:** Dokumentasjonen for prosjektet.

**Inneholder:**
- Oversikt over hva prosjektet er
- Hvordan installere det
- Hvordan kjøre det
- Bidragsregler

**Hvorfor det trengs:** For at andre (eller du selv senere) skal forstå prosjektet.

---

## 📁 App-mappen - Din Business Logic

### `app/Http/Controllers/Controller.php`
**Hva det er:** Base-klassen som alle dine kontrollers arver fra.

**Hva det gjør:**
- Inneholder felles funksjonalitet for alle kontrollers
- Typisk tomt i nye prosjekter, men kan brukes for delt logikk

**Hvorfor det trengs:** Som en "parent class" for alle kontrollers.

---

### `app/Http/Controllers/ProfileController.php`
**Hva det er:** Kontroller for bruker-profil-funksjonalitet.

**Hva det gjør:**
- Viser bruker-profilsiden
- Håndterer oppdatering av profilinformasjon
- Håndterer sletting av brukerkonto

**Metoder:**
- `edit()` - viser redigeringssiden
- `update()` - lagrer endringer
- `destroy()` - sletter brukeren

**Hvorfor det trengs:** For å organisere all profil-relatert logikk på ett sted.

---

### `app/Http/Controllers/Auth/` - Autentiserings-Kontrollers
**Hva det er:** Kontrollers for login, registrering, og passordhåndtering.

#### `AuthenticatedSessionController.php`
- Håndterer login og logout
- `store()` - logger inn brukeren
- `destroy()` - logger ut brukeren

#### `RegisteredUserController.php`
- Håndterer bruker-registrering
- `store()` - oppretter ny bruker

#### `PasswordResetLinkController.php`
- Håndterer "jeg glemte passordet" funksjonen
- `store()` - sender reset-link til epostadresse

#### `NewPasswordController.php`
- Håndterer resetting av passord
- `store()` - lagrer nytt passord

#### `PasswordController.php`
- Håndterer endring av passord for innlogget bruker

#### `EmailVerificationPromptController.php` & `VerifyEmailController.php`
- Håndterer epostverifikasjon
- `VerifyEmailController` - markerer eposten som verifisert

#### `ConfirmablePasswordController.php` & `EmailVerificationNotificationController.php`
- Hjelpekontrollers for sikkerhet og verifikasjon

**Hvorfor det trengs:** For å separere all autentisering fra resten av koden.

---

### `app/Http/Requests/`
**Hva det er:** "Form Requests" - validering av data som kommer fra brukeren.

#### `Auth/LoginRequest.php`
**Hva det gjør:**
- Validerer at email-adressen er gyldig
- Validerer at passordet er utfylt
- Validerer at brukeren eksisterer og passordet er riktig

**Hvorfor det trengs:** For å sikre at bare gyldig data blir behandlet.

#### `ProfileUpdateRequest.php`
**Hva det gjør:**
- Validerer at navn er utfylt
- Validerer at ny epostadresse (hvis endret) er unik

**Hvorfor det trengs:** For å sikre at profil-data er gyldig før den lagres.

---

### `app/Models/User.php`
**Hva det er:** Brukeren-modellen - representerer en bruker i databasen.

**Hva det gjør:**
- Definerer hvilke felt en bruker har (name, email, password, etc)
- Definerer relasjoner (f.eks user -> posts)
- Inneholder brukerspesifikk logikk

**Eksempler på metoder:**
- `hashPassword()` - krypter passord
- `isVerified()` - sjekk om eposten er verifisert

**Hvorfor det trengs:** For å representere og håndtere brukerdata på en strukturert måte.

---

### `app/Providers/AppServiceProvider.php`
**Hva det er:** En "Service Provider" - en fil som kjøres ved startup av appen.

**Hva det gjør:**
- Registrerer tjenester som hele appen kan bruke
- Konfigurerer databasekonektor, mail-tjeneste, osv
- Utføres når appen starter opp

**Hvorfor det trengs:** For å initialisere komponenter som trengs i hele appen.

---

### `app/View/Components/AppLayout.php` & `GuestLayout.php`
**Hva det er:** PHP-klasser som representerer Blade-komponenter.

**Hva de gjør:**
- `AppLayout.php` - layouten for innlogget brukere (med navigasjon, etc)
- `GuestLayout.php` - layouten for fremmede brukere (login/register-sider)

**Hvorfor det trengs:** For å ha gjenbrukbar struktur for forskellige sidetyper.

---

## 🗂️ Bootstrap-mappen

### `bootstrap/app.php`
**Hva det er:** Der Laravel-applikasjonen blir initialisert.

**Hva det gjør:**
- Starter Laravel-konteineren
- Registrerer globale exception handlers
- Setter opp terminalkommandoer

**Hvorfor det trengs:** Det er "hjerteslaget" av Laravel-appen - alle startar her.

---

### `bootstrap/providers.php`
**Hva det er:** Liste over alle Service Providers som skal lastes.

**Inneholder:** Service providers som skal kjøres ved startup.

---

### `bootstrap/cache/`
**Hva det er:** Cache-filer som Laravel lager for raskere oppstart.

**Filer:**
- `packages.php` - cacher liste over alle pakker
- `services.php` - cacher servicekonfigurasjonen

**Viktig:** Disse blir automatisk regenerert, du trenger ikke endre dem.

---

## ⚙️ Config-mappen - Konfigurasjonsfiler

### `config/app.php`
**Hva det er:** Hovedkonfigurasjonen for hele appen.

**Inneholder:**
```php
'name' => 'ReadySoft',              // Appens navn
'env' => 'local',                   // local, production, testing
'debug' => true,                    // Vis feilmeldinger?
'timezone' => 'UTC',                // Tidssone
'locale' => 'en',                   // Språk
```

**Hvorfor det trengs:** Sentralisert plass for appens globale innstillinger.

---

### `config/database.php`
**Hva det er:** Konfigurasjonen for databasen.

**Inneholder:**
- Database-driver (MySQL, PostgreSQL, SQLite, etc)
- Database-host, bruker, passord
- Databasenavn

**Eksempel:**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE', 'readysoft'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
]
```

**Hvorfor det trengs:** For at Laravel skal vite hvordan koble til databasen.

---

### `config/auth.php`
**Hva det er:** Konfigurasjonen for autentisering (login).

**Inneholder:**
- Hvilken "guard" skal brukes (web, api, etc)
- Hvilken "provider" skal brukes (users, osv)
- Timeout-innstillinger

**Hvorfor det trengs:** For å konfigurere hvordan Laravel håndterer innlogging.

---

### `config/cache.php`
**Hva det er:** Konfigurasjonen for caching.

**Inneholder:**
- Hvilken cache-driver skal brukes (file, redis, memcached, etc)
- Cache-navn
- TTL (Time To Live - hvor lenge cache skal vare)

**Hvorfor det trengs:** For å konfigurere caching som brukes for å raskere appen.

---

### `config/session.php`
**Hva det er:** Konfigurasjonen for brukersesjoner (holder brukeren innlogget).

**Inneholder:**
- Hvor sesjoner skal lagres (file, cookie, database, etc)
- Hvor lenge sesjoner varer
- Session-cookies navn

**Hvorfor det trengs:** For å konfigurere hvordan Laravel holder brukere innlogget.

---

### `config/mail.php`
**Hva det er:** Konfigurasjonen for sending av epost.

**Inneholder:**
- Mail-driver (SMTP, Mailgun, SendGrid, etc)
- Mail-server adresse, bruker, passord
- Default "from" adresse

**Hvorfor det trengs:** For å sende epost (passord-reset, varslinger, etc).

---

### `config/filesystems.php`, `config/logging.php`, `config/queue.php`, `config/services.php`
**Hva de er:** Diverse konfigurasjoner for:
- Fillagring
- Logging (skrive loggfiler)
- Queue jobs (bakgrunnsjobber)
- Eksterne tjenester (API-nøkler, etc)

---

## 📊 Database-mappen

### `database/migrations/`
**Hva det er:** Filer som beskriver databasen-strukturen.

**Hva de gjør:**
- `0001_01_01_000000_create_users_table.php` - lager "users"-tabellen
- `0001_01_01_000001_create_cache_table.php` - lager cache-tabellen
- `0001_01_01_000002_create_jobs_table.php` - lager job-kø tabellen

**Eksempel på innhold:**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();                           // Auto-increment ID
    $table->string('name');                 // Navn
    $table->string('email')->unique();      // Epost (unikt)
    $table->timestamp('email_verified_at'); // Når epost ble verifisert
    $table->string('password');             // Passord
    $table->timestamps();                   // created_at, updated_at
});
```

**Hvorfor det trengs:** For å versionere og automatisere database-endringer. Du kjører `php artisan migrate` for å anvende migrasjoner.

---

### `database/factories/UserFactory.php`
**Hva det er:** En "factory" som lager dummy brukere for testing.

**Hva den gjør:**
```php
User::factory()->create();  // Lager en fake bruker med tilfeldig data
```

**Hvorfor det trengs:** For å kunne lage test-data raskt når du tester.

---

### `database/seeders/DatabaseSeeder.php`
**Hva det er:** En "seeder" som fyller databasen med initiale data.

**Hva den gjør:**
- Kan lage admin-brukere
- Kan lage test-data
- Kjøres med `php artisan db:seed`

**Hvorfor det trengs:** For å ha startdata i databasen uten å legge inn det manuelt.

---

## 📚 Resources-mappen - Frontend

### `resources/css/app.css`
**Hva det er:** Main CSS-fil for hele appen.

**Inneholder:**
```css
@tailwind base;           /* Base Tailwind styles */
@tailwind components;     /* Tailwind component classes */
@tailwind utilities;      /* Tailwind utility classes */
```

**Hvorfor det trengs:** For å importere Tailwind CSS og legge til custom CSS.

---

### `resources/js/app.js`
**Hva det er:** Main JavaScript-fil som lastes på alle sider.

**Inneholder:**
- Importer av Alpine.js
- Importer av Axios
- Initialisering av JavaScript-komponenter

**Hvorfor det trengs:** For å starte JavaScript-funksjonalitet på alle sider.

---

### `resources/js/bootstrap.js`
**Hva det er:** Bootstrap-fil som setter opp JavaScript-biblioteker.

**Inneholder:**
```js
window.axios = axios;  // Gjør Axios tilgjengelig globalt
```

**Hvorfor det trengs:** For å konfigurere JavaScript-biblioteker ved startup.

---

### `resources/views/` - Blade Templates
**Hva det er:** HTML-filer som viser innhold til brukeren. Bruker Blade-syntaks.

#### `welcome.blade.php`
- Hjemmesiden/landingssiden
- Vises når brukeren besøker `http://localhost:8000`

#### `dashboard.blade.php`
- Dashboard-siden for innlogget brukere
- Vises når brukeren besøker `/dashboard`

#### `auth/` - Autentiserings-sider
- `login.blade.php` - Login-skjemaet
- `register.blade.php` - Registrerings-skjemaet
- `forgot-password.blade.php` - "Jeg glemte passordet" side
- `reset-password.blade.php` - Sett nytt passord side
- `confirm-password.blade.php` - Bekreft passord side
- `verify-email.blade.php` - Epostverifikasjon side

#### `layouts/` - Layout-komponenter
- `app.blade.php` - Hovedlayouten for hele appen (header, footer, etc)
- `guest.blade.php` - Layouten for login/register-sider
- `navigation.blade.php` - Navigasjonsmenyen

#### `profile/` - Profil-sider
- `edit.blade.php` - Siden for å redigere profilen
- `partials/update-profile-information-form.blade.php` - Skjema for navn/epost
- `partials/update-password-form.blade.php` - Skjema for å endre passord
- `partials/delete-user-form.blade.php` - Skjema for å slette konto

#### `components/` - Gjenbrukbare Blade-komponenter
- `text-input.blade.php` - Tekst-input felt
- `primary-button.blade.php` - Blå knapp
- `danger-button.blade.php` - Rød knapp
- `input-label.blade.php` - Form-etikett
- `input-error.blade.php` - Feilmelding
- `dropdown.blade.php` - Dropdown-meny
- `modal.blade.php` - Popup-dialog
- `nav-link.blade.php` - Navigasjonslenkner
- osv...

**Blade-syntaks:**
```blade
{{ $variable }}           {{-- Vis variabel --}}
@if ($condition)         {{-- If-statement --}}
@foreach ($items as $item)
    {{ $item }}
@endforeach

@include('component')    {{-- Inkluder annen view --}}
<x-primary-button />     {{-- Bruk Blade-komponent --}}
```

**Hvorfor det trengs:** For å vise HTML til brukeren. Blade lar deg blande HTML med PHP-logikk.

---

## 🛣️ Routes-mappen

### `routes/web.php`
**Hva det er:** Definerer alle web-rutene (URL-ene) i appen.

**Eksempler:**
```php
Route::get('/', function () {
    return view('welcome');
});                                    // GET / -> vis welcome-siden

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');               // GET /dashboard -> vis dashboard (må være innlogget)

Route::get('/profile', [ProfileController::class, 'edit'])
    ->middleware('auth');             // GET /profile -> vis profil-redigeringssiden
```

**Hvorfor det trengs:** For å definere alle URLene brukeren kan besøke og hvilken kode som skal kjøres.

---

### `routes/auth.php`
**Hva det er:** Alle autentiserings-rutene (login, register, password reset).

**Eksempler:**
```php
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
```

**Hvorfor det trengs:** For å separere autentiserings-rutene fra andre ruter.

---

### `routes/console.php`
**Hva det er:** Definerer custom konsoll-kommandoer.

**Eksempler:**
```php
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quotes()->random());
});
```

**Hvorfor det trengs:** For å opprette custom `php artisan`-kommandoer.

---

## 📦 Storage-mappen

### `storage/app/`
**Hva det er:** Bruker-opploadet filer lagres her.

**Undermapper:**
- `public/` - Filer som skal være offentlig tilgjengelig
- `private/` - Private filer (passord-reset tokens, osv)

**Hvorfor det trengs:** For å lagre filer som brukere laster opp eller som appen genererer.

---

### `storage/framework/`
**Hva det er:** Laravel-interne filer.

**Undermapper:**
- `cache/` - Cache-data (raskere tilgang til data)
- `sessions/` - Bruker-sesjoner (holder brukere innlogget)
- `testing/` - Temp-filer under testing
- `views/` - Kompilerte Blade-templates (for raskere ytelse)

**Hvorfor det trengs:** For Laravel sin interne funksjonalitet.

---

### `storage/logs/`
**Hva det er:** Loggfiler som registrerer hva som skjer i appen.

**Filer:**
- `laravel.log` - Applikasjonens logg
- `browser.log` - Nettleser-test logg

**Innhold eksempler:**
```
[2025-11-28 10:30:00] local.ERROR: Exception occurred: ...
[2025-11-28 10:31:05] local.INFO: User logged in: user@example.com
```

**Hvorfor det trengs:** For debugging og å forstå hva som skjedde hvis noe gikk galt.

---

## 🧪 Tests-mappen

### `tests/TestCase.php`
**Hva det er:** Base-klassen for alle tester.

**Inneholder:**
- Felles setup for alle tester
- Helper-metoder som alle tester kan bruke
- Databasekonfigurering for testing

**Hvorfor det trengs:** For å ikke måtte gjenta konfigurering i hver test.

---

### `tests/Pest.php`
**Hva det er:** Global Pest-konfigurering.

**Inneholder:**
- Helper-funksjoner som alle tester kan bruke
- Global setup/teardown

**Hvorfor det trengs:** For tilgjengelige hjelpere i alle tester.

---

### `tests/Feature/`
**Hva det er:** Feature-tester tester hele funksjonaliteten end-to-end.

**Eksempler:**
- `AuthenticationTest.php` - Tester at login/logout fungerer
- `RegistrationTest.php` - Tester at registrering fungerer
- `PasswordResetTest.php` - Tester at passordresett fungerer
- `ProfileTest.php` - Tester profilredigering

**Hvordan de fungerer:**
```php
public function test_user_can_login()
{
    $user = User::factory()->create();
    
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);
    
    $this->assertAuthenticated();  // Sjekk at brukeren er innlogget
}
```

**Hvorfor det trengs:** For å teste at hele funksjoner fungerer korrekt.

---

### `tests/Unit/`
**Hva det er:** Unit-tester tester små, isolerte deler av koden.

**Eksempler:**
- `ExampleTest.php` - Eksempel unit-test

**Hvordan de fungerer:**
```php
public function test_addition()
{
    $this->assertEquals(2, 1 + 1);
}
```

**Hvorfor det trengs:** For å teste små funksjoner isolert, uten å testa hele systemet.

---

### `tests/Feature/Auth/`
**Hva det er:** Feature-tester spesifikt for autentiserings-funksjonalitet.

**Inneholder:**
- `AuthenticationTest.php` - Login/logout
- `RegistrationTest.php` - Registrering
- `EmailVerificationTest.php` - Epostverifikasjon
- `PasswordResetTest.php` - Passordresett
- `PasswordUpdateTest.php` - Passordendring
- `PasswordConfirmationTest.php` - Passordbekreftelse

**Hvorfor det trengs:** For å separere autentiserings-tester fra andre tester.

---

## 🌐 Public-mappen - Web Root

### `public/index.php`
**Hva det er:** Inngangsportalen til appen.

**Hva den gjør:**
- Det første som kjøres når noen besøker appen
- Laster Bootstrap og starter Laravel
- Ruter all trafikk til Laravel

**Viktig:** Alle requests går gjennom denne filen.

**Hvorfor det trengs:** Det er entry point for hele appen.

---

### `public/.htaccess`
**Hva det er:** Apache-konfigurasjonsregler.

**Hva den gjør:**
- Omdirigerer all trafikk til `index.php`
- Aktiverer URL-rewriting
- Sikrer at filer ikke kan lastes direkte

**Eksempel:**
```
GET /profile     ->  index.php -> rutes/web.php -> ProfileController
GET /image.jpg   ->  Lastes direkte fra public/ (ikke omdirigert)
```

**Hvorfor det trengs:** For at Laravel skal kunne håndtere alle URLer.

---

### `public/favicon.ico`
**Hva det er:** Ikonet som vises i browserens fane.

**Hvorfor det trengs:** For at nettsiden din ser profesjonell ut.

---

### `public/robots.txt`
**Hva det er:** Instruksjoner til søkemotorer om hva de skal indeksere.

**Eksempel:**
```
User-agent: *
Disallow: /admin  # Ikke indekser admin-siden
Allow: /          # Indekser alt annet
```

**Hvorfor det trengs:** For å kontrollere hva Google og andre søkemotorer indekserer.

---

### `public/build/`
**Hva det er:** Kompilerte CSS og JavaScript filer.

**Inneholder:**
- `manifest.json` - Oversikt over alle kompilerte filer
- `assets/app-c0myYa0N.css` - Kompilert CSS (hash i navn = versjonering)
- `assets/app-CJy8ASEk.js` - Kompilert JavaScript

**Hvorfor det trengs:** Når du kjører `npm run build`, blir CSS og JS kompilert hit. Disse filene er optimisert for produksjon.

---

## 📋 Oppsummering av Dataflyt

```
1. Bruker besøker http://localhost:8000
   ↓
2. Apache omdirigerer til public/index.php
   ↓
3. Laravel starter (bootstrap/app.php)
   ↓
4. Laravel sjekker routes/web.php for matching rute
   ↓
5. Ruten kjører en Controller-metode
   ↓
6. Kontrolleren henter data fra Model/Database
   ↓
7. Kontrolleren returnerer en View
   ↓
8. View (Blade-template) blir rendret til HTML
   ↓
9. Browser viser HTML til bruker
```

---

## 🚀 Når du starter å kode

**Typisk arbeidsflyt:**
1. **Lage en rute** i `routes/web.php`
2. **Lage en controller** med `php artisan make:controller MyController`
3. **Lage en model** med `php artisan make:model MyModel`
4. **Lage en view** i `resources/views/`
5. **Lage en test** i `tests/Feature/`
6. **Kjøre testen** med `php artisan test`
7. **Implementere funksjonen** i kontroller/model
8. **Testen skal passere**

---

Last updated: November 28, 2025
