# 📚 MySQL 8 – Installasjonsguide (Windows)

**Trygg, ren og Laravel-vennlig installasjon**
*(ingen XAMPP, ingen PHP-rot, ingen konflikter)*

---

## Før du starter

**Hva denne guiden gir deg:**
- ✔ MySQL server som kjører isolert
- ✔ Ingen konflikter med Herd eller annen PHP
- ✔ Enkel administrasjon med MySQL Workbench
- ✔ Perfekt oppsett for Laravel-prosjekter

**Estimert tid:** 10-15 minutter

---

## 1. Last ned MySQL Installer

### Trinn:
1. Gå til [mysql.com](https://www.mysql.com/)
2. Søk etter **"MySQL Installer Community"**
3. Last ned **MySQL Installer 8.x.x (Community)** 
   - Velg **32-bit** eller **64-bit** (avhenger av din Windows)
4. Kjør installasjons-filen (`mysql-installer-community-x.x.x.msi`)

---

## 2. Velg Setup Type

Når installeren starter:

### Velg:
```
→ Custom
```

Dette lar deg velge nøyaktig hvilke komponenter du vil installere (vi vil bare ha serveren).

Trykk **Next**.

---

## 3. Velg Produkter

Under **Available Products**, velg følgende:

### ✔ Obligatorisk:
- **MySQL Server 8.x (X64)**
  - Selve databasen (må ha)

### ➕ Anbefalt (for enkel administrasjon):
- **MySQL Workbench 8.x**
  - GUI for administrasjon av databasen

### ❌ IKKE installer:
- MySQL Router
- MySQL Shell
- MySQL Samples
- Dokumentasjon
- **Ingenting som heter PHP, Apache eller Connectorer**
  - (Dette unngår konflikter med Herd)

### Flytt til "Products To Be Installed":
Klikk på pilen `→` for å flytte valgte produkter.

Trykk **Next**.

---

## 4. Download & Install

### Trinn:
1. Klikk **Execute**
2. Vent til alle pakker er lastet ned og installert (du ser grønne haker ✔)
3. Trykk **Next**

---

## 5. Server Configuration – Type & Networking

### Innstillinger:
```
Config Type:                  Development Computer
TCP/IP:                       ✔ (avhuket)
Port:                         3306
Open Windows Firewall port:   ✔ (avhuket)
Named Pipe:                   ❌ (IKKE avhuket)
Shared Memory:                ❌ (IKKE avhuket)
```

### Forklaring:
- **Development Computer** = enkelt oppsett, perfekt for utvikling
- **Port 3306** = standard MySQL-port
- **Firewall** = lar lokale apper koble til
- **TCP/IP** = moderne og sikker (det vi bruker)

Trykk **Next**.

---

## 6. Authentication Method

### Velg:
```
✔ Use Strong Password Encryption (RECOMMENDED)
```

Dette bruker SHA256-basert autentisering som:
- ✔ Er standard i moderne MySQL
- ✔ PHP og Laravel støtter det perfekt
- ✔ Er sikker

Trykk **Next**.

---

## 7. Accounts & Roles

### Trinn:
1. Lag **root-passord** (velg et sterkt passord)
   - **Husk eller skriv det ned!** Du trenger det senere
2. **Ikke legg til ekstra brukere** (kan gjøres senere hvis nødvendig)
3. Trykk **Next**

### Tips:
- Passord bør være minst 8 tegn
- Bruk stor/små bokstaver, tall og symboler
- Lagre det sikkert (password manager anbefales)

---

## 8. Windows Service Configuration

### Innstillinger:
```
✔ Configure MySQL as Windows Service
Name:                                 MySQL80
✔ Start the MySQL Server at system startup
Run as:                               Standard System Account
```

### Forklaring:
- **Windows Service** = MySQL starter automatisk med Windows
- **MySQL80** = tjenestens navn (standard)
- **Standard System Account** = sikker måte å kjøre det på

Trykk **Next**.

---

## 9. Server File Permissions

### Velg:
```
✔ "Yes, grant full access to the user running the service"
```

Dette gir MySQL de rettighetene det trenger.

Trykk **Next**.

---

## 10. Apply Configuration

Nå ser du en liste over ting som blir konfigurert:
- Writing config
- Windows Firewall
- Windows Service
- Initialize database
- Permissions
- Start server
- Security settings
- Start menu shortcut

### Trinn:
1. Klikk **Execute**
2. Vent til alt blir grønt ✔ (kan ta et par minutter)
3. Klikk **Finish**

**Gratulerer! MySQL er nå installert og kjører!**

---

## 11. Verifiser installasjonen (Anbefalt)

For å være sikker på at alt fungerer:

### Trinn:
1. Åpne **MySQL Workbench** (burde være på Start-menyen)
2. Koble til databasen:
   ```
   Host:     127.0.0.1
   Port:     3306
   User:     root
   Password: (det du valgte i steg 7)
   ```
3. Klikk **OK**

### Hvis du kommer inn:
- ✔ Du ser Workbench-grensesnittet
- ✔ Du kan se "Local instance MySQL80" i venstre panel
- ✔ MySQL kjører perfekt!

### Hvis du får feil:
- Sjekk at MySQL80 kjører i Windows Services (Win + R → `services.msc`)
- Verifiser passord er riktig
- Verifiser host er `127.0.0.1` (ikke `localhost`)

---

## 12. Lag din første database for Laravel

### Trinn:
1. I MySQL Workbench, gå til fanen **Schemas** (venstre panel)
2. Høyreklikk i tomrommet under Schemas
3. Velg **Create Schema…**
4. Skriv databasenavnet (eksempel):
   ```
   my_app
   ```
5. Klikk **Apply**
6. Klikk **Apply** igjen (popup)
7. Klikk **Finish**

**Ferdig!** Du har nå en tom database som Laravel kan bruke.

---

## 13. Koble Laravel til MySQL

I ditt Laravel-prosjekt, åpne `.env`-filen i roten og sett:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_app
DB_USERNAME=root
DB_PASSWORD=det_du_valgte_i_steg_7
```

### Test konfigurasjonen:
```bash
php artisan migrate
```

Hvis det kjører uten feil → Laravel kan snakke med MySQL! ✔

---

## 14. Gratulerer!

Du har nå:

✔ MySQL installert og kjørende
✔ MySQL Workbench for administrasjon
✔ En database klar for Laravel
✔ Ingen konflikter med Herd eller annen PHP
✔ En ren, stabil, profesjonell installasjon

**Du er klar til å begynne å utvikle!** 🎉

---

## Hva hvis noe går galt?

### ❌ "Connection refused" under verifisering

**Problem:** MySQL kjører ikke

**Løsning:**
1. Åpne Windows Services (Win + R → `services.msc`)
2. Finn **MySQL80**
3. Sjekk at den kjører
4. Hvis ikke → høyreklikk → **Start**

---

### ❌ Feil passord når jeg kobler til Workbench

**Problem:** Du husker ikke root-passordet

**Løsning:**
- Du må resette det (litt komplisert prosess)
- **Enklere løsning:** Avinstaller MySQL og installer på nytt

---

### ❌ Port 3306 er allerede i bruk

**Problem:** En annen app bruker port 3306

**Løsning:**
1. I installasjonen, velg en annen port (f.eks. 3307)
2. Oppdater `.env` med samme port:
   ```
   DB_PORT=3307
   ```

---

## Neste steg

Nå som MySQL er installert, kan du:

✔ Les [MySQL_GUIDE.md](./MySQL_GUIDE.md) for daglig bruk

✔ Les [LARAVEL+BREEZE_GUIDE.md](./LARAVEL+BREEZE_GUIDE.md) for å sette opp Laravel

✔ Begynn å lage Laravel-prosjekter med `laravel new`

---

## Sjekkliste

- ✔ MySQL Installer lastet ned
- ✔ Custom setup type valgt
- ✔ Bare MySQL Server og Workbench valgt
- ✔ Download og install kjørt
- ✔ Server configuration satt opp
- ✔ Strong password encryption valgt
- ✔ Root-passord opprettet
- ✔ Windows Service konfigurert
- ✔ File permissions satt
- ✔ Configuration applisert
- ✔ Installasjon verifisert i Workbench
- ✔ Første database opprettet
- ✔ Laravel konfigurert

**Hvis alle er avhuket, er du ferdig!** ✔

---

**Tips:** Lagre denne guiden - du vil referere til den hvis du må reinstallere eller hjelpe noen annen!

Last updated: November 28, 2025
