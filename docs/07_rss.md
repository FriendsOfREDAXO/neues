# RSS-Feed

Das `Neues`-Addon bietet einen vollständig funktionsfähigen RSS-Feed für News-Einträge über die API-Klasse `FriendsOfRedaxo\Neues\Api\Rss`. Der Feed wird über die URL `index.php?rex-api-call=neues_rss` aufgerufen und liefert einen RSS 2.0-kompatiblen XML-Feed.

## Grundlegende Verwendung

**Standard RSS-Feed (alle Online-Einträge):**
```
index.php?rex-api-call=neues_rss
```

## Filteroptionen

Der RSS-Feed unterstützt verschiedene URL-Parameter zum Filtern der Einträge:

### Nach Kategorie filtern

**RSS-Feed für eine bestimmte Kategorie:**
```
index.php?rex-api-call=neues_rss&category_id=3
```

Zeigt nur Einträge aus der Kategorie mit ID 3. Der Feed-Titel und Dateiname werden automatisch an die Kategorie angepasst.

### Nach Sprache filtern

**RSS-Feed mit Spracheinstellung:**
```
index.php?rex-api-call=neues_rss&lang_id=2
```

Setzt die Sprache des RSS-Feeds auf die Sprache mit ID 2. Derzeit beeinflusst dies die `<language>`-Angabe im RSS-Channel.

### Nach Domain filtern

**RSS-Feed mit Domain-Parameter:**
```
index.php?rex-api-call=neues_rss&domain_id=1
```

Zeigt nur Einträge an, die entweder keine Domain-Beschränkung haben oder explizit der angegebenen Domain zugeordnet sind. Ideal für Multi-Domain-Setups.

### Kombinierte Filter

**RSS-Feed mit mehreren Filtern:**
```
index.php?rex-api-call=neues_rss&category_id=3&lang_id=2&domain_id=1
```

## Schöne URLs mit .htaccess

Für benutzerfreundliche URLs wie `/feed` oder `/rss` kann eine `.htaccess`-Regel verwendet werden:

```apache
# RSS-Feed auf /feed umleiten
RewriteRule ^feed/?$ index.php?rex-api-call=neues_rss [L]

# RSS-Feed mit Kategorie auf /feed/kategorie-name umleiten  
RewriteRule ^feed/([^/]+)/?$ index.php?rex-api-call=neues_rss&category_id=$1 [L]

# Alternativer RSS-Endpunkt
RewriteRule ^rss/?$ index.php?rex-api-call=neues_rss [L]
RewriteRule ^rss\.xml$ index.php?rex-api-call=neues_rss [L]
```

## Beispiel-URLs

```
# Basis-Feed
https://example.com/feed

# Feed für Kategorie 1  
https://example.com/index.php?rex-api-call=neues_rss&category_id=1

# Feed mit deutscher Sprache
https://example.com/index.php?rex-api-call=neues_rss&lang_id=1

# Feed für Kategorie 3 mit englischer Sprache
https://example.com/index.php?rex-api-call=neues_rss&category_id=3&lang_id=2
```

## RSS-Feed-Format

Der generierte RSS-Feed enthält:

- **Channel-Informationen**: Titel (Servername), Beschreibung, Link zur Website
- **Spracheinstellung**: Automatisch aus `lang_id` wenn angegeben
- **Einträge**: Titel, Beschreibung (ohne HTML-Tags), Link, Publikationsdatum, GUID

## Programmatische Verwendung

```php
use FriendsOfRedaxo\Neues\Entry;
use FriendsOfRedaxo\Neues\Api\Rss;

// Alle Online-Einträge
$entries = Entry::findOnline();
$rss = Rss::createRssFeed($entries, 0, 1, 'Mein RSS-Feed');

// Einträge einer bestimmten Kategorie
$entries = Entry::findOnline(3);
$rss = Rss::createRssFeed($entries, 0, 1, 'News aus Kategorie 3');
```

Die Parameter `domain_id`, `lang_id` und `category_id` sind alle optional. Ohne Parameter werden alle Online-Einträge in den Feed einbezogen.
