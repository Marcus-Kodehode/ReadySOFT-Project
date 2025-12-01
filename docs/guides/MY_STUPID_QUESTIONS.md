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

