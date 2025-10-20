# Changelog

Tutte le modifiche notevoli a questo progetto saranno documentate in questo file.

## [2.0.0] - 2025-10-20

### 🎉 Major Refactoring - Stile Next.js

Refactoring completo dell'applicazione per replicare la struttura e l'esperienza utente del progetto Next.js OpenCMS.

### ✨ Aggiunte

#### Templates
- **list.php**: Vista lista completamente rinnovata
  - Dropdown per selezione tipo contenuto (article-m, contact-m, faq-m)
  - Dropdown per selezione lingua (en, it)
  - Card moderne con hover effects
  - Anteprima immagini
  - Supporto intro text
  - Layout responsive a griglia

- **detail.php**: Vista dettaglio con rendering type-specific
  - Navigazione back con persistenza parametri
  - Supporto per componenti specifici per tipo
  - Layout migliorato con prose styling

- **layout.php**: Layout principale modernizzato
  - Header sticky con logo e titolo
  - Dark mode toggle con persistenza localStorage
  - Footer moderno con link
  - Configurazione Tailwind personalizzata
  - Supporto tema scuro completo

- **components/**: Template specifici per tipo di contenuto
  - `article-detail.php`: Rendering articoli con autore, intro, paragrafi
  - `faq-detail.php`: Rendering FAQ con icona e box risposta
  - `contact-detail.php`: Rendering contatti con form integrato

#### Modelli
- **Page.php**: Campi aggiuntivi
  - `intro`: Testo introduttivo
  - `author`: Autore del contenuto
  - `image`: Immagine di anteprima

- **Paragraph.php**: Campi aggiuntivi
  - `title`: Titolo del paragrafo
  - `image`: Immagine del paragrafo

#### API Client
- **ApiClient.php**: Miglioramenti parsing
  - Supporto per estrazione locale corretta (`$data[$locale]`)
  - Estrazione automatica prima immagine per anteprima
  - Supporto campi intro/author
  - Fix URL per detail view con prefix `/json`

#### Controller
- **ViewController.php**: Passaggio variabili
  - Variabili `$type` e `$locale` disponibili nei template
  - Persistenza tipo contenuto nella navigazione

#### Documentazione
- **OPENCMS_API_STRUCTURE.md**: Guida completa struttura API OpenCMS
- **MIGRATION_GUIDE.md**: Guida migrazione da Next.js
- **TEST.md**: Checklist completa per testing
- **README.md**: Documentazione aggiornata e completa

### 🎨 Miglioramenti UI/UX

- **Dark Mode**: Toggle con persistenza e supporto preferenze sistema
- **Responsive Design**: Ottimizzato per mobile, tablet, desktop
- **Animazioni**: Hover effects, transizioni smooth, transform su immagini
- **Typography**: Gerarchia visiva migliorata con prose styling
- **Colors**: Palette colori moderna con gradient
- **Spacing**: Spaziatura consistente e respirabile
- **Accessibility**: Migliore contrasto e navigazione keyboard

### 🔧 Modifiche Tecniche

- **Router**: Supporto completo per parametri type e locale
- **Template System**: Rendering condizionale basato su tipo contenuto
- **Component Factory**: Supporto per nuovi campi componenti
- **Error Handling**: Gestione errori migliorata
- **Code Organization**: Separazione template per tipo in cartella components/

### 📚 Documentazione

- Guida completa API OpenCMS con esempi
- Documentazione migrazione da Next.js
- README aggiornato con esempi e troubleshooting
- Checklist testing completa
- Changelog dettagliato

### 🐛 Bug Fixes

- Fix URL API per detail view (aggiunto prefix `/json`)
- Fix estrazione locale da risposta API
- Fix passaggio parametri tra list e detail view
- Fix rendering HTML nei paragrafi (rimosso htmlspecialchars per text)

### 🔄 Confronto con Next.js

| Funzionalità | Next.js | PHP (v2.0.0) | Status |
|--------------|---------|--------------|--------|
| List/Detail Views | ✅ | ✅ | ✅ Completo |
| Selezione tipo contenuto | ✅ | ✅ | ✅ Completo |
| Selezione lingua | ✅ | ✅ | ✅ Completo |
| Dark mode | ✅ | ✅ | ✅ Completo |
| Componenti riutilizzabili | ✅ | ✅ | ✅ Completo |
| Anteprima immagini | ✅ | ✅ | ✅ Completo |
| Navigazione persistente | ✅ | ✅ | ✅ Completo |
| Responsive design | ✅ | ✅ | ✅ Completo |
| Form contatto | ✅ | ⚠️ | ⚠️ UI presente, backend da implementare |
| Slider/Carousel | ✅ | ❌ | 📋 Roadmap |

### 🚀 Performance

- Nessuna dipendenza JavaScript pesante (solo Tailwind CDN)
- Rendering server-side per SEO
- Caricamento rapido pagine
- Animazioni CSS performanti

### 📦 File Modificati

```
Modificati:
- templates/list.php (completo refactoring)
- templates/detail.php (completo refactoring)
- templates/layout.php (completo refactoring)
- src/Model/Page.php (aggiunti campi)
- src/Model/Component/Paragraph.php (aggiunti campi)
- src/Api/ApiClient.php (migliorato parsing)
- src/Controllers/ViewController.php (passaggio variabili)
- README.md (documentazione completa)

Aggiunti:
- templates/components/article-detail.php
- templates/components/faq-detail.php
- templates/components/contact-detail.php
- OPENCMS_API_STRUCTURE.md
- MIGRATION_GUIDE.md
- TEST.md
- CHANGELOG.md
```

### 🎯 Breaking Changes

⚠️ **Attenzione**: Questa versione introduce breaking changes nella struttura dei template.

- Template `list.php` e `detail.php` completamente riscritti
- Struttura URL modificata (aggiunto parametro `type`)
- Modello `Page` richiede nuovi campi opzionali
- Layout richiede TailwindCSS CDN

**Migrazione da v1.x**:
1. Backup dei template personalizzati
2. Aggiorna `.env` se necessario
3. Verifica compatibilità con nuova struttura API
4. Testa tutte le funzionalità

### 📝 Note

- Compatibile con PHP 8.0+
- Richiede OpenCMS con API JSON attiva
- TailwindCSS caricato da CDN (nessun build step)
- Dark mode richiede JavaScript abilitato

---

## [1.0.0] - Data Precedente

### Versione Iniziale

- Struttura base progetto
- Router semplice
- Template base list/detail
- Componenti base (Article, Paragraph, Image)
- ApiClient base
- Layout semplice

---

## Formato

Questo changelog segue il formato [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
e questo progetto aderisce al [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### Tipi di Cambiamenti

- **Added** (Aggiunte): Nuove funzionalità
- **Changed** (Modifiche): Cambiamenti a funzionalità esistenti
- **Deprecated** (Deprecati): Funzionalità che saranno rimosse
- **Removed** (Rimosse): Funzionalità rimosse
- **Fixed** (Corrette): Bug fix
- **Security** (Sicurezza): Vulnerabilità corrette
