# JSON Feed API

Das `Neues`-Addon bietet neben dem RSS-Feed auch eine moderne JSON Feed API nach dem [JSON Feed 1.1 Standard](https://jsonfeed.org/version/1.1). Diese API ist ideal für JavaScript-Anwendungen, mobile Apps und moderne Web-Frameworks.

## Grundlegende Verwendung

**Standard JSON Feed (alle Online-Einträge):**
```
index.php?rex-api-call=neues_json
```

## Filteroptionen

Die JSON Feed API unterstützt dieselben Filter wie der RSS-Feed, plus zusätzliche Paginierung:

### Nach Kategorie filtern

```
index.php?rex-api-call=neues_json&category_id=3
```

### Nach Sprache filtern

```
index.php?rex-api-call=neues_json&lang_id=2
```

### Nach Domain filtern

```
index.php?rex-api-call=neues_json&domain_id=1
```

### Paginierung

```
index.php?rex-api-call=neues_json&limit=10&offset=20
```

- **limit**: Anzahl der Einträge pro Seite (Standard: 50)
- **offset**: Startposition (Standard: 0)

### Kombinierte Filter

```
index.php?rex-api-call=neues_json&category_id=3&lang_id=2&limit=25
```

## JSON Feed Format

Die API gibt einen JSON Feed 1.1 kompatiblen Response zurück:

```json
{
  "version": "https://jsonfeed.org/version/1.1",
  "title": "Meine Website | Kategorie Name",
  "description": "News-Feed - Kategorie Name",
  "home_page_url": "https://example.com",
  "feed_url": "https://example.com/?rex-api-call=neues_json&category_id=3",
  "language": "de",
  "favicon": "https://example.com/favicon.ico",
  "authors": [
    {
      "name": "Meine Website",
      "url": "https://example.com"
    }
  ],
  "items": [
    {
      "id": "42",
      "title": "Titel des News-Eintrags",
      "content_html": "<p>HTML-Inhalt des Eintrags...</p>",
      "content_text": "Text-Inhalt ohne HTML-Tags...",
      "url": "https://example.com/news/titel-des-news-eintrags",
      "date_published": "2025-10-30T10:30:00+01:00",
      "date_modified": "2025-10-30T15:45:00+01:00",
      "authors": [
        {
          "name": "Max Mustermann"
        }
      ],
      "tags": ["Kategorie 1", "Kategorie 2"],
      "image": "https://example.com/media/teaser-bild.jpg"
    }
  ]
}
```

## Schöne URLs mit .htaccess

```apache
# JSON Feed auf /api/feed oder /feed.json umleiten
RewriteRule ^api/feed/?$ index.php?rex-api-call=neues_json [L]
RewriteRule ^feed\.json$ index.php?rex-api-call=neues_json [L]

# Mit Kategorie-Parameter
RewriteRule ^api/feed/([0-9]+)/?$ index.php?rex-api-call=neues_json&category_id=$1 [L]
```

## Beispiel-URLs

```
# Basis-Feed
https://example.com/api/feed

# Feed für Kategorie 1
https://example.com/?rex-api-call=neues_json&category_id=1

# Feed mit deutscher Sprache, 10 Einträge
https://example.com/?rex-api-call=neues_json&lang_id=1&limit=10

# Feed für Domain 2, Seite 3 (Einträge 21-30)
https://example.com/?rex-api-call=neues_json&domain_id=2&limit=10&offset=20
```

## Programmatische Verwendung

### JavaScript/Fetch API

```javascript
async function loadNews(categoryId = null, limit = 10) {
    let url = '/?rex-api-call=neues_json&limit=' + limit;
    if (categoryId) {
        url += '&category_id=' + categoryId;
    }
    
    const response = await fetch(url);
    const feed = await response.json();
    
    return feed.items;
}

// Verwendung
loadNews(3, 5).then(items => {
    items.forEach(item => {
        console.log(item.title, item.url);
    });
});
```

### PHP/cURL

```php
function getJsonFeed($category_id = null, $limit = 50) {
    $url = rex::getServer() . '/?rex-api-call=neues_json&limit=' . $limit;
    if ($category_id) {
        $url .= '&category_id=' . $category_id;
    }
    
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Verwendung
$feed = getJsonFeed(3, 10);
foreach ($feed['items'] as $item) {
    echo $item['title'] . ': ' . $item['url'] . PHP_EOL;
}
```

### React/Next.js Hook

```javascript
import { useState, useEffect } from 'react';

export function useNewsFeed(categoryId, limit = 10) {
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);
    
    useEffect(() => {
        let url = `/api/feed?limit=${limit}`;
        if (categoryId) url += `&category_id=${categoryId}`;
        
        fetch(url)
            .then(res => res.json())
            .then(feed => {
                setItems(feed.items);
                setLoading(false);
            });
    }, [categoryId, limit]);
    
    return { items, loading };
}
```

## Vorteile gegenüber RSS

- **Einfacher zu parsen**: Nativer JSON-Support in allen modernen Sprachen
- **Typisiert**: Klare Datenstrukturen ohne XML-Parsing
- **Erweiterbar**: Zusätzliche Felder wie `image`, `tags`, `authors` möglich
- **Paginierung**: Native Unterstützung für `limit` und `offset`
- **Performance**: Geringerer Overhead als XML-Parsing
- **API-freundlich**: Perfekt für REST-APIs und Single-Page-Applications

Die JSON Feed API ergänzt den bestehenden RSS-Feed perfekt für moderne Anwendungen, während RSS weiterhin für traditionelle Feed-Reader verfügbar bleibt.