# 📚 MySQL – Guide for Laravel Prosjekter

**Alt du trenger å vite om databasen i dine Laravel-prosjekter**

---

## 1. Hva MySQL gjør i prosjektet

MySQL er **databasen** som lagrer all vedvarende data i appen di.

### Hva lagres i MySQL:
- ✔ Brukere og passord
- ✔ Sesjoner (holder deg innlogget)
- ✔ Alt av forretningsdata (bookinger, tenants, osv.)
- ✔ Alt som skal være der når du starter appen på nytt

**Uten MySQL = ingen ekte data, bare data i minne som forsvinner.**

Laravel snakker med MySQL via innstillingene i `.env`-filen.

---

## 2. Hvordan MySQL er installert hos deg

### Installasjonsdetaljer:
- **Installer:** MySQL Installer (Community Edition)
- **Tjeneste-navn:** MySQL80
- **Port:** 3306 (standard MySQL-port)
- **Kjører som:** Windows-tjeneste
- **Admin-verktøy:** MySQL Workbench

### Viktig å vite:
- ✔ Starter automatisk når Windows starter
- ✔ Kjører uavhengig av Herd eller Laravel
- ✔ Du administrerer det med MySQL Workbench
- ✔ Du trenger ikke starte/stoppe noe manuelt normalt

---

## 3. Starte og stoppe MySQL (hvis noe krøller seg)

Vanligvis gjør du **ingenting** – MySQL kjører av seg selv.

### Hvis du MÅ sjekke statusen:

Åpne **Windows Services**:
1. Trykk **Win + R**
2. Skriv `services.msc`
3. Trykk **Enter**

### I Services-vinduet:
- Finn **MySQL80** i listen
- Høyreklikk på den:
  - **Start** – starter tjenesten
  - **Stop** – stopper tjenesten
  - **Restart** – restarter tjenesten

### Når trenger du dette?
- Hvis Laravel klager: `Connection refused`
- Hvis du glemte å koble fra før du stengte PC
- Hvis du må rydde eller resette noe

---

## 4. MySQL Workbench – praktisk bruk

MySQL Workbench er GUI-verktøyet du bruker til å administrere MySQL.

### Hva du bruker det til:
- ✔ Lag nye databaser ("Schemas")
- ✔ Se tabeller og data
- ✔ Teste SQL-spørringer
- ✔ Rydde/slette hvis du roter deg bort
- ✔ Sikkerhetskopi og restore

---

## 4.1. Lag database for nytt Laravel-prosjekt

### Eksempel: Lag database for `my_new_app`

**Trinn:**
1. Åpne **MySQL Workbench**
2. Klikk på tilkoblingen **Local instance MySQL80** (i venstre panel)
3. Gå til fanen **Schemas** (under Connection)
4. Høyreklikk i tomrommet under Schemas
5. Velg **Create Schema…**
6. Skriv databasenavnet:
   ```
   my_new_app
   ```
7. Klikk **Apply**
8. Klikk **Apply** igjen
9. Klikk **Finish**

**Ferdig!** Du har nå en tom database klar.

---

## 4.2. Se tabeller i en database

### Trinn:
1. I **Schemas**, klikk på databasenavnet (f.eks. `readysoft_project`)
2. Klikk på pilen ved siden for å **utvide** den
3. Se **Tables** mappen
4. Høyreklikk på en tabell (f.eks. `users`)
5. Velg **Select Rows – Limit 1000**

**Resultat:** Du ser innholdet i tabellen (brukere, bookinger, osv.)

---

## 5. Laravel + MySQL – Oppskrift for nye prosjekter

Hver gang du starter et **nytt Laravel-prosjekt** følger du dette mønsteret:

### Steg 1: Lag database
- Åpne MySQL Workbench
- Create Schema med prosjektnavnet (f.eks. `my_cool_app`)

### Steg 2: Oppdater `.env`
Finn `.env` i prosjektet og sett:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_cool_app
DB_USERNAME=root
DB_PASSWORD=DITT_PASSORD
```

### Steg 3: Kjør migrering
```bash
php artisan migrate
```

**Hvis det kjører uten feil** → databasen er riktig koblet! ✔

---

## 6. Vanlige MySQL-problemer og løsninger

### ❌ SQLSTATE[HY000] [2002] Connection refused

**Hva det betyr:** Laravel kan ikke koble til MySQL-serveren

**Vanlige årsaker:**
- MySQL-server kjører ikke
- Feil port (ikke 3306)
- Feil host (ikke 127.0.0.1)

**Løsning:**
1. Åpne **Windows Services** (Win + R → `services.msc`)
2. Finn **MySQL80**
3. Sjekk at den kjører (Status = "Running")
4. Hvis ikke → høyreklikk → **Start**
5. Verifiser `.env`:
   ```
   DB_HOST=127.0.0.1
   DB_PORT=3306
   ```

---

### ❌ SQLSTATE[HY000] [1049] Unknown database 'xxxx'

**Hva det betyr:** Databasen med det navnet finnes ikke

**Løsning:**
1. Åpne MySQL Workbench
2. Lag databasen med **Create Schema…** (se kapittel 4.1)
3. Sjekk at `DB_DATABASE` i `.env` staves **eksakt likt** som i Workbench
4. Kjør `php artisan migrate` igjen

---

### ❌ SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'

**Hva det betyr:** Feil passord eller brukernavn

**Løsning:**
1. Sjekk `.env`:
   ```bash
   DB_USERNAME=root
   DB_PASSWORD=det_du_valgte
   ```
2. Verifiser passordet er korrekt
3. Hvis du har glemt root-passordet:
   - Dette krever en reset-prosess (litt komplisert, men mulig)
   - **Tips:** Lag en `.env.local` eller `.env.backup` fil der du lagrer dette trygt

---

### ❌ Tabell allerede opprettet ved migrate

**Hva det betyr:** Migration forsøkte å lage en tabell som allerede finnes

**Løsning:**
- Kjør `php artisan migrate:rollback`
- Kjør `php artisan migrate` igjen

---

### ❌ Jeg har rotet helt til med databasen

**Løsning:** Reset databasen fullstendig (se kapittel 7)

---

## 7. Reset databasen i et prosjekt

Hvis du har rotet deg helt bort og vil starte på blanke ark.

### Metode A: Drop og recreate i Workbench (sikker)

**Trinn:**
1. Åpne MySQL Workbench
2. I **Schemas**, høyreklikk på databasen (f.eks. `readysoft_project`)
3. Velg **Drop Schema…**
4. Klikk **Apply**
5. Lag den på nytt med **Create Schema…**
6. Kjør i terminal:
   ```bash
   php artisan migrate
   ```

**Resultat:** Databasen er som "ny installasjon"

---

### Metode B: Artisan command (hvis DB finnes og funker)

Hvis databasen finnes og du bare vil slette innholdet:

```bash
php artisan migrate:fresh
```

Dette:
- ✔ Sletter alle tabeller
- ✔ Kjører alle migrationer på nytt
- ✔ Fungerer bare hvis databasen finnes

---

### Metode C: Med seeding (fyll med test-data)

Hvis du vil reset OG fylle med startdata:

```bash
php artisan migrate:fresh --seed
```

Dette:
- ✔ Sletter alle tabeller
- ✔ Kjører alle migrationer
- ✔ Kjører seeders (test-data)

---

## 8. Trenger du mysql-kommando i terminalen?

**Kort svar: Nei.**

### Hvorfor du ikke trenger det:
- Laravel bruker ikke `mysql`-kommandoen direkte
- Du har MySQL Workbench som er mye enklere
- Alt fungerer perfekt gjennom Laravel commands

### Hvis du likevel vil ha det (valgfritt):

Legg MySQL sin `bin`-mappe i Windows PATH:

1. Finn mappen:
   ```
   C:\Program Files\MySQL\MySQL Server 8.0\bin
   ```
2. Legg den til PATH (se Windows-dokumentasjon)
3. Restart terminalen

**Men det er "nice to have", ikke noe du trenger til hverdags.**

---

## 9. MySQL-kommandoer du MAY trenger (via Artisan)

Selv om du ikke trenger `mysql`-kommando, kan disse Artisan-kommandoene være nyttige:

```bash
# Database
php artisan migrate                 # Kjør alle migrationer
php artisan migrate:rollback        # Angre siste migration
php artisan migrate:fresh           # Slett alle og kjør på nytt
php artisan migrate:fresh --seed    # Reset og fyll med data
php artisan tinker                  # Interaktiv PHP-shell (test kode)

# Seeding
php artisan db:seed                 # Kjør seeders
php artisan db:seed --class=UserSeeder  # Kjør spesifikk seeder

# Migrering
php artisan make:migration navn     # Lag ny migration

# Info
php artisan migrate:status          # Se status på alle migrationer
```

---

## 10. Sikkerhetskopi av databasen

### Manuell sikkerhetskopi fra Workbench:

1. Høyreklikk på databasen i **Schemas**
2. Velg **Data Export…**
3. Velg alle tabeller
4. Klikk **Export to Dump Project Folder** eller **Export to Self-Contained File**
5. Velg lokasjon og lagre

### Restore fra sikkerhetskopi:

1. I Workbench, gå til **File** → **Open SQL Script**
2. Velg dump-filen
3. Klikk **Execute**

---

## 11. Tips og beste praksis

### ✔ Alltid lag database FØR du setter opp .env
Ikke anta at `php artisan migrate` lager databasen.

### ✔ Test databasen-konfigurasjonen tidlig
```bash
php artisan migrate
```
Gjør dette umiddelbart etter å ha satt opp `.env`.

### ✔ Lagre root-passordet trygt
- Skribble det ned et sikkert sted
- Eller bruk en password manager
- Du trenger det hvis du må resette

### ✔ Bruk `migrate:fresh` i utvikling, ALDRI i produksjon
`migrate:fresh` sletter ALL data. For produksjon, bruk `migrate` (som legger til tabeller).

### ✔ Kjør migrationer i rekkefølge
Migrationer har timestamps. De kjøres i rekkefølgen de ble opprettet. Ikke endre på dette.

### ✔ Gi migrerings-filene beskrivende navn
```
2025_11_28_100000_create_users_table.php  ✔ Bra
2025_11_28_100001_create_posts_table.php  ✔ Bra
xxxx.php                                   ❌ Dårlig
```

---

## 12. Produksjon vs. Utvikling

### I **utvikling** (lokalt):
- ✔ Bruk `migrate:fresh` når du vil
- ✔ Slett og opprett tabeller fritt
- ✔ Test ulike endringer
- ✔ Bruk `tinker` for å teste kode

### I **produksjon** (live-server):
- ✔ Bruk bare `migrate` (legger til, ikke sletter)
- ✔ Test all migrering lokalt først
- ✔ Lage sikkerhetskopi før migrating
- ✔ ALDRI bruk `migrate:fresh`
- ✔ Overvåk databasen-ytelse

---

## 13. Rask sjekkliste når du starter nytt prosjekt

- ✔ PHP og Node.js installert
- ✔ MySQL80 kjører (Services)
- ✔ Lag nytt Laravel-prosjekt
- ✔ Lag database i MySQL Workbench
- ✔ Sett opp `.env` med DB-detaljer
- ✔ Kjør `php artisan migrate`
- ✔ Sjekk at tabeller ble opprettet i Workbench
- ✔ Du er klar til å kode! 🎉

---

**Tips:** Lagre denne guiden for senere - du vil referere til den når du jobber med databaser!

Last updated: November 28, 2025
