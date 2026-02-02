# News-Verwaltung für REDAXO 5 (News, Pressemitteilungen, Pressestimmen, ...)

Mit diesem Addon können News-Beiträge anhand von YForm und YOrm im Backend verwaltet und im Frontend ausgegeben werden. Auf Wunsch auch multidomainfähig und mehrsprachig.

## Features

* Vollständig mit **YForm** umgesetzt: Alle Features und Anpassungsmöglichkeiten von YForm verfügbar
* Einfach: Die Ausgabe erfolgt über [`rex_sql`](https://redaxo.org/doku/master/datenbank-queries) oder objektorientiert über [YOrm](https://github.com/yakamara/redaxo_yform/blob/master/docs/04_yorm.md)
* Flexibel: **Zugriff** über die [YForm Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/05_rest.md)
* **RSS-Feed & JSON API** integriert: Vollautomatischer RSS 2.0-Feed und moderne JSON-API mit Kategorie-, Sprach- und Domain-Filterung
* Sinnvoll: Nur ausgewählte **Rollen**/Redakteure haben Zugriff
* Bereit für Multidomain-Newsverwaltung mit YRewrite
* Bereit für **mehrsprachige** Websites: Reiter für Sprachen auf Wunsch anzeigen oder ausblenden
* Bereit für viel mehr: URL-Profile werden unterstützt



> **Steuere eigene Verbesserungen** dem [GitHub-Repository von neues](https://github.com/FriendsOfREDAXO/neues) bei. Oder **unterstütze dieses Addon:** Mit einer [Beauftragung unterstützt du die Weiterentwicklung dieses AddOns](https://github.com/sponsors/alexplusde)

## Installation

Im REDAXO-Installer das Addon `neues` herunterladen und installieren. Anschließend erscheint im Hauptmenü ein neuer Menüpunkt `Neues`.

![image](https://user-images.githubusercontent.com/3855487/209792457-b6f824dc-7fd8-4295-a7c7-2eab046d19c7.png)

## RSS-Feed

Das Addon bietet einen integrierten RSS 2.0-Feed für News-Einträge:

**Basis-Feed:** `index.php?rex-api-call=neues_rss`

**Mit Filtern:**
- Nach Kategorie: `index.php?rex-api-call=neues_rss&category_id=3`
- Nach Sprache: `index.php?rex-api-call=neues_rss&lang_id=2`
- Nach Domain: `index.php?rex-api-call=neues_rss&domain_id=1`
- Kombiniert: `index.php?rex-api-call=neues_rss&category_id=3&lang_id=2&domain_id=1`

## JSON Feed API

Moderner JSON Feed nach [jsonfeed.org](https://jsonfeed.org) Standard:

**Basis-API:** `index.php?rex-api-call=neues_json`

**Mit Filtern:**
- Nach Kategorie: `index.php?rex-api-call=neues_json&category_id=3`
- Nach Sprache: `index.php?rex-api-call=neues_json&lang_id=2`
- Nach Domain: `index.php?rex-api-call=neues_json&domain_id=1`
- Mit Paginierung: `index.php?rex-api-call=neues_json&limit=10&offset=20`

**Schöne URLs** können über `.htaccess` eingerichtet werden:
```apache
# RSS Feed
RewriteRule ^feed/?$ index.php?rex-api-call=neues_rss [L]

# JSON Feed
RewriteRule ^api/feed/?$ index.php?rex-api-call=neues_json [L]
RewriteRule ^feed\.json$ index.php?rex-api-call=neues_json [L]
```

Detaillierte Dokumentation siehe [docs/07_rss.md](docs/07_rss.md).

## Lizenz

MIT Lizenz, siehe [LICENSE](https://github.com/FriendsOfREDAXO/neues/blob/main/LICENSE)  

## Autor

**Friends of REDAXO**

## Credits

Danke an: 
**Alexander Walther**  
<http://www.alexplus.de>

**Paul Götz**  
<https://github.com/schorschy>

Neues basiert auf: [YForm](https://github.com/yakamara/redaxo_yform), spezieller Dank zu YOrm an [Gregor Harlan](https://github.com/gharlan)
