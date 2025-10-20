# Guida alla Migrazione - Stile Next.js

## Modifiche Implementate

Questo progetto PHP è stato aggiornato per replicare la struttura e l'esperienza utente del progetto Next.js OpenCMS (`~/IdeaProjects/OpenCMS/`).

### 1. **Template List View** (`templates/list.php`)

#### Funzionalità Aggiunte:
- **Selezione tipo di contenuto**: Dropdown per scegliere tra `article-m`, `contact-m`, `faq-m`
- **Selezione lingua**: Dropdown per cambiare tra `en` e `it`
- **Card moderne**: Layout a griglia con card responsive
- **Anteprima immagini**: Mostra l'immagine del primo paragrafo
- **Intro text**: Visualizza il testo introduttivo se disponibile
- **Dark mode support**: Classi Tailwind per tema scuro

#### Esempio di utilizzo:
```
http://localhost/?type=article-m&locale=en
```

### 2. **Template Detail View** (`templates/detail.php`)

#### Funzionalità Aggiunte:
- **Navigazione back**: Pulsante per tornare alla lista mantenendo tipo e locale
- **Layout migliorato**: Design più pulito e leggibile
- **Supporto autore**: Visualizza l'autore se presente
- **Intro text**: Testo introduttivo in evidenza
- **Componenti stilizzati**: Paragrafi e immagini con effetti hover
- **Gradient decorativi**: Elementi visivi moderni

### 3. **Layout Principale** (`templates/layout.php`)

#### Funzionalità Aggiunte:
- **Header sticky**: Rimane fisso durante lo scroll
- **Dark mode toggle**: Pulsante per cambiare tema (salvato in localStorage)
- **Footer moderno**: Con link alla documentazione
- **Responsive design**: Ottimizzato per mobile, tablet, desktop
- **Configurazione Tailwind**: Colori personalizzati e utilità

### 4. **Modello Page** (`src/Model/Page.php`)

#### Campi Aggiunti:
```php
public ?string $intro = null;    // Testo introduttivo
public ?string $author = null;   // Autore del contenuto
public ?string $image = null;    // Immagine di anteprima
```

### 5. **Componente Paragraph** (`src/Model/Component/Paragraph.php`)

#### Campi Aggiunti:
```php
public ?string $title = null;    // Titolo del paragrafo
public ?string $image = null;    // Immagine del paragrafo
```

### 6. **ApiClient** (`src/Api/ApiClient.php`)

#### Miglioramenti:
- **Estrazione locale corretta**: Supporta sia `$data[$locale]` che `$data['localeContent']`
- **Estrazione immagini**: Recupera la prima immagine dai paragrafi per l'anteprima
- **Supporto intro/author**: Estrae campi aggiuntivi dall'API
- **URL corretti**: Fix per il path detail con `/json` prefix

### 7. **ViewController** (`src/Controllers/ViewController.php`)

#### Miglioramenti:
- **Passaggio variabili**: `$type` e `$locale` disponibili nei template
- **Persistenza tipo**: Mantiene il tipo di contenuto nella navigazione detail

## Confronto con il Progetto Next.js

| Funzionalità | Next.js | PHP (Questo Progetto) |
|--------------|---------|------------------------|
| List/Detail Views | ✅ | ✅ |
| Selezione tipo contenuto | ✅ | ✅ |
| Selezione lingua | ✅ | ✅ |
| Dark mode | ✅ | ✅ |
| Componenti riutilizzabili | ✅ | ✅ |
| Anteprima immagini | ✅ | ✅ |
| Navigazione persistente | ✅ | ✅ |
| Responsive design | ✅ | ✅ |

## Come Testare

### 1. Avvia il server PHP
```bash
cd /home/adolf/IdeaProjects/opencms-headless-php
php -S localhost:8000 -t public
```

### 2. Accedi all'applicazione
```
http://localhost:8000
```

### 3. Testa le funzionalità

#### Lista articoli (default)
```
http://localhost:8000/?type=article-m&locale=en
```

#### Lista contatti
```
http://localhost:8000/?type=contact-m&locale=en
```

#### Lista FAQ
```
http://localhost:8000/?type=faq-m&locale=en
```

#### Dettaglio (esempio)
```
http://localhost:8000/?path=/sites/mercury.local/.content/article-m/a_00001.xml&type=article-m&locale=en
```

#### Cambia lingua
Usa il dropdown "Language" nell'interfaccia o aggiungi `&locale=it` all'URL

## Struttura dei Dati OpenCms

### Risposta API per Lista
```json
{
  "filename.xml": {
    "path": "/sites/mercury.local/.content/article-m/a_00001.xml",
    "en": {
      "Title": "Article Title",
      "Intro": "Introduction text",
      "Author": "John Doe",
      "Paragraph": [
        {
          "Caption": "Section Title",
          "Text": "<p>HTML content</p>",
          "Image": {
            "link": "http://localhost/image.jpg"
          }
        }
      ]
    }
  }
}
```

### Risposta API per Dettaglio
```json
{
  "path": "/sites/mercury.local/.content/article-m/a_00001.xml",
  "en": {
    "Title": "Article Title",
    "Intro": "Introduction text",
    "Author": "John Doe",
    "Paragraph": [...]
  }
}
```

## Prossimi Passi

### Componenti da Implementare
1. **Contact Form** (`contact-m`)
   - Form con validazione
   - Invio email
   - Feedback utente

2. **FAQ Component** (`faq-m`)
   - Accordion/collapsible
   - Ricerca FAQ
   - Categorie

3. **Slider/Hero** (`m/element/slider-hero`)
   - Carousel immagini
   - Autoplay
   - Controlli navigazione

4. **Navigazione Dinamica**
   - Menu dal sito OpenCms
   - Breadcrumbs
   - Sitemap

### Miglioramenti Suggeriti
1. **Caching**: Implementare cache per le risposte API
2. **Error handling**: Gestione errori più robusta
3. **Loading states**: Spinner durante il caricamento
4. **SEO**: Meta tags dinamici
5. **Pagination**: Per liste lunghe
6. **Search**: Ricerca full-text nei contenuti

## Differenze Chiave PHP vs Next.js

### Routing
- **Next.js**: File-based routing automatico
- **PHP**: Router manuale con query parameters

### State Management
- **Next.js**: React hooks (useState, useEffect)
- **PHP**: Session e query parameters

### Rendering
- **Next.js**: Client-side rendering con React
- **PHP**: Server-side rendering con template PHP

### API Calls
- **Next.js**: fetch() client-side
- **PHP**: file_get_contents() server-side

## Risorse

- [OpenCms API Structure](./OPENCMS_API_STRUCTURE.md) - Documentazione completa dell'API
- [TailwindCSS](https://tailwindcss.com/docs) - Framework CSS utilizzato
- [OpenCms Documentation](https://documentation.opencms.org/) - Documentazione ufficiale
