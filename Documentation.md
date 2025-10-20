# OpenCMS Headless PHP - Documentation

## Panoramica del Progetto

Questo progetto implementa un sito web headless basato su PHP che utilizza l'API JSON di OpenCMS per recuperare
contenuti. Il sistema supporta rendering dinamico di pagine container, liste di contenuti e viste dettagliate, con
supporto multilingua e dark mode.

### Scopo Principale

Creare un'alternativa PHP all'implementazione Next.js originale, mantenendo la stessa esperienza utente e funzionalità
attraverso un'architettura MVC server-side.

---

## Architettura del Sistema

### Pattern Architetturale

- **MVC (Model-View-Controller)**: Separazione chiara tra logica di business, modelli dati e presentazione
- **Component-based rendering**: Utilizzo di componenti riutilizzabili per il rendering dei contenuti
- **Template inheritance**: Sistema di layout con inclusione di template parziali

### Struttura delle Directory

```
/
├── src/                          # Codice sorgente PHP
│   ├── Api/                      # Client API
│   ├── Controllers/              # Controller MVC
│   ├── Model/                    # Modelli dati
│   └── Components/               # Componenti di rendering
├── templates/                    # Template PHP
│   ├── components/               # Componenti template
│   ├── layout.php                # Layout principale
│   ├── list.php                  # Vista lista contenuti
│   ├── detail.php                # Vista dettaglio
│   └── page.php                  # Rendering pagine container
├── public/                       # File pubblici
└── composer.json                 # Dipendenze PHP
```

---

## API Integration

### ApiClient Class

La classe `App\Api\ApiClient` gestisce tutte le comunicazioni con l'API OpenCMS JSON.

#### Endpoint Base

```php
private $apiEndpoint = 'http://localhost/json/sites/mercury.local';
```

#### Metodi Principali

##### `loadContentList($type, $locale): array`

- **Scopo**: Carica liste di contenuti per tipo (articoli, FAQ, contatti)
- **URL**: `{apiEndpoint}/.content/{type}?content&wrapper&locale={locale}&fallbackLocale`
- **Ritorna**: Array di oggetti `Page`

##### `loadContentDetail($path, $locale): ?Page`

- **Scopo**: Carica il dettaglio di un singolo contenuto
- **URL**: `{baseUrl}/json{path}?content&wrapper&locale={locale}&fallbackLocale`
- **Ritorna**: Oggetto `Page` o null

##### `loadContainerPage($pagePath, $locale): ?array`

- **Scopo**: Carica pagine container con struttura complessa (homepage, pagine contatti)
- **Gestisce**: Risoluzione automatica da path cartella a `index.html`
- **Ritorna**: Array con struttura container/elements

---

## Modelli Dati

### Page Model

La classe `App\Model\Page` rappresenta un contenuto del CMS.

```php
class Page {
    public string $title;
    public string $path;
    public ?string $intro = null;
    public ?string $author = null;
    public ?string $image = null;
    public array $components = [];
}
```

#### Struttura dei Dati

- **title**: Titolo del contenuto
- **path**: Percorso API del contenuto
- **intro**: Testo introduttivo (opzionale)
- **author**: Autore del contenuto (opzionale)
- **image**: URL immagine principale (opzionale)
- **components**: Array di componenti per il rendering

### Component Factory

Il `ComponentFactory` crea istanze di componenti basandosi sul tipo.

```php
class ComponentFactory {
    public static function create(array $data): ?BaseComponent {
        switch ($data['type']) {
            case 'paragraph':
                return new Paragraph($data);
            case 'image':
                return new Image($data);
        }
    }
}
```

---

## Controller

### ViewController Class

Gestisce il routing e la logica di presentazione.

#### Metodi Principali

##### `showList($type, $locale)`

- Carica lista contenuti per tipo
- Gestisce errori API
- Include template `list.php`

##### `showDetail($path, $locale)`

- Carica dettaglio singolo contenuto
- Gestisce errori API
- Include template `detail.php`

##### `showPage($pagePath, $locale)`

- Carica pagine container (homepage, contatti)
- Gestisce struttura nested containers
- Include template `page.php`

---

## Componenti di Rendering

### Architettura dei Componenti

I componenti sono divisi in due categorie:

1. **Model Components** (`src/Model/Component/`): Gestiscono dati
2. **View Components** (`src/Components/`): Gestiscono presentazione

### Componenti Principali

#### Article Component

- **Classe**: `App\Components\Article`
- **Modalità**: preview/detail
- **Caratteristiche**: Rendering responsive, immagini, autore, intro

#### Contact Component

- **Classe**: `App\Components\Contact`
- **Funzionalità**: Form contatti, informazioni contatto
- **Validazione**: Client-side con JavaScript

#### FAQ Component

- **Classe**: `App\Components\Faq`
- **Layout**: Icona + box risposta colorato
- **Styling**: Hover effects

#### Paragraph Component

- **Gestisce**: Testo, immagini, titoli, caption
- **Supporto**: YouTube embed (auto-detect)

---

## Sistema di Template

### Layout Principale (`templates/layout.php`)

- **DOCTYPE HTML5** con meta tags responsive
- **TailwindCSS** via CDN con configurazione custom
- **Dark mode** support con classe `dark`
- **Configurazione colori** primary custom
- **JavaScript** per toggle dark mode

### Template Specifici

#### `list.php`

- Vista griglia responsive per liste contenuti
- Dropdown per selezione tipo/locale
- Card con hover effects
- Paginazione implicita (tutti contenuti caricati)

#### `detail.php`

- Rendering type-specific dei contenuti
- Include componenti appropriati per tipo
- Layout pulito e leggibile

#### `page.php`

- Rendering complesso per pagine container
- Gestione struttura nested containers/elements
- Fetch dinamico contenuti elementi
- Supporto formatter multipli

---

## Routing e URL

### Sistema di Routing

- **Frontend routing**: Query parameters invece di path-based
- **Rewrite rules**: `.htaccess` per URL puliti
- **Parametri**: `type`, `locale`, `path`

### Esempi URL

```
# Homepage (container page)
/ → showPage('/sites/mercury.local/index.html', 'en')

# Pagina contatti
/contatti → showPage('/sites/mercury.local/contatti/index.html', 'en')

# Lista articoli
/?type=article-m&locale=en → showList('article-m', 'en')

# Dettaglio articolo
/?path=/sites/mercury.local/.content/article-m/a_00001.xml&type=article-m&locale=en → showDetail(path, 'en')
```

---

## API OpenCMS Structure

### Tipi di Risorse

1. **Container Page**: Pagine con struttura containers/elements
2. **Content Folder**: Cartelle speciali `.content/` con XML
3. **Image**: Risorse immagine con metadati dimensione
4. **Folder**: Cartelle di navigazione

### Struttura Container Page

```json
{
  "containers": [
    {
      "elements": [
        {
          "formatterKey": "m/section/text-image",
          "path": "/sites/mercury.local/.content/section-m/cs_00001.xml",
          "settings": {
            "hsize": "h1",
            "imageRatio": "4:3"
          }
        }
      ]
    }
  ]
}
```

### Formatter Supportati

| FormatterKey                  | Componente       | Status     |
|-------------------------------|------------------|------------|
| `m/element/slider-hero`       | Slider carousel  | ✅          |
| `m/section/text-only`         | Testo semplice   | ✅          |
| `m/section/text-image`        | Testo + immagine | ✅          |
| `m/webform/webform`           | Form contatto    | ✅          |
| `m/display/article-elaborate` | Teaser articolo  | ✅          |
| `m/layout/group`              | Layout wrapper   | ⚠️ Wrapper |

---

## Funzionalità Implementate

### ✅ Completate

- **Sistema Container Pages**: Rendering dinamico homepage/contatti
- **Liste Contenuti**: Articoli, FAQ, Contatti con filtri
- **Dettagli Type-Specific**: Layout ottimizzato per tipo contenuto
- **Multilingua**: EN/IT con fallback automatico
- **Dark Mode**: Toggle con persistenza localStorage
- **Responsive Design**: Mobile-first con breakpoint Tailwind
- **Navigazione**: Menu header con hamburger mobile
- **SEO**: Meta tags dinamici, struttura semantica

### 🔄 Da Implementare

- **Backend Form**: Gestione invio email form contatti
- **Caching API**: Redis/file per performance
- **Error Handling**: Logging e gestione errori robusta
- **Pagination**: Per liste contenuti lunghe
- **Search**: Ricerca full-text contenuti

---

## Configurazione e Deployment

### Dipendenze

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  }
}
```

### Variabili Ambiente

```bash
OPENCMS_SERVER=http://localhost  # URL server OpenCMS
```

### Web Server

- **Development**: `php -S localhost:8000 -t public`
- **Production**: Apache/Nginx con rewrite rules
- **Rewrite Rule**: `RewriteRule ^(.*)$ index.php [QSA,L]`

---

## Flusso di Rendering

### 1. Richiesta Utente

```
Utente → URL → index.php → Router → Controller
```

### 2. Fetch Dati

```
Controller → ApiClient → API OpenCMS → JSON Response
```

### 3. Elaborazione Dati

```
JSON → Page Model → Component Factory → Components
```

### 4. Rendering Template

```
Components → Template → HTML Output → Browser
```

### 5. Esempio Homepage

```
1. GET / → showPage('/sites/mercury.local/index.html')
2. ApiClient carica struttura container
3. page.php itera containers/elements
4. Per ogni element: fetch contenuto da path
5. Identifica formatterKey e renderizza componente
6. Output: Homepage completa con slider, sezioni, etc.
```

---

## Best Practices Implementate

### Codice

- **PSR-4 Autoloading**: Namespace coerenti
- **Type Hints**: Dichiarazioni tipi PHP 7+
- **Error Handling**: Try/catch e validazioni
- **Separation of Concerns**: MVC chiaro

### Performance

- **Lazy Loading**: Contenuti caricati on-demand
- **CDN Resources**: Tailwind via CDN
- **Minimal JS**: Solo funzionalità essenziali

### UX/UI

- **Progressive Enhancement**: Funziona senza JS
- **Accessibility**: Semantica HTML corretta
- **Responsive**: Mobile-first approach
- **Dark Mode**: Support preferenze utente

---

## Troubleshooting

### Problemi Comuni

#### API Non Raggiungibile

```
Sintomi: Pagina bianca, errori fetch
Soluzione: Verifica OPENCMS_SERVER, connessione rete
```

#### Immagini Non Caricate

```
Sintomi: Placeholder grigi, 404 immagini
Soluzione: Verifica baseUrl in template, percorsi relativi
```

#### Form Non Funziona

```
Sintomi: Nessun invio, errori validazione
Soluzione: Implementare backend POST handler
```

#### Dark Mode Non Funziona

```
Sintomi: Toggle non salva, classe non applicata
Soluzione: Verifica localStorage, JS errori console
```

---

## Roadmap Futura

### Priorità Alta

1. **Implementare backend form contact**
2. **Aggiungere caching API responses**
3. **Migliorare error handling e logging**

### Priorità Media

4. **Implementare pagination per liste**
5. **Aggiungere ricerca full-text**
6. **Ottimizzare immagini (lazy loading, WebP)**

### Priorità Bassa

7. **PWA support (service worker, manifest)**
8. **Analytics integration**
9. **Admin panel per configurazione**

---

*Documentazione generata automaticamente dall'analisi del codice sorgente - Versione 3.0.0*
