# News-Verwaltung für REDAXO 5 (Aktuelles, Pressemitteilungen, Pressesteimmen, ...)

![web_banner_redaxo_addon_neues](https://user-images.githubusercontent.com/3855487/204768778-99b56b18-3997-4466-9ae2-537fd792c521.png)

Mit diesem Addon können News anhand von YForm und YOrm im Backend verwaltet und im Frontend ausgegeben werden. Auf Wunsch auch mehrsprachig.

## Features

* Vollständig mit **YForm** umgesetzt: Alle Features und Anpassungsmöglichkeiten von YForm verfügbar
* Einfach: Die Ausgabe erfolgt über [`rex_sql`](https://redaxo.org/doku/master/datenbank-queries) oder objektorientiert über [YOrm](https://github.com/yakamara/redaxo_yform_docs/blob/master/de_de/yorm.md)
* Flexibel: **Zugriff** über die [YForm Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/plugins.md#restful-api-einf%C3%BChrung)
* Sinnvoll: Nur ausgewählte **Rollen**/Redakteure haben Zugriff
* Bereit für **mehrsprachige** Websites: Reiter für Sprachen auf Wunsch anzeigen oder ausblenden
* Bereit für mehr: RSS-Feed in Planung
* Bereit für viel mehr: Kompatibel zum [URL2-Addon](https://github.com/tbaddade/redaxo_url)

> **Tipp:** Events arbeitet hervorragend zusammen mit den Addons [`yform_usability`](https://github.com/FriendsOfREDAXO/yform_usability/) und [`yform_geo_osm`](https://github.com/FriendsOfREDAXO/yform_geo_osm)

> **Steuere eigene Verbesserungen** dem [GitHub-Repository von neues](https://github.com/alexplusde/neues) bei. Oder **unterstütze dieses Addon:** Mit einer [Spende oder Beauftragung unterstützt du die Weiterentwicklung dieses AddOns](https://github.com/sponsors/alexplusde)

## Installation

Im REDAXO-Installer das Addon `neues` herunterladen und installieren. Anschließend erscheint ein neuer Menüpunkt `Veranstaltungen` sichtbar.

## Nutzung im Frontend

### Die Klasse `neues_entry`

Typ `rex_yform_manager_dataset`. Greift auf die Tabelle `rex_neues_entry` zu.

#### Beispiel-Ausgabe einer News

```php
dump(neues_entry::get(3)); // News mit der id=3
```

```php
dump(neues_entry::get(3)->getCategory()); // Kategorie, wenn nur ein Element verwendet wird
dump(neues_entry::get(3)->getCategories()); // Kategorie, wenn nur ein Element verwendet wird
```

### Die Klasse `neues_category`

Typ `rex_yform_manager_dataset`. Greift auf die Tabelle `rex_neues_category` zu.

#### Beispiel-Ausgabe einer Kategorie

```php
dump(neues_category::get(3)); // News-Kategorie mit der id=3
```

## Nutzung im Backend: Die Terminverwaltung

### Die Tabelle "SPRACHEN"

Die Tabelle "Sprachen" ist eine Tabelle, in der zunächst Sprachen verwaltet werden können und im Anschluss die eigentliche News-Tabelle gefiltert nach dieser Sprache angezeigt wird.

Wer keine mehrsprachigen Termine benötigt, kann diesen Menüpunkt problemlos für Redakteure über die Benutzer-Rollen ausblenden. Wichtig ist jedoch, dass mind. eine Sprache angelegt wurde.

### Die Tabelle "NEUES"

In der Termin-Tabelle werden einzelne Daten festgehalten. Nach der Installation von `neues` stehen folgende Felder zur Verfügung:

| Typ      | Typname             | Name                | Bezeichnung       |
|----------|---------------------|---------------------|-------------------|
| value    | text                | name                | Name              |
| validate | empty               | name                |                   |
| value    | textarea            | description         | Beschreibung      |
| value    | be_manager_relation | category_ids        | Kategorien        |
| value    | be_media            | images              | Bilde             |
| value    | select              | status              | Status            |

### Die Tabelle "KATEGORIEN"

Die Tabelle Kategorien kann frei verändert werden, um Termine zu gruppieren (bspw. Veranstaltungsreihen) oder zu Verschlagworten (als Tags).

| Typ      | Typname             | Name    | Bezeichnung |
|----------|---------------------|---------|-------------|
| value    | text                | name    | Titel       |
| validate | unique              | name    |             |
| validate | empty               | name    |             |

## RESTful API (dev)

Die [Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/plugins.md#restful-api-einf%C3%BChrung) ist über das REST-Plugin von YForm umgesetzt.

### Einrichtung

Zunächst das REST-Plugin von YForm installieren und einen Token einrichten. Den Token auf die jeweiligen Endpunkte legen:

```php
    /v2.0.0/neues/date
    /v2.0.0/neues/category
```

### Endpunkt `entry`

**Auslesen:** GET `example.org/rest/v2.0.0/neues/date/?token=###TOKEN###`

**Auslesen einzelner Termin**  GET `example.org/rest/v2.0.0/neues/entry/7/?token=###TOKEN###` Eintrag der `id=7`

### Endpunkt `category`

**Auslesen:** GET `example.org/rest/v2.0.0/neues/category/?token=###TOKEN###`

**Auslesen einzelne Kategorie**  GET `example.org/rest/v2.0.0/neues/category/7/?token=###TOKEN###` Eintrag der `id=7`

### Endpunkt `location`

**Auslesen:** GET `example.org/rest/v2.0.0/neues/location/?token=###TOKEN###`

**Auslesen einzelner Standort**  GET `example.org/rest/v2.0.0/neues/location/7/?token=###TOKEN###` Eintrag  der `id=7`

## Import

### Import via CSV

Neues basiert auf YForm. Importiere deine Einträge bequem per CSV, wie du es von YForm kennst.

## Export

### Export via CSV

Neues basiert auf YForm. Exportiere deine Einträge bequem per CSV, wie du es von YForm kennst.

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/neues/blob/master/LICENSE.md)  

## Autor

**Alexander Walther**  
<http://www.alexplus.de>
<https://github.com/alexplusde>

**Projekt-Lead**  
[Alexander Walther](https://github.com/alexplusde)

## Credits

neues basiert auf: [YForm](https://github.com/yakamara/redaxo_yform)  
Danke an [Gregor Harlan](https://github.com/gharlan) für die Unterstützung
