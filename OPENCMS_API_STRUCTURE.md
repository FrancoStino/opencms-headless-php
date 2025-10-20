# OpenCms Headless API - Struttura e Guida

## Panoramica

L'API headless di OpenCms espone il contenuto del sito attraverso endpoint JSON. Questa guida spiega come navigare e utilizzare l'API per costruire un sito.

## Endpoint Base

```
http://localhost/json/sites/mercury.local/
```

## Struttura dell'API

### 1. **Listing delle Risorse (Directory/Folder)**

Quando accedi a una cartella (es. `/json/sites/mercury.local/`), ottieni un oggetto JSON con tutte le risorse contenute:

```json
{
  "resource-name": {
    "attributes": {
      "lastModified": timestamp,
      "type": "tipo-risorsa"
    },
    "isFolder": boolean,
    "isXmlContent": boolean,
    "link": "url-completo",
    "path": "percorso-interno",
    "properties": {
      "Title": "titolo",
      "NavPos": "posizione-navigazione",
      "NavText": "testo-navigazione",
      "template": "template-path"
    },
    "own_properties": {},
    "requestParams": {}
  }
}
```

### 2. **Tipi di Risorse**

#### **Folder** (`type: "folder"`)
- `isFolder: true`
- `isXmlContent: false`
- Contiene altre risorse (pagine, immagini, contenuti)
- Proprietà di navigazione: `NavPos`, `NavText`, `Title`

#### **Container Page** (`type: "containerpage"`)
- `isFolder: false`
- `isXmlContent: true`
- Rappresenta una pagina del sito
- Contiene containers con elementi di contenuto

#### **Image** (`type: "image"`)
- `isFolder: false`
- `isXmlContent: false`
- Proprietà aggiuntive: `image.size` (es. "w:1200,h:800")
- Link diretto all'immagine in `link`

#### **Content Folder** (`type: "content_folder"`)
- Cartella speciale `.content/` che contiene i contenuti XML
- Non visibile nella navigazione

### 3. **Struttura di una Container Page**

Quando accedi a una pagina (es. `/json/sites/mercury.local/index.html`):

```json
{
  "attributes": {
    "lastModified": timestamp,
    "type": "containerpage"
  },
  "containers": [
    {
      "elements": [
        {
          "containers": [],
          "formatterKey": "m/layout/group",
          "path": "/sites/mercury.local/.content/modelgroup/modelgroup-00003.html",
          "settings": {
            "SYSTEM::pageId": "uuid",
            "addBottomMargin": "true",
            "headerPosition": "css",
            ...
          }
        }
      ],
      "isDetailOnlyContainer": false,
      "isNestedContainer": true,
      "isRootContainer": false,
      "name": "container-name",
      "type": "element"
    }
  ]
}
```

**Elementi chiave:**
- `containers`: array di container che organizzano il layout
- `elements`: array di elementi di contenuto dentro ogni container
- `formatterKey`: tipo di formatter/componente (es. `m/section/text-image`, `m/webform/webform`)
- `path`: percorso al contenuto XML effettivo
- `settings`: configurazioni specifiche per il rendering

### 4. **Tipi di Formatter/Componenti**

Basandosi sull'analisi dell'API, i principali formatter sono:

#### **Layout**
- `m/layout/group` - Gruppo di layout per organizzare la pagina

#### **Sezioni**
- `m/section/text-only` - Solo testo
- `m/section/text-image` - Testo con immagine
- Settings comuni: `hsize`, `textAlignment`, `imageRatio`, `linkOption`, `effect`

#### **Slider**
- `m/element/slider-hero` - Slider hero/carousel
- Settings: `rotationTime`, `showArrows`, `showDots`, `transition`, `imageRatioLarge`, `imageRatioSmall`

#### **Webform**
- `m/webform/webform` - Form di contatto
- Settings: `displayType`, `formCssWrapper`, `formModus`

### 5. **Struttura dei Contenuti XML**

Quando accedi a un contenuto specifico (es. `/json/sites/mercury.local/.content/section-m/cs_00002.xml`):

```json
{
  "attributes": {
    "lastModified": timestamp,
    "type": "m-section"
  },
  "en": {
    "Text": "<p>Contenuto HTML</p>",
    "Title": "Titolo"
  },
  "isFolder": false,
  "isXmlContent": true,
  "link": "url",
  "locales": ["en"],
  "path": "percorso",
  "properties": {
    "Title": "titolo"
  }
}
```

**Caratteristiche:**
- Contenuti multilingua: chiavi per locale (es. `en`, `it`)
- Campi specifici per tipo (es. `Text`, `Title`, `Image`, `Link`)
- HTML già formattato nei campi di testo

### 6. **Struttura Webform**

```json
{
  "en": {
    "FormCaptcha": {
      "FieldLabel": "Security check",
      "Preset": "path-to-captcha-preset"
    },
    "FormConfirmation": "<p>Testo conferma</p>",
    "FormText": "",
    "InputField": [
      {
        "FieldErrorMessage": "Messaggio errore",
        "FieldLabel": "Etichetta campo",
        "FieldMandatory": true,
        "FieldType": "text|textarea|email",
        "FieldValidation": "regex-validazione"
      }
    ],
    "MailFrom": "email@example.com",
    "MailSubject": "Oggetto email",
    "MailTo": "destinatario@example.com",
    "MailType": "html|text"
  }
}
```

## Come Costruire un Sito

### Passo 1: Ottenere la Struttura di Navigazione

1. Fai una richiesta a `/json/sites/mercury.local/`
2. Filtra le risorse con `isFolder: true` e proprietà `NavPos` definita
3. Ordina per `NavPos` per ottenere l'ordine del menu
4. Usa `NavText` o `Title` per il testo del menu
5. Usa `link` per costruire i link di navigazione

### Passo 2: Renderizzare una Pagina

1. Fai una richiesta alla pagina (es. `/json/sites/mercury.local/index.html`)
2. Itera attraverso `containers[].elements[]`
3. Per ogni elemento:
   - Identifica il tipo tramite `formatterKey`
   - Fai una richiesta al `path` per ottenere il contenuto effettivo
   - Renderizza il componente appropriato basandoti su `formatterKey` e `settings`

### Passo 3: Gestire i Contenuti Nested

I container possono essere annidati:
```
containers[] 
  → elements[] 
    → containers[] 
      → elements[]
```

Usa una funzione ricorsiva per processare tutti i livelli.

### Passo 4: Gestire le Immagini

- Le immagini hanno `link` che punta direttamente al file
- Usa `properties.image.size` per ottenere dimensioni originali
- Esempio: `"w:1200,h:800"` → width: 1200px, height: 800px

### Passo 5: Implementare i Form

1. Recupera il contenuto del webform dal `path`
2. Itera attraverso `InputField[]` per creare i campi
3. Implementa la validazione usando `FieldValidation` (regex)
4. Gestisci l'invio email con i parametri `MailFrom`, `MailTo`, `MailSubject`

## Mapping Formatter → Componenti PHP

Basandoti sui componenti già esistenti nel progetto:

| FormatterKey | Componente PHP | Note |
|--------------|----------------|------|
| `m/section/text-only` | `Paragraph.php` | Testo semplice |
| `m/section/text-image` | `Article.php` | Testo + immagine |
| `m/webform/webform` | `Contact.php` | Form di contatto |
| `m/element/slider-hero` | Nuovo componente | Da implementare |
| `m/layout/group` | Layout wrapper | Gestione container |

## Esempio di Flusso Completo

```
1. GET /json/sites/mercury.local/
   → Ottieni lista risorse e costruisci menu

2. GET /json/sites/mercury.local/index.html
   → Ottieni struttura pagina con containers

3. Per ogni element.path:
   GET /json/sites/mercury.local/.content/section-m/cs_00005.xml
   → Ottieni contenuto effettivo

4. Renderizza componente basandoti su:
   - formatterKey (tipo componente)
   - settings (configurazione)
   - contenuto XML (dati)
```

## Proprietà Importanti per la Navigazione

- **NavPos**: Posizione nel menu (es. "1.0", "2.0")
- **NavText**: Testo da mostrare nel menu
- **Title**: Titolo della pagina/risorsa
- **Description**: Meta description per SEO

## Best Practices

1. **Caching**: Cachare le risposte API per migliorare le performance
2. **Lazy Loading**: Caricare i contenuti degli elementi solo quando necessario
3. **Gestione Errori**: Gestire risorse mancanti o errori API
4. **Multilingua**: Usare il campo `locales[]` per supportare più lingue
5. **Responsive**: Usare settings come `imageRatioLarge`, `imageRatioSmall` per responsive design

## Endpoint Utili

```
# Lista risorse root
/json/sites/mercury.local/

# Pagina specifica
/json/sites/mercury.local/index.html

# Cartella specifica
/json/sites/mercury.local/contatti/

# Contenuto XML
/json/sites/mercury.local/.content/section-m/cs_00002.xml

# Immagine diretta
/export/sites/mercury.local/image.jpg
```
