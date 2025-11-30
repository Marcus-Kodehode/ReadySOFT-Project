# 📚 Laravel + Breeze – Komplett Oppstarts-Guide

**For nye prosjekter på Windows med Herd - enkel å gjenta, ryddig og moderne**

---

## 1. Sjekk at du har riktig miljø

Åpne terminal (Git Bash eller PowerShell) og kjør:

```bash
php -v
composer -V
node -v
npm -v
```

### Du trenger:
- ✔ **PHP** (fra Herd)
- ✔ **Composer** 
- ✔ **Node.js** (LTS versjon 18 eller 20)
- ✔ **npm**

Hvis noe mangler → installer Node LTS (18/20) fra [nodejs.org](https://nodejs.org/)

---

## 2. Lag nytt Laravel-prosjekt

Gå til mappa hvor du har prosjekter:

```bash
cd C:/Users/DittNavn/Code
```

Lag prosjekt med en av disse metodene:

**Metode 1 - med Composer:**
```bash
composer create-project laravel/laravel prosjekt_navn
```

**Metode 2 - med Laravel installer:**
```bash
laravel new prosjekt_navn
```

Gå inn i prosjektet:
```bash
cd prosjekt_navn
```

---

## 3. Laravel Wizard - velg riktig alternativer

Når Laravel spør, svar slik:

### ❓ Hvilken starter kit vil du installere?
```
➡ Velg: None
```
(Vi bruker Breeze etterpå.)

### ❓ Hvilken database skal appen bruke?
```
➡ Velg: MySQL
```

### ❓ Kjør default migrations?
```
➡ Velg: Yes
```

### ❓ Kjør npm install + npm run build?
```
➡ Velg: Yes
```
(Denne bygger bare en enkel produksjons-build, ikke dev-server enda.)

---

## 4. Legg prosjektet i Herd

Åpne **Herd** → **Sites** → **Add**

Velg prosjektmappa.

Herd gir deg nå en URL slik:

```
https://prosjekt_navn.test
```

Dette blir din lokale utviklingsadresse.

---

## 5. Lag database i MySQL Workbench

**Trinn:**
1. Åpne **MySQL Workbench**
2. Klikk **Local instance MySQL80**
3. Gå til fanen **Schemas**
4. Høyreklikk → **Create Schema**
5. Skriv databasenavnet ditt:
   ```
   prosjekt_navn
   ```
6. Klikk **Apply** → **Apply** → **Finish**

---

## 6. Oppdater `.env` fil

Finn `.env` filen i roten av prosjektet og sett disse verdiene:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prosjekt_navn
DB_USERNAME=root
DB_PASSWORD=DITT_PASSORD
```

Lagre filen.

### Test at databasen kobler til:

```bash
php artisan migrate
```

Hvis det kjøres uten feil → alt er satt opp korrekt! ✔

---

## 7. Installer Laravel Breeze

Installer Breeze som dev-dependency:

```bash
composer require laravel/breeze --dev
```

Kjør Breeze-installasjonen:

```bash
php artisan breeze:install blade
```

### Dette gir deg automatisk:
- ✔ Login/Registrering
- ✔ Dashboard
- ✔ Tailwind CSS
- ✔ Alpine.js
- ✔ Vite oppsett
- ✔ Blade templates

---

## 8. Installer NPM-pakker (frontend)

Installer alle JavaScript-avhengigheter:

```bash
npm install
```

Dette laster ned Tailwind, Alpine, Vite og andre pakker.

---

## 9. Start Vite Dev Server

Åpne en **ny terminal** og kjør:

```bash
npm run dev
```

Du vil se output slik:

```
VITE ready at https://prosjekt_navn.test:5173/
```

**La denne terminalen stå åpen mens du utvikler** - den gjenoppbygger CSS og JS automatisk når du gjør endringer.

---

## 10. Start Laravel-serveren

### Hvis du bruker Herd:
➡ **Bare åpne nettleseren** og gå til:
```
https://prosjekt_navn.test
```

Herd kjører serveren automatisk.

### Hvis du IKKE bruker Herd:
Kjør Laravel-serveren manuelt:
```bash
php artisan serve
```

Åpne så:
```
http://127.0.0.1:8000
```

---

## 11. Test Breeze-autentisering

**Sjekkliste:**
1. Gå til prosjektets URL
2. Klikk **Register**
3. Lag en ny brukerkonto
4. Logg inn med brukeren
5. Sjekk om du kommer til **Dashboard**

**Hvis JA** → hele stacken fungerer perfekt! 🎉

---

## 12. Filstruktur som Breeze gir deg

### Views (HTML-filer)
```
resources/views/
├── auth/
│   ├── login.blade.php              # Login-form
│   ├── register.blade.php           # Registrerings-form
│   ├── forgot-password.blade.php    # Passordresett
│   ├── reset-password.blade.php     # Nytt passord
│   ├── confirm-password.blade.php   # Bekreft passord
│   └── verify-email.blade.php       # Epostverifikasjon
├── layouts/
│   ├── app.blade.php                # Hovedlayout (innlogget)
│   ├── guest.blade.php              # Guest layout (login/reg)
│   └── navigation.blade.php         # Navigasjonsmeny
├── profile/
│   └── edit.blade.php               # Profil-redigering
└── dashboard.blade.php              # Dashboard-siden
```

### Controllers (Business Logic)
```
app/Http/Controllers/Auth/
├── AuthenticatedSessionController.php    # Login/logout
├── RegisteredUserController.php          # Registrering
├── PasswordResetLinkController.php       # Passordresett
├── NewPasswordController.php             # Nytt passord
└── ... (flere autentiserings-controllers)

app/Http/Controllers/
└── ProfileController.php                 # Profil-oppdateringer
```

### Routes (URL-mapping)
```
routes/
├── web.php                          # Web-ruter
└── auth.php                         # Autentiserings-ruter
```

### Frontend Assets
```
resources/
├── css/app.css                      # Tailwind CSS
└── js/
    ├── app.js                       # Main JS entry
    └── bootstrap.js                 # Bootstrap (Axios, osv)
```

---

## 13. Test at Alpine.js fungerer

Åpne `resources/views/dashboard.blade.php` og legg inn:

```blade
<div x-data="{ open: false }">
    <button @click="open = !open">
        Toggle
    </button>
    <p x-show="open">
        Hei fra Alpine!
    </p>
</div>
```

Lagre og gå tilbake til nettleseren.

**Hvis knappen skjuler/viser teksten** → Alpine fungerer! ✔

---

## 14. Vanlige problemer og løsninger

### ❌ "Connection refused" (SQLSTATE[HY000] [2002])

**Problem:** MySQL-databasen kobler ikke

**Løsning:**
1. Åpne **Windows Services**
2. Sjekk at **MySQL80** kjører
3. Hvis ikke → høyreklikk og velg **Start**

---

### ❌ "Unknown database"

**Problem:** Databasen finnes ikke

**Løsning:**
1. Åpne MySQL Workbench
2. Lag schema med riktig navn
3. Kjør `php artisan migrate` igjen

---

### ❌ Vite-server feiler på port

**Problem:** Port 5173 er allerede i bruk

**Løsning:**
- Åpne terminalen der `npm run dev` kjører
- Trykk **Ctrl+C** for å stoppe
- Kjør `npm run dev` igjen

---

### ❌ Views oppdateres ikke i nettleseren

**Problem:** Cached Blade-templates

**Løsning:**
```bash
php artisan view:clear
```

Deretter refresh nettleseren.

---

### ❌ CSS/JavaScript ikke oppdateres

**Problem:** Vite-dev-serveren kjører ikke

**Løsning:**
1. Sjekk at `npm run dev` kjører i en terminal
2. Sjekk at nettleseren laster fra riktig URL (`https://prosjekt_navn.test`)
3. Refresh nettleseren med **Ctrl+Shift+R** (hard refresh)

---

### ❌ "Syntax error" i Blade-filer

**Problem:** PHP-syntaks-feil

**Løsning:**
1. Sjekk filen for skrivefeil
2. Kjør `php artisan tinker` for å teste PHP
3. Sjekk error-loggene: `storage/logs/laravel.log`

---

## 15. Neste steg

Nå som du har et fungerende Laravel + Breeze-oppsett kan du:

✔ Lese [ALPINE_GUIDE.md](./ALPINE_GUIDE.md) for å forstå interaktivitet

✔ Lese [FILE_STRUCTURE.md](../summaries/FILE_STRUCTURE.md) for å forstå koden

✔ Lese [FILE_DESCRIPTION.md](../summaries/FILE_DESCRIPTION.md) for detaljerte forklaringer

✔ Begynne å lage egne controllers, models og views

✔ Lese [Laravel-dokumentasjonen](https://laravel.com/docs) for dypere innsikt

---

## 16. Vanlige Laravel-kommandoer

```bash
# Servere
php artisan serve                    # Start dev-server

# Database
php artisan migrate                  # Kjør migrations
php artisan migrate:rollback         # Angre migrations
php artisan tinker                   # Interaktiv PHP-shell

# Kode-generering
php artisan make:controller Navn     # Lag controller
php artisan make:model Navn          # Lag model
php artisan make:migration navn      # Lag migration

# Cache/Cleanup
php artisan cache:clear              # Slett cache
php artisan view:clear               # Slett Blade-cache
php artisan optimize                 # Optimer appen

# Testing
php artisan test                     # Kjør tester
./vendor/bin/pest                    # Kjør Pest-tester
```

---

## 17. Sjekkliste for produksjon

Før du deployer til produksjon:

- ✔ Sett `APP_ENV=production` i `.env`
- ✔ Sett `APP_DEBUG=false` i `.env`
- ✔ Kjør `npm run build` for å kompilere assets
- ✔ Kjør `php artisan config:cache`
- ✔ Kjør `php artisan route:cache`
- ✔ Sikrer `.env` fil ikke committest til Git
- ✔ Sett opp SSL-sertifikat
- ✔ Sett opp backup-rutiner

---

**Tips:** Lagre denne guiden for senere referanse - du vil trenge den når du setter opp nye Laravel-prosjekter!

Last updated: November 28, 2025
