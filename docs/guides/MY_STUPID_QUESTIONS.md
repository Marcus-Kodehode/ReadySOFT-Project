# My Questions & Answers

En samling av spørsmål og svar som dukket opp underveis i prosjektet.

---

## Q1: Hvorfor har Laravel sine migrasjoner `0001_01_01` mens mine har dagens dato?

**Spørsmål:**  
Fungerer det at noen migrations-filer ikke har dagens dato? Er det god praksis?

**Svar:**  
Ja, det er helt korrekt! Her er hvorfor:

**Laravel sine default migrasjoner:**
```
0001_01_01_000000_create_users_table.php
0001_01_01_000001_create_cache_table.php
0001_01_01_000002_create_jobs_table.php
```
- Bruker `0001_01_01` **med vilje**
- Sikrer at de alltid kjører først (før alle dine migrasjoner)
- Dette er framework-migrasjoner som kommer med Laravel

**Dine egne migrasjoner:**
```
2025_12_01_000001_create_tenants_table.php
2025_12_01_000002_create_plans_table.php
```
- Skal bruke dagens dato
- Viser når de ble laget
- Kjører etter Laravel sine migrasjoner

**Hvordan Laravel kjører dem:**  
Laravel sorterer migrasjoner alfabetisk:
1. `0001_01_01_*` (Laravel defaults) → Kjører først
2. `2025_12_01_*` (dine) → Kjører etterpå

**Konklusjon:**  
✅ Bruk alltid dagens dato for dine egne migrasjoner  
✅ La Laravel sine `0001_01_01` migrasjoner være som de er  
✅ Rekkefølgen styres av timestamp i filnavnet

---

## Q2: Hvorfor ser jeg ikke nye tabeller/data i MySQL Workbench?

**Spørsmål:**  
Jeg kjørte `php artisan migrate` og `php artisan db:seed`, men ser ikke de nye tabellene eller dataene i MySQL Workbench. Hva er galt?

**Svar:**  
Ingenting er galt! MySQL Workbench oppdaterer **ikke** automatisk. Du må refreshe manuelt.

**Løsning:**

**Metode 1: Refresh Tables**
1. Høyreklikk på **Tables** (i Navigator-panelet til venstre)
2. Velg **"Refresh All"**

**Metode 2: Refresh-ikon**
- Klikk på refresh-ikonet (🔄) øverst i Navigator-panelet

**Metode 3: Keyboard shortcut**
- Trykk **F5** (refresh)

**Når må du refreshe?**
- Etter `php artisan migrate` (nye tabeller)
- Etter `php artisan db:seed` (nye data)
- Etter du har lagt til/endret data via Laravel
- Etter du har kjørt SQL-queries i en annen tab

**Pro tip:**  
Kom i vanen med å refreshe hver gang du har gjort endringer i databasen via terminalen. Workbench vet ikke at noe har skjedd før du refresher!

**Konklusjon:**  
✅ MySQL Workbench oppdaterer IKKE automatisk  
✅ Alltid refresh etter migrations/seeding  
✅ Høyreklikk Tables → Refresh All  

---

## Q3: Hvorfor kjører ikke npm install også composer install?

Spørsmål:
Jeg trodde npm install skulle hente alt jeg trenger, men Laravel klager på manglende filer i vendor/. Hvorfor installeres ikke PHP-pakkene automatisk?

Svar:
Fordi npm og composer er to helt forskjellige systemer.
npm install installerer frontend-pakker (Tailwind, Vite, Alpine osv.), mens composer install installerer PHP-pakker (Laravel-kjernen, routing, migrasjoner osv.).
De snakker ikke sammen og kan ikke erstatte hverandre.

Løsning:
Når du kloner et Laravel-prosjekt må du ALLTID kjøre begge:

PHP-avhengigheter:

composer install


Frontend-avhengigheter:

npm install


Når må du kjøre dette?

Etter du har clonet et prosjekt

Når du bytter PC

Etter noen har lagt til nye pakker i composer.json eller package.json

Konklusjon:
✅ npm install = frontend
✅ composer install = backend
❌ Den ene erstatter ikke den andre
