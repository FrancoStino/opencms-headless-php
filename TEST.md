# Test Checklist - OpenCMS Headless PHP

## Pre-requisiti

- [ ] OpenCMS in esecuzione su `http://localhost`
- [ ] API JSON attiva: `http://localhost/json/sites/mercury.local/`
- [ ] Contenuti di test presenti (article-m, contact-m, faq-m)

## Test da Eseguire

### 1. Avvio Applicazione

#### Con Lando

```bash
cd /home/adolf/IdeaProjects/opencms-headless-php
lando start
```

- [ ] Lando si avvia senza errori
- [ ] URL accessibile (es. https://opencms-headless-php.lndo.site)

#### Con PHP Built-in

```bash
cd /home/adolf/IdeaProjects/opencms-headless-php
php -S localhost:8000 -t public
```

- [ ] Server si avvia su http://localhost:8000
- [ ] Nessun errore PHP nel terminale

### 2. Test List View

#### Homepage Default

```
http://localhost/
```

**Verificare:**

- [ ] Header con logo e titolo visibile
- [ ] Dark mode toggle presente e funzionante
- [ ] Sezione "Controls" con 2 dropdown (Content Type, Language)
- [ ] Dropdown "Content Type" mostra: Articles, Contacts, FAQs
- [ ] Dropdown "Language" mostra: English, Italiano
- [ ] Lista contenuti visualizzata in griglia (1-3 colonne responsive)
- [ ] Card contenuti con: titolo, intro (se presente), link "Read more"
- [ ] Footer con copyright e link

#### Cambio Tipo di Contenuto

```
http://localhost/?type=contact-m&locale=en
```

- [ ] URL aggiornato correttamente
- [ ] Dropdown "Content Type" seleziona "Contacts"
- [ ] Lista mostra contenuti di tipo contact-m

```
http://localhost/?type=faq-m&locale=en
```

- [ ] URL aggiornato correttamente
- [ ] Dropdown "Content Type" seleziona "FAQs"
- [ ] Lista mostra contenuti di tipo faq-m

#### Cambio Lingua

```
http://localhost/?type=article-m&locale=it
```

- [ ] URL aggiornato correttamente
- [ ] Dropdown "Language" seleziona "Italiano"
- [ ] Contenuti mostrati in italiano (se disponibili)

### 3. Test Detail View

#### Article Detail

```
Clicca su un articolo dalla lista
```

**Verificare:**

- [ ] Pulsante "Torna alla lista" presente e funzionante
- [ ] Titolo articolo in grande (h1)
- [ ] Autore visualizzato (se presente)
- [ ] Intro text in evidenza (se presente)
- [ ] Paragrafi renderizzati correttamente
- [ ] Caption paragrafi con barra colorata laterale
- [ ] Immagini con effetto hover (scale + rotate)
- [ ] HTML formattato correttamente (grassetto, corsivo, link)

#### FAQ Detail

```
http://localhost/?type=faq-m&locale=en
Clicca su una FAQ
```

**Verificare:**

- [ ] Icona FAQ (punto interrogativo) presente
- [ ] Label "Frequently Asked Question"
- [ ] Domanda come titolo
- [ ] Risposta in box blu con label "Answer:"
- [ ] Pulsante back funzionante

#### Contact Detail

```
http://localhost/?type=contact-m&locale=en
Clicca su un contatto
```

**Verificare:**

- [ ] Layout a 2 colonne (info + form)
- [ ] Icona contatto presente
- [ ] Informazioni contatto visualizzate
- [ ] Form con campi: Name, Email, Subject, Message
- [ ] Campi obbligatori marcati con *
- [ ] Pulsante "Send Message" con gradient
- [ ] Pulsante back funzionante

### 4. Test Responsive

#### Mobile (< 640px)

- [ ] Header responsive (logo + toggle verticali se necessario)
- [ ] Dropdown full-width
- [ ] Card lista in colonna singola
- [ ] Form contatto in colonna singola
- [ ] Testo leggibile

#### Tablet (640px - 1024px)

- [ ] Card lista in 2 colonne
- [ ] Layout contatto ancora in colonna singola
- [ ] Navigazione comoda

#### Desktop (> 1024px)

- [ ] Card lista in 3 colonne
- [ ] Layout contatto in 2 colonne
- [ ] Spaziatura ottimale

### 5. Test Dark Mode

#### Toggle Dark Mode

- [ ] Click su toggle cambia tema
- [ ] Icona cambia (sole/luna)
- [ ] Colori si invertono correttamente
- [ ] Testo leggibile in entrambi i temi
- [ ] Card hanno sfondo appropriato
- [ ] Form leggibile in dark mode

#### Persistenza

- [ ] Refresh pagina mantiene tema selezionato
- [ ] Tema salvato in localStorage
- [ ] Preferenza sistema rispettata al primo accesso

### 6. Test Navigazione

#### Persistenza Parametri

```
1. Seleziona type=faq-m, locale=it
2. Clicca su un elemento
3. Clicca "Torna alla lista"
```

- [ ] Ritorna a lista FAQ in italiano
- [ ] Parametri URL preservati

#### Link Diretti

```
http://localhost/?path=/sites/mercury.local/.content/article-m/a_00001.xml&type=article-m&locale=en
```

- [ ] Dettaglio caricato direttamente
- [ ] Back button funziona
- [ ] Parametri corretti

### 7. Test Errori

#### API Non Disponibile

```
1. Ferma OpenCMS
2. Accedi all'app
```

- [ ] Pagina errore visualizzata
- [ ] Messaggio chiaro
- [ ] Nessun errore PHP fatale

#### Contenuto Non Trovato

```
http://localhost/?path=/sites/mercury.local/.content/article-m/non_esistente.xml&type=article-m&locale=en
```

- [ ] Pagina errore o messaggio appropriato
- [ ] Possibilità di tornare alla lista

#### Tipo Non Supportato

```
http://localhost/?type=tipo-inesistente&locale=en
```

- [ ] Lista vuota o messaggio appropriato
- [ ] Nessun errore PHP

### 8. Test Performance

#### Caricamento Pagina

- [ ] Homepage carica in < 2 secondi
- [ ] Detail view carica in < 2 secondi
- [ ] Nessun flash di contenuto non stilizzato (FOUC)

#### Transizioni

- [ ] Hover su card fluido
- [ ] Cambio tema smooth
- [ ] Animazioni non laggy

### 9. Test Browser

#### Chrome/Chromium

- [ ] Tutto funziona correttamente
- [ ] Dark mode funziona
- [ ] Animazioni smooth

#### Firefox

- [ ] Tutto funziona correttamente
- [ ] Dark mode funziona
- [ ] Animazioni smooth

#### Safari (se disponibile)

- [ ] Tutto funziona correttamente
- [ ] Dark mode funziona

### 10. Test Console

#### Errori JavaScript

- [ ] Nessun errore in console
- [ ] Dark mode toggle funziona senza errori

#### Errori PHP

```
Controlla log PHP o terminale
```

- [ ] Nessun warning PHP
- [ ] Nessun errore PHP
- [ ] Nessun notice PHP

## Problemi Comuni e Soluzioni

### Problema: "No content found"

**Causa**: API non restituisce dati o struttura dati diversa
**Soluzione**:

1. Verifica URL API in browser: `http://localhost/json/sites/mercury.local/.content/article-m/`
2. Controlla struttura JSON
3. Aggiorna `ApiClient.php` se necessario

### Problema: Immagini non caricate

**Causa**: Path immagini non corretto
**Soluzione**:

1. Verifica link immagine in JSON API
2. Assicurati che OpenCMS serva le immagini
3. Controlla CORS se necessario

### Problema: Dark mode non persiste

**Causa**: localStorage non funziona
**Soluzione**:

1. Verifica che JavaScript sia abilitato
2. Controlla console per errori
3. Testa in incognito (senza estensioni)

### Problema: Stili non applicati

**Causa**: TailwindCSS CDN non caricato
**Soluzione**:

1. Verifica connessione internet
2. Controlla che `<script src="https://cdn.tailwindcss.com"></script>` sia presente
3. Refresh hard (Ctrl+Shift+R)

## Report Bug

Se trovi bug, annota:

- [ ] URL esatto
- [ ] Tipo di contenuto
- [ ] Browser e versione
- [ ] Screenshot (se possibile)
- [ ] Messaggio errore (se presente)
- [ ] Steps per riprodurre

## Test Completati

Data: _______________
Tester: _______________
Risultato: ⬜ PASS | ⬜ FAIL
Note: _______________________________________________
