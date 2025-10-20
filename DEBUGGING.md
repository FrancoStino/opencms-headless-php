# 🐛 Guida al Debugging

## Problema: Pagine Vuote

Se le pagine (homepage, contatti) appaiono completamente vuote, segui questi passaggi per diagnosticare il problema.

---

## 1. Verifica API OpenCMS

### Accedi alla pagina di test:
```
http://localhost:8000/test-api.php
```

Questa pagina ti mostrerà:
- ✅ Se l'API OpenCMS è raggiungibile
- ✅ Struttura dei containers
- ✅ Elementi presenti
- ✅ FormatterKeys disponibili

### Cosa verificare:
1. **API raggiungibile**: Deve mostrare "✅ Risposta ricevuta"
2. **Containers presenti**: Deve mostrare almeno 1 container
3. **Elementi presenti**: Ogni container deve avere elementi
4. **FormatterKeys**: Verifica che i formatter siano supportati

---

## 2. Verifica Configurazione

### File `.env`
```bash
OPENCMS_SERVER=http://localhost
```

Assicurati che l'URL sia corretto e che OpenCMS sia in esecuzione.

### Test manuale API:
```bash
curl http://localhost/json/sites/mercury.local/index.html?locale=en&fallbackLocale
```

Dovresti vedere un JSON con `containers` e `elements`.

---

## 3. Debug Mode

Il template `page.php` mostra automaticamente informazioni di debug se i containers sono vuoti.

### Cosa mostra:
- **Containers**: Se la variabile è impostata
- **Containers Count**: Numero di containers
- **Page Title**: Titolo della pagina
- **Locale**: Lingua corrente
- **Base URL**: URL API OpenCMS

### Come leggere il debug:
```
Containers: SET          → Variabile impostata ✅
Containers Count: 0      → Nessun container ❌
```

Se `Count: 0`, il problema è nell'API OpenCMS o nel path della pagina.

---

## 4. Log PHP

I log vengono scritti in `error_log`. Verifica:

```bash
# Se usi Lando
lando logs -s appserver

# Se usi PHP built-in server
tail -f /var/log/php_errors.log
```

### Cerca questi messaggi:
```
Page Path: /sites/mercury.local/index.html
Page Data: Array(...)
Containers count: 0
Template page.php - Containers: NOT SET
```

---

## 5. Problemi Comuni

### Problema 1: API non raggiungibile
**Sintomo**: `test-api.php` mostra "❌ ERRORE: Impossibile raggiungere l'API"

**Soluzione**:
1. Verifica che OpenCMS sia in esecuzione
2. Controlla l'URL in `.env`
3. Testa manualmente: `curl http://localhost/json/sites/mercury.local/`

### Problema 2: Pagina non esiste
**Sintomo**: `test-api.php` mostra "❌ Pagina non trovata"

**Soluzione**:
1. La pagina `/contatti` potrebbe non esistere in OpenCMS
2. Verifica le pagine disponibili:
   ```bash
   curl http://localhost/json/sites/mercury.local/
   ```
3. Usa un path esistente nel Router

### Problema 3: Containers vuoti
**Sintomo**: Debug mostra `Containers Count: 0`

**Soluzione**:
1. Verifica che la pagina sia di tipo `containerpage`
2. Controlla che ci siano elementi nei containers
3. Usa `test-api.php` per vedere la struttura completa

### Problema 4: Formatter non supportato
**Sintomo**: Elementi presenti ma non renderizzati

**Soluzione**:
1. Verifica il `formatterKey` in `test-api.php`
2. Aggiungi supporto in `page.php`:
   ```php
   <?php elseif ($formatterKey === 'm/nuovo/formatter'): ?>
       <!-- Rendering -->
   <?php endif; ?>
   ```

### Problema 5: Contenuto elemento non caricato
**Sintomo**: Slider o form non visibili

**Soluzione**:
1. Verifica che `fetchElementContent()` funzioni
2. Controlla il path dell'elemento
3. Testa manualmente:
   ```bash
   curl http://localhost/json/sites/mercury.local/.content/slider-m/slider_00001.xml?locale=en
   ```

---

## 6. Checklist Debugging

- [ ] OpenCMS in esecuzione
- [ ] API raggiungibile (`curl http://localhost/json/...`)
- [ ] `.env` configurato correttamente
- [ ] `test-api.php` mostra containers
- [ ] Debug info in `page.php` mostra dati corretti
- [ ] Log PHP non mostrano errori
- [ ] Path pagina corretto nel Router
- [ ] FormatterKeys supportati in `page.php`

---

## 7. Test Step-by-Step

### Step 1: Test API Base
```bash
curl http://localhost/json/sites/mercury.local/
```
**Aspettativa**: JSON con lista pagine/contenuti

### Step 2: Test Homepage API
```bash
curl http://localhost/json/sites/mercury.local/index.html?locale=en&fallbackLocale
```
**Aspettativa**: JSON con `containers` array

### Step 3: Test API Client
Accedi a: `http://localhost:8000/test-api.php`
**Aspettativa**: "✅ API Client funziona"

### Step 4: Test Homepage
Accedi a: `http://localhost:8000/`
**Aspettativa**: Slider, sezioni, contenuti visibili

### Step 5: Verifica Debug
Se pagina vuota, verifica il box giallo con debug info
**Aspettativa**: `Containers Count: > 0`

---

## 8. Soluzioni Rapide

### Soluzione 1: Pagina di test non esiste
Se `/contatti` non esiste, modifica il Router per usare una pagina esistente:

```php
// src/Router.php
if ($path === '/contatti' || $path === '/contatti/') {
    // Usa una pagina che esiste
    $controller->showPage('/sites/mercury.local/demo/index.html', $locale);
}
```

### Soluzione 2: Usa homepage per test
Concentrati prima sulla homepage:

```php
// src/Router.php
if ($path === '/' || $path === '/index.php') {
    $controller->showPage('/sites/mercury.local/index.html', $locale);
}
```

### Soluzione 3: Fallback a lista
Se le container pages non funzionano, usa temporaneamente la lista:

```php
// src/Router.php
if ($path === '/' || $path === '/index.php') {
    // Fallback a lista articoli
    $controller->showList('article-m', $locale);
}
```

---

## 9. Strumenti Utili

### Browser DevTools
- **Network Tab**: Verifica richieste API
- **Console**: Cerca errori JavaScript
- **Elements**: Verifica se HTML è generato

### PHP Error Reporting
Abilita errori in `public/index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Var Dump
Aggiungi debug temporaneo:
```php
// In page.php
echo '<pre>';
var_dump($containers);
echo '</pre>';
exit;
```

---

## 10. Contatti per Supporto

Se il problema persiste:

1. **Raccogli informazioni**:
   - Output di `test-api.php`
   - Log PHP
   - Screenshot debug info

2. **Verifica documentazione**:
   - [CONTAINER_PAGES.md](./CONTAINER_PAGES.md)
   - [OPENCMS_API_STRUCTURE.md](./OPENCMS_API_STRUCTURE.md)

3. **Controlla esempi**:
   - Progetto Next.js di riferimento
   - Documentazione OpenCMS

---

## 11. Reset Completo

Se tutto fallisce, reset completo:

```bash
# 1. Ferma server
lando stop

# 2. Pulisci cache
rm -rf /tmp/php_cache

# 3. Verifica .env
cat .env

# 4. Riavvia
lando start

# 5. Test API
curl http://localhost/json/sites/mercury.local/index.html

# 6. Accedi a test
http://localhost:8000/test-api.php
```

---

## Conclusione

La pagina di test `test-api.php` è il tuo migliore amico per il debugging. Inizia sempre da lì per verificare che l'API OpenCMS funzioni correttamente.

**Ordine di debug**:
1. ✅ API OpenCMS raggiungibile
2. ✅ Containers presenti
3. ✅ Elementi presenti
4. ✅ FormatterKeys supportati
5. ✅ Contenuti elementi caricabili
6. ✅ Template renderizza correttamente

Buon debugging! 🐛🔍
