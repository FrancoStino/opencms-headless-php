# ✅ Soluzione al Problema Pagine Vuote

## 🎯 Problema Risolto

Il problema era che il sistema cercava direttamente `/sites/mercury.local/contatti/index.html`, ma l'API ritornava la *
*lista della cartella** invece del file.

## 🔧 Soluzione Implementata

Ho aggiornato `ApiClient.php` per gestire automaticamente questo caso:

### Come Funziona Ora

1. **Richiesta iniziale**: `/sites/mercury.local/contatti/`
2. **API risponde** con lista cartella (JSON con `index.html`, ecc.)
3. **Sistema rileva** che è una folder listing
4. **Cerca** `index.html` nella lista
5. **Carica automaticamente** `/sites/mercury.local/contatti/index.html`
6. **Renderizza** la container page

### Codice Aggiunto

```php
// src/Api/ApiClient.php

public function loadContainerPage($pagePath, $locale): ?array
{
    // Try direct path first
    $url = $this->baseUrl . '/json' . $pagePath . '?locale=' . $locale . '&fallbackLocale';
    $data = $this->fetchData($url);
    
    if ($data && is_array($data)) {
        // Check if it's a folder listing
        if ($this->isFolderListing($data)) {
            // Look for index.html in the folder
            if (isset($data['index.html'])) {
                // Try to load index.html
                $indexPath = rtrim($pagePath, '/') . '/index.html';
                $url = $this->baseUrl . '/json' . $indexPath . '?locale=' . $locale . '&fallbackLocale';
                $indexData = $this->fetchData($url);
                if ($indexData && is_array($indexData)) {
                    return $indexData;
                }
            }
        }
        return $data;
    }
    
    return null;
}

private function isFolderListing($data): bool
{
    // A container page has 'containers' array
    if (isset($data['containers'])) {
        return false;
    }
    
    // Check if it looks like a folder listing
    foreach ($data as $key => $value) {
        if (is_array($value) && isset($value['attributes']) && isset($value['isFolder'])) {
            return true;
        }
    }
    
    return false;
}
```

## ✅ Cosa Fare Ora

### 1. Testa la Homepage

```
http://localhost/
```

**Dovrebbe mostrare:**

- ✅ Slider hero (se presente)
- ✅ Sezioni testo/immagine
- ✅ Contenuti vari

### 2. Testa la Pagina Contatti

```
http://localhost/contatti
```

**Dovrebbe mostrare:**

- ✅ Contenuto della pagina contatti
- ✅ Form (se presente)
- ✅ Sezioni varie

**Se ancora vuota:**

- Devi creare un file `index.html` dentro la cartella `/contatti/` in OpenCMS
- Oppure il file esiste ma non ha containers

### 3. Verifica con test-api.php

```
http://localhost/test-api.php
```

Ora dovresti vedere:

- ✅ Test 2: Lista contenuto cartella contatti
- ✅ Test 2b: Se esiste index.html, lo carica

## 📋 Checklist

- [x] Sistema rileva folder listing
- [x] Sistema cerca index.html automaticamente
- [x] Sistema carica index.html se esiste
- [ ] **Creare index.html in /contatti/ in OpenCMS** (se non esiste)
- [ ] **Testare homepage**
- [ ] **Testare pagina contatti**

## 🎨 Come Creare la Pagina Contatti in OpenCMS

Se la pagina `/contatti/index.html` non esiste ancora:

### 1. Accedi a OpenCMS Workplace

```
http://localhost/system/workplace/
```

### 2. Naviga alla Cartella

```
/sites/mercury.local/contatti/
```

### 3. Crea Nuovo File

- Click destro → New → Container Page
- Nome: `index.html`
- Template: Mercury Template

### 4. Aggiungi Contenuti

Aggiungi elementi alla pagina:

- **Text+Image Section** per info contatto
- **Webform** per il form di contatto
- **Text Only Section** per testo aggiuntivo

### 5. Pubblica

- Salva la pagina
- Pubblica (Publish)

### 6. Testa

```
http://localhost/contatti
```

Ora dovrebbe funzionare! ✅

## 🔍 Debug

Se ancora non funziona, usa `test-api.php`:

```
http://localhost/test-api.php
```

Guarda:

- **Test 2**: Mostra contenuto cartella `/contatti/`
- **Test 2b**: Mostra se `index.html` esiste e se è caricabile

## 📊 Struttura Attesa

```
/sites/mercury.local/
├── index.html              ← Homepage (container page) ✅
└── contatti/               ← Cartella contatti
    └── index.html          ← Pagina contatti (container page) ⚠️ Da creare
```

## 💡 Alternative

### Opzione 1: Usa File Diretto

Invece di cartella, crea file diretto:

```
/sites/mercury.local/contatti.html
```

E modifica Router:

```php
if ($path === '/contatti' || $path === '/contatti/') {
    $controller->showPage('/sites/mercury.local/contatti.html', $locale);
}
```

### Opzione 2: Usa Pagina Esistente

Se hai già una pagina demo, usala temporaneamente:

```php
if ($path === '/contatti' || $path === '/contatti/') {
    // Usa pagina demo esistente
    $controller->showPage('/sites/mercury.local/demo/index.html', $locale);
}
```

## 🎉 Conclusione

Il sistema ora gestisce automaticamente:

- ✅ Container pages dirette (`/index.html`)
- ✅ Cartelle con index.html (`/contatti/` → `/contatti/index.html`)
- ✅ Fallback intelligente

**Prossimo passo**: Crea la pagina `/contatti/index.html` in OpenCMS e testa!

---

**Data**: 2025-10-20
**Versione**: 3.0.1
**Status**: ✅ Sistema Aggiornato
