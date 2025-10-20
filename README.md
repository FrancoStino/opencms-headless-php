# OpenCMS Headless PHP

Un'applicazione PHP moderna per consumare contenuti da OpenCMS tramite API headless. Ispirata al progetto Next.js OpenCMS con un'interfaccia utente pulita e responsive.

## 🚀 Caratteristiche

- **Container Pages**: Homepage e pagine complesse con layout dinamici
- **Hero Slider**: Carousel full-width con autoplay e navigazione
- **Contact Forms**: Form dinamici con validazione
- **List/Detail Views**: Navigazione tra liste di contenuti e dettagli
- **Multi-tipo**: Supporto per Articles, Contacts, FAQs
- **Multilingua**: Cambio dinamico tra lingue (EN/IT)
- **Dark Mode**: Tema scuro con persistenza localStorage
- **Responsive**: Ottimizzato per mobile, tablet e desktop
- **Modern UI**: Design pulito con TailwindCSS e animazioni
- **Navigation Menu**: Menu globale con supporto mobile

## 📋 Requisiti

- **Lando** installato (per ambiente di sviluppo)
- **PHP 8.0+** (per esecuzione standalone)
- **OpenCMS** con API JSON attiva su `http://localhost`

## 🛠️ Installazione

### Opzione 1: Con Lando (Raccomandato)

```bash
# Avvia l'ambiente
lando start

# Accedi all'applicazione
# URL mostrato da Lando (tipicamente https://opencms-headless-php.lndo.site)
```

### Opzione 2: Server PHP Built-in

```bash
# Avvia il server
php -S localhost:8000 -t public

# Accedi all'applicazione
# http://localhost:8000
```

## 📁 Struttura del Progetto

```
opencms-headless-php/
├── public/
│   └── index.php              # Entry point
├── src/
│   ├── Api/
│   │   └── ApiClient.php      # Client per API OpenCMS
│   ├── Controllers/
│   │   └── ViewController.php # Controller principale
│   ├── Model/
│   │   ├── Page.php           # Modello pagina
│   │   └── Component/         # Componenti (Paragraph, Image, etc.)
│   └── Router.php             # Router applicazione
├── templates/
│   ├── layout.php             # Layout principale
│   ├── list.php               # Vista lista
│   ├── detail.php             # Vista dettaglio
│   ├── exception.php          # Pagina errore
│   └── components/            # Template per tipi specifici
│       ├── article-detail.php
│       ├── faq-detail.php
│       └── contact-detail.php
├── .env                       # Configurazione ambiente
├── OPENCMS_API_STRUCTURE.md   # Documentazione API
└── MIGRATION_GUIDE.md         # Guida migrazione da Next.js
```

## 🎯 Utilizzo

### Navigazione Base

```
# Homepage (container page con slider, sezioni, etc.)
http://localhost:8000/

# Pagina Contatti (container page con form)
http://localhost:8000/contatti

# Lista articoli/news
http://localhost:8000/?type=article-m&locale=en

# Lista con tipo specifico
http://localhost:8000/?type=contact-m&locale=it
http://localhost:8000/?type=faq-m&locale=en

# Dettaglio contenuto
http://localhost:8000/?path=/sites/mercury.local/.content/article-m/a_00001.xml&type=article-m&locale=en
```

### Tipi di Contenuto Supportati

| Tipo | Descrizione | Formatter OpenCMS |
|------|-------------|-------------------|
| `article-m` | Articoli con testo e immagini | m-article |
| `contact-m` | Contatti con form | m-contact |
| `faq-m` | Domande frequenti | m-faq |

### Lingue Supportate

- **en**: English
- **it**: Italiano

## ⚙️ Configurazione

### File `.env`

```env
OPENCMS_SERVER=http://localhost
```

### Personalizzazione

**Aggiungere nuovi tipi di contenuto:**

1. Aggiorna `templates/list.php` - array `$availableTypes`
2. Crea template in `templates/components/[tipo]-detail.php`
3. Aggiorna ApiClient se necessario per parsing specifico

**Aggiungere nuove lingue:**

1. Aggiorna `templates/list.php` - array `$availableLocales`
2. Verifica che OpenCMS supporti la lingua

## 🐛 Troubleshooting

### Pagine Vuote?

**Accedi subito alla pagina di test:**
```
http://localhost:8000/test-api.php
```

Questa pagina diagnosticherà automaticamente il problema e ti dirà:
- ✅ Se l'API OpenCMS è raggiungibile
- ✅ Se i containers esistono
- ✅ Quali elementi sono presenti
- ❌ Dove si trova esattamente il problema

**Per maggiori dettagli vedi:**
- **[QUICK_START.md](./QUICK_START.md)** - Guida rapida con soluzioni immediate
- **[DEBUGGING.md](./DEBUGGING.md)** - Guida completa al debugging

## 📚 Documentazione

- **[QUICK_START.md](./QUICK_START.md)** - 🚀 Guida rapida e troubleshooting
- **[DEBUGGING.md](./DEBUGGING.md)** - 🐛 Guida completa al debugging
- **[CONTAINER_PAGES.md](./CONTAINER_PAGES.md)** - Guida container pages e componenti
- **[IMPLEMENTATION_SUMMARY.md](./IMPLEMENTATION_SUMMARY.md)** - Riepilogo implementazione
- **[OPENCMS_API_STRUCTURE.md](./OPENCMS_API_STRUCTURE.md)** - Struttura completa dell'API OpenCMS
- **[MIGRATION_GUIDE.md](./MIGRATION_GUIDE.md)** - Guida migrazione da Next.js

## 🎨 Componenti UI

### Dark Mode
- Toggle nel header
- Persistenza in localStorage
- Supporto preferenze sistema

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px)
- Grid adattivo per liste

### Animazioni
- Hover effects su card
- Transizioni smooth
- Transform su immagini

## 🔧 Comandi Lando

```bash
# Verifica versione PHP
lando php -v

# Accesso shell container
lando ssh

# Ferma il progetto
lando stop

# Riavvia il progetto
lando restart

# Elimina il progetto
lando destroy

# Rebuild completo
lando rebuild -y
```

## 🐛 Troubleshooting

### API non disponibile
```
Errore: Cannot connect to OpenCMS API
```
**Soluzione**: Verifica che OpenCMS sia in esecuzione su `http://localhost` e che l'API JSON sia attiva.

### Contenuti non visualizzati
```
No content found
```
**Soluzione**: 
- Verifica il tipo di contenuto esista in OpenCMS
- Controlla la struttura dati in `ApiClient.php`
- Verifica la lingua selezionata sia disponibile

### Immagini non caricate
**Soluzione**: Verifica che il path delle immagini sia corretto e accessibile. Le immagini devono essere servite da OpenCMS.

## 🚧 Roadmap

- [ ] Implementare caching API
- [ ] Aggiungere pagination per liste lunghe
- [ ] Implementare ricerca full-text
- [ ] Aggiungere breadcrumbs
- [ ] Menu di navigazione dinamico da OpenCMS
- [ ] Supporto per slider/carousel
- [ ] Form contact funzionante con invio email
- [ ] SEO: meta tags dinamici
- [ ] PWA support

## 📄 Licenza

Questo progetto è open source e disponibile sotto licenza MIT.

## 🤝 Contributi

Contributi, issues e feature requests sono benvenuti!

## 📞 Supporto

Per domande o supporto, consulta la [documentazione OpenCMS](https://documentation.opencms.org/).
