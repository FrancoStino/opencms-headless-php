# 🚀 Quick Start - Diagnosi Pagine Vuote

## ⚠️ Problema Attuale
Le pagine (homepage e contatti) risultano vuote.

## 🔍 Primo Passo: Test API

**Accedi subito a questa pagina:**
```
http://localhost:8000/test-api.php
```

Questa pagina ti dirà immediatamente:
- ✅ Se OpenCMS è raggiungibile
- ✅ Se i containers esistono
- ✅ Quali elementi sono presenti
- ❌ Dove si trova il problema

---

## 📋 Cosa Fare Ora

### 1. Avvia il Server (se non è già avviato)

```bash
cd /home/adolf/IdeaProjects/opencms-headless-php

# Con Lando
lando start

# OPPURE con PHP built-in
php -S localhost:8000 -t public
```

### 2. Accedi alla Pagina di Test

Apri nel browser:
```
http://localhost:8000/test-api.php
```

### 3. Leggi i Risultati

#### ✅ Se tutto è OK:
- Vedrai "✅ Risposta ricevuta"
- Containers: 1 o più
- Elementi presenti con formatterKeys

**→ Il problema è nel rendering, non nell'API**

#### ❌ Se API non raggiungibile:
- Vedrai "❌ ERRORE: Impossibile raggiungere l'API"

**→ OpenCMS non è in esecuzione o URL sbagliato**

**Soluzione:**
1. Verifica che OpenCMS sia avviato
2. Controlla `.env`:
   ```bash
   cat .env
   # Deve contenere: OPENCMS_SERVER=http://localhost
   ```
3. Testa manualmente:
   ```bash
   curl http://localhost/json/sites/mercury.local/
   ```

#### ⚠️ Se pagina non trovata:
- Vedrai "❌ Pagina non trovata"

**→ Il path `/contatti` non esiste in OpenCMS**

**Soluzione:**
Usa un path esistente. Modifica `src/Router.php`:
```php
if ($path === '/contatti' || $path === '/contatti/') {
    // Cambia questo path con uno esistente
    $controller->showPage('/sites/mercury.local/demo/index.html', $locale);
}
```

---

## 🎯 Scenari Comuni

### Scenario A: "API funziona ma pagina vuota"

**Debug automatico attivo!**

Quando accedi alla homepage (`http://localhost:8000/`), vedrai un box giallo con debug info:

```
🔍 Debug Info
Containers: SET
Containers Count: 0
```

**Se Count = 0:**
- L'API risponde ma senza containers
- La pagina non è di tipo `containerpage`
- Verifica con `test-api.php` la struttura JSON

**Se Containers: NOT SET:**
- La variabile non arriva al template
- Problema nel `ViewController.php`
- Controlla i log PHP

### Scenario B: "Tutto sembra OK ma niente si vede"

**Possibili cause:**
1. **FormatterKey non supportato**
   - Vai su `test-api.php`
   - Guarda i formatterKeys degli elementi
   - Verifica che siano in `templates/page.php`

2. **Contenuto elemento non caricato**
   - Il path dell'elemento è sbagliato
   - L'elemento non ha contenuto per la lingua
   - Testa manualmente l'URL dell'elemento

3. **JavaScript non funziona**
   - Apri DevTools Console
   - Cerca errori JavaScript
   - Verifica che slider.js sia caricato

---

## 🛠️ Fix Rapidi

### Fix 1: Disabilita Debug Mode

Se il debug è fastidioso, modifica `templates/page.php`:
```php
// Cambia questa riga:
$showDebug = empty($containers);

// In:
$showDebug = false; // Disabilita debug
```

### Fix 2: Usa Lista come Fallback

Se le container pages non funzionano, usa temporaneamente la lista:

```php
// src/Router.php - linea ~20
if ($path === '/' || $path === '/index.php') {
    // Fallback temporaneo
    $controller->showList('article-m', $locale);
    return;
}
```

### Fix 3: Testa con Path Diverso

Prova un path diverso per la homepage:

```php
// src/Router.php
if ($path === '/' || $path === '/index.php') {
    // Prova diversi path
    $controller->showPage('/sites/mercury.local/', $locale);
    // OPPURE
    $controller->showPage('/sites/mercury.local/demo/', $locale);
}
```

---

## 📊 Checklist Veloce

Segui questa checklist in ordine:

- [ ] **Server avviato** (`lando start` o `php -S ...`)
- [ ] **OpenCMS raggiungibile** (test con `curl`)
- [ ] **test-api.php accessibile** (`http://localhost:8000/test-api.php`)
- [ ] **test-api.php mostra containers** (Count > 0)
- [ ] **Homepage accessibile** (`http://localhost:8000/`)
- [ ] **Debug info visibile** (box giallo se vuota)
- [ ] **Log PHP controllati** (cerca errori)

---

## 🎓 Documentazione Completa

Per approfondire:
- **[DEBUGGING.md](./DEBUGGING.md)** - Guida completa al debugging
- **[CONTAINER_PAGES.md](./CONTAINER_PAGES.md)** - Come funzionano le container pages
- **[IMPLEMENTATION_SUMMARY.md](./IMPLEMENTATION_SUMMARY.md)** - Riepilogo implementazione

---

## 💡 Suggerimenti

### Suggerimento 1: Inizia dalla Lista
Se hai fretta, usa la lista articoli che già funziona:
```
http://localhost:8000/?type=article-m&locale=en
```

### Suggerimento 2: Verifica Progetto Next.js
Confronta con il progetto Next.js di riferimento:
```bash
cd ~/IdeaProjects/OpenCMS/
# Verifica quali pagine esistono
```

### Suggerimento 3: Log in Tempo Reale
Tieni aperto il log mentre testi:
```bash
# Con Lando
lando logs -s appserver -f

# Con PHP built-in
tail -f /var/log/php_errors.log
```

---

## 🆘 Aiuto Immediato

**Se sei bloccato:**

1. **Copia l'output di test-api.php** (tutto il JSON)
2. **Fai screenshot del debug info** (box giallo)
3. **Copia i log PHP** (ultimi 50 righe)

Questo aiuterà a diagnosticare il problema esatto.

---

## ✅ Prossimi Passi

Una volta che `test-api.php` mostra tutto OK:

1. ✅ Verifica che homepage mostri debug info
2. ✅ Controlla che `Containers Count > 0`
3. ✅ Verifica formatterKeys supportati
4. ✅ Testa rendering di ogni componente

**Buona fortuna! 🚀**

---

**Ultimo aggiornamento**: 2025-10-20
**Versione**: 3.0.0-debug
