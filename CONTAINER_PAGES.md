# Container Pages - Guida Implementazione

## Panoramica

Il sistema ora supporta le **Container Pages** di OpenCMS, permettendo di creare homepage e pagine complesse con layout dinamici basati su container ed elementi.

## Struttura Container Page

### Risposta API

```json
{
  "attributes": {
    "type": "containerpage"
  },
  "containers": [
    {
      "elements": [
        {
          "formatterKey": "m/element/slider-hero",
          "path": "/sites/mercury.local/.content/slider-m/slider_00001.xml",
          "settings": {
            "rotationTime": "3000",
            "showArrows": "true"
          }
        }
      ]
    }
  ]
}
```

## Componenti Implementati

### 1. **Slider Hero** (`m/element/slider-hero`)

Carousel hero con immagini full-width e contenuto sovrapposto.

**Caratteristiche:**
- Autoplay configurabile
- Navigazione frecce
- Dots navigation
- Pause on hover
- Transizioni smooth
- Responsive

**Esempio contenuto:**
```json
{
  "Slide": [
    {
      "Title": "Welcome",
      "Text": "<p>Description</p>",
      "Image": {
        "link": "http://localhost/image.jpg"
      },
      "Link": {
        "link": "/page",
        "text": "Learn More"
      }
    }
  ]
}
```

### 2. **Text Only Section** (`m/section/text-only`)

Sezione con solo testo, centrata.

**Caratteristiche:**
- Titolo
- Testo HTML
- Link/Button opzionale
- Allineamento centrato

### 3. **Text + Image Section** (`m/section/text-image`)

Sezione con testo e immagine affiancati.

**Caratteristiche:**
- Layout 2 colonne (responsive)
- Immagine a sinistra o destra (configurabile)
- Titolo, testo, link
- Responsive (stack su mobile)

**Settings:**
- `pieceLayout: "6"` = Immagine a sinistra
- `pieceLayout: "4"` = Immagine a destra

### 4. **Webform** (`m/webform/webform`)

Form di contatto completo.

**Caratteristiche:**
- Campi dinamici (text, textarea, email, etc.)
- Validazione client-side
- Campi obbligatori
- Captcha semplice
- Messaggi errore personalizzati
- Responsive

**Campi supportati:**
- `text`: Input testo
- `textarea`: Area testo multi-riga
- `email`: Input email con validazione
- Pattern validation con regex

### 5. **Article Teaser** (`m/display/article-elaborate`)

Anteprima articolo con link al dettaglio.

**Caratteristiche:**
- Immagine + testo
- Layout 2 colonne
- Link al dettaglio
- Hover effects

## Routing

### Homepage
```
http://localhost/
→ Carica /sites/mercury.local/index.html
```

### Pagina Contatti
```
http://localhost/contatti
→ Carica /sites/mercury.local/contatti/index.html
```

### News (Lista)
```
http://localhost/?type=article-m&locale=en
→ Lista articoli
```

### Dettaglio Articolo
```
http://localhost/?path=/sites/mercury.local/.content/article-m/a_00001.xml&locale=en
→ Dettaglio articolo
```

## Navigazione

Il menu di navigazione è stato aggiunto al layout:

- **Home**: `/`
- **Contatti**: `/contatti`
- **News**: `?type=article-m&locale=en`

### Desktop
Menu orizzontale nel header.

### Mobile
Menu hamburger con dropdown.

## Template page.php

Il template `page.php` gestisce il rendering delle container pages:

1. Itera attraverso i `containers`
2. Per ogni `element`:
   - Fetch del contenuto dal `path`
   - Rendering basato su `formatterKey`
   - Applicazione `settings`

### Formatter Supportati

| FormatterKey | Componente | Template |
|--------------|------------|----------|
| `m/element/slider-hero` | Hero Slider | `slider-hero.php` |
| `m/section/text-only` | Text Section | Inline |
| `m/section/text-image` | Text+Image | Inline |
| `m/webform/webform` | Contact Form | `webform.php` |
| `m/display/article-elaborate` | Article Teaser | Inline |
| `m/detail/media` | Media Display | Inline |

## Aggiungere Nuovi Formatter

### 1. Creare Template Componente

```php
// templates/components/nuovo-componente.php
<?php
/** @var array $localeContent */
/** @var array $settings */
?>

<section class="...">
    <!-- Rendering componente -->
</section>
```

### 2. Aggiungere al page.php

```php
<?php elseif ($formatterKey === 'm/nuovo/componente'): ?>
    <?php require __DIR__ . '/components/nuovo-componente.php'; ?>
```

## Nested Containers

Le container pages supportano container annidati:

```
containers[]
  → elements[]
    → containers[]
      → elements[]
```

Il sistema gestisce automaticamente un livello di nesting. Per supportare nesting più profondo, implementare rendering ricorsivo.

## Esempi Pratici

### Homepage Tipica

```
1. Hero Slider (full-width)
2. Text Only Section (intro)
3. Text+Image Section (features)
4. Article Teaser (latest news)
5. Text Only Section (CTA)
```

### Pagina Contatti

```
1. Text+Image Section (info)
2. Webform (contact form)
3. Text Only Section (additional info)
```

## Configurazione

### Router.php

Gestisce il routing per:
- Homepage (`/`)
- Pagine specifiche (`/contatti`)
- Liste contenuti (`?type=...`)
- Dettagli contenuti (`?path=...`)

### ViewController.php

Metodo `showPage($pagePath, $locale)`:
- Carica container page da API
- Passa `$containers` al template
- Renderizza `page.php`

### ApiClient.php

Metodo `loadContainerPage($pagePath, $locale)`:
- Fetch pagina da API
- Ritorna array completo con containers

## Best Practices

### 1. Caching
```php
// Implementare cache per container pages
$cacheKey = md5($pagePath . $locale);
if ($cached = getCache($cacheKey)) {
    return $cached;
}
```

### 2. Error Handling
```php
if (!$elementContent) {
    // Log errore
    error_log("Failed to load element: $elementPath");
    continue; // Skip elemento
}
```

### 3. Performance
- Lazy load immagini slider
- Minify CSS/JS inline
- Ottimizzare query API

### 4. Accessibilità
- Alt text per immagini
- ARIA labels per controlli
- Keyboard navigation per slider

## Troubleshooting

### Slider non funziona
**Problema**: JavaScript non caricato
**Soluzione**: Verificare che `slider-hero.php` sia incluso correttamente

### Form non invia
**Problema**: Backend non implementato
**Soluzione**: Implementare handler in `public/index.php`:
```php
if ($_POST && $_GET['action'] === 'submit-form') {
    // Process form
}
```

### Immagini non caricate
**Problema**: Path immagini non corretto
**Soluzione**: Verificare `$baseUrl` in `page.php`

### Container vuoti
**Problema**: Fetch elemento fallisce
**Soluzione**: Verificare URL API e struttura JSON

## Testing

### Test Homepage
```bash
curl http://localhost/
# Dovrebbe mostrare slider, sezioni, etc.
```

### Test Contatti
```bash
curl http://localhost/contatti
# Dovrebbe mostrare form contatto
```

### Test API Container
```bash
curl http://localhost/json/sites/mercury.local/index.html
# Verifica struttura containers
```

## Prossimi Passi

1. **Implementare backend form**
   - Validazione server-side
   - Invio email
   - Protezione CSRF

2. **Aggiungere più formatter**
   - Linkbox
   - Gallery
   - Video player
   - Accordion

3. **Ottimizzare performance**
   - Caching API
   - Image optimization
   - Lazy loading

4. **Migliorare slider**
   - Touch gestures
   - Keyboard navigation
   - Preload immagini

## Risorse

- **Template**: `templates/page.php`
- **Componenti**: `templates/components/`
- **Router**: `src/Router.php`
- **Controller**: `src/Controllers/ViewController.php`
- **API Client**: `src/Api/ApiClient.php`
