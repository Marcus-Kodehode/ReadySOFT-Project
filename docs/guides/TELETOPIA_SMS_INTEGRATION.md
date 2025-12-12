# Teletopia SMS Integration - Sikkerhetsdokumentasjon

## Oversikt
Dette dokumentet beskriver Teletopia SMS-integrasjonen med fokus på sikkerhet, kostnadsoptimalisering og beskyttelse mot misbruk.

## Konfigurasjon

### .env Oppsett
```env
TELETOPIA_USERNAME=y3330c5nuv2
TELETOPIA_PASSWORD=LlTM060VKuq30iaJQcpl9JLK
```

### API Detaljer
- **API URL**: https://api1.teletopiasms.no/gateway/v3/json (HTTP JSON API)
- **Backup URL**: https://api2.teletopiasms.no/gateway/v3/json
- **Autentisering**: JSON body med username/password
- **Kostnad**: 1 credit per SMS
- **Format**: JSON POST request

## Sikkerhetstiltak

### 1. Meldingslengde-validering
- **Maks 50 ord** - Sikrer at meldingen holder seg innenfor 1 SMS
- **Maks 160 tegn** - Standard SMS-lengde
- Validering skjer både på frontend (Alpine.js) og backend (Laravel)

### 2. Rate Limiting
- **Test SMS**: Maks 5 forsøk per time per bruker
- **Booking SMS**: Throttle på 10 bookinger per time
- Implementert via Laravel's `throttle` middleware

### 3. Autentisering og Autorisasjon
- Kun autentiserte brukere med aktiv subscription kan sende SMS
- SMS må være aktivert i tenant settings
- Teletopia credentials lagres i .env (ikke i database)

### 4. Input Validering
```php
// Telefonnummer validering
'phone_number' => 'required|string|regex:/^[+]?[0-9\s\-\(\)]{8,20}$/'

// Melding validering
'message' => [
    'required',
    'string',
    'max:160',
    function ($attribute, $value, $fail) {
        $wordCount = str_word_count($value);
        if ($wordCount > 50) {
            $fail("Message must not exceed 50 words");
        }
    }
]
```

### 5. Telefonnummer Normalisering
- **VIKTIG**: Teletopia krever format UTEN + (f.eks. 4790039911, ikke +4790039911)
- Fjerner automatisk +, mellomrom, bindestreker og parenteser
- Legger til 47 for norske 8-sifrede nummer
- Eksempler:
  - Input: `90039911` → Output: `4790039911`
  - Input: `+47 900 39 911` → Output: `4790039911`
  - Input: `47 90039911` → Output: `4790039911`

### 6. Logging og Monitoring
- All SMS-aktivitet logges med tenant_id
- Feilmeldinger logges med full stack trace
- Credits brukt logges for hver sending

## API Integrasjon

### Request Format (TeletopiaSMS HTTP JSON API)
```json
POST https://api1.teletopiasms.no/gateway/v3/json
Content-Type: application/json

{
    "auth": {
        "username": "y3330c5nuv2",
        "password": "LlTM060VKuq30iaJQcpl9JLK"
    },
    "messages": [
        {
            "recipient": "4790039911",
            "senderType": 5,
            "sender": "ReadySoft",
            "contentText": {
                "text": "Your booking is confirmed"
            }
        }
    ]
}
```

**VIKTIG**: 
- Telefonnummer UTEN + symbol (4790039911, ikke +4790039911)
- senderType 5 = Alphanumeric sender (maks 11 tegn)
- Response inneholder "accepted": 1 hvis vellykket

### Response Handling
- **200 OK**: SMS sendt vellykket (1 credit brukt)
- **4xx/5xx**: Feil - ingen credits brukt
- Full response logges for debugging

## Kostnadsoptimalisering

### Sikrer 1 SMS = 1 Credit
1. **Frontend validering**: Real-time ord/tegn-telling
2. **Backend validering**: Dobbel-sjekk før sending
3. **Visuell feedback**: Grønn ✓ når innenfor grense, rød ⚠ når over
4. **Disabled knapp**: Kan ikke sende hvis over grense

### Beskyttelse mot Misbruk
- Rate limiting på test-funksjon (5/time)
- Kun admin/tenant-eiere har tilgang
- Subscription må være aktiv
- SMS må være eksplisitt aktivert

## Testing

### Manuell Test
1. Gå til Dashboard → SMS Settings
2. Aktiver SMS notifications
3. Skriv inn test-melding (maks 50 ord)
4. Sjekk at teller viser grønn ✓
5. Send til Håkon: +47 900 39 911
6. Verifiser at kun 1 credit brukes

### Sjekkliste før Live
- [ ] .env har riktige Teletopia credentials
- [ ] SMS er aktivert i tenant settings
- [ ] Test-melding er under 50 ord og 160 tegn
- [ ] Rate limiting fungerer (test 6 ganger raskt)
- [ ] Telefonnummer normaliseres korrekt
- [ ] Logging fungerer (sjekk storage/logs)

## Feilsøking

### "Teletopia credentials not configured"
- Sjekk at TELETOPIA_USERNAME og TELETOPIA_PASSWORD er satt i .env
- Kjør `php artisan config:clear`

### "Message exceeds 50 words"
- Forkort meldingen
- Fjern unødvendige ord
- Bruk forkortelser der mulig

### "Failed to send SMS"
- Sjekk at credentials er korrekte
- Verifiser at Teletopia-kontoen har credits
- Sjekk logs for detaljert feilmelding

## Kontaktinformasjon
- **Test kontakt**: Håkon 90039911 (sendes som 4790039911)
- **Teletopia support**: https://teletopia.no/support

## ⚠️ KRITISK - Telefonnummer Format
Teletopia krever telefonnummer UTEN + symbol:
- ✅ Riktig: `4790039911`
- ❌ Feil: `+4790039911`

Alle telefonnummer normaliseres automatisk til riktig format.
