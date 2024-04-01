# Migration von REDAXO `FOR News Manager 3`, `Blogger 1.3.2` und `alexplusde\Neues` 3.x zu `FriendsOfREEAXO\Neues` 4.1

## Warum der Wechsel?

Das FOR-Addon News-Manager und das FOR-Addon Blogger befinden sich nicht mehr in aktiver Entwicklung. Sie wurden nur noch bis Ende 2022 bzw. Anfang 2024 gewartet. Potentielle Sicherheitslücken werden nicht mehr geschlossen.

Um die Lücke zu schließen, wird das Addon `Neues` von @alexplus_de zu FriendsOfREDAXO gespendet. Die Weiterentwicklung des Neues ist gesichert. Es wird ständig an die neuesten REDAXO-Versionen angepasst und erweitert.

Ein wesentlicher Vorteil gegenüber News Manager oder Blogger ist die Unterstützung von YForm. Damit lassen sich die News-Einträge und Kategorien komfortabel verwalten und erweitern, viele Funktionen von YForm und YOrm können genutzt werden.

Wir danken Alex für die Bereitschaft, das Addon in die Hände von FriendsOfREDAXO zu geben, Alex bleibt Projekt-Lead des Addons. Sowie @schorschy @skerbis und @eace für die Unterstützung bei der Entwicklung.

## Funktions-Parität und Unterschiede

| Was                                  | News Manager `3.0.3`                        | Blogger `1.3.2` | Neues `^4.1`                                               |
| ------------------------------------ | ------------------------------------------- | --------------- | ---------------------------------------------------------- |
| Letzte Weiterentwicklung und Wartung | ❌ 28. Dez. 2022                             | ❌ 31. März 2024 | ✅ aktuell                                                  |
| REDAXO Core-Version                  | ab `^5.4`                                   | ❌ n/a             | ab `^5.15`                                                 |
| PHP-Version                          | ab `^5.6`                                   | ❌ n/a             | ab `^7.2`                                                  |
| Addon-Abhängigkeiten                 | URL ab `^2`                                 | Keine           | URL ab `^2`, YForm ab `^4`, YForm Field ab `^2`            |
| Position im Backend                  | `Addons > News Manager`                     | `Addons > Blogger` | `Aktuelles` (oben)                                         |
| News-Übersicht                       | ✅ `News Manager > "News anlegen"`           | `Blogger > Einträge` | ✅ `Aktuelles > Einträge`                                   |
| Kategorien                           | ✅ `News Manager > "Kategorien"`             | `Blogger > Kategorien` | ✅ `Aktuelles > Kategorien`                                 |
| Kommentare                           | ✅ als Plugin: `News Manager > "Kommentare"` | ❌ nein         | ❌ nein                                                     |
| Autoren                              | ❌ nein                                      | ❌ nein         | ✅ `Aktuelles > Autoren`                                      |
| Mehrsprachigkeit                     | ✅ `News Manager > (Sprache auswählen)`      | ❌ nein         | ✅ `Aktuelles > Sprachen`                                   |
| Dokumentation                        | ✅ als Plugin                                | ❌ nein         | ✅ `Aktuelles > Hilfe`                                      |
| Einstellungen                        | ❌ nein                                      | `Blogger > Einstellungen` | ✅ `Aktuelles > Einstellungen`                              |
| WYSIWYG-Editor                       | ✅ ausschließlich `redactor2`                | ✅ frei wählbar | ✅ frei wählbar (`cke5`, `redactor`, `markitup`, `tinymce`) |
| Backend-Sprachen                     | ✅ ja `de,en,es,se`                          | ✅ ja `de, en`  | ✅ ja `de,en,es,fr,it,se`                                    |
| RSS                                  | ✅ ja                                        | ❌ nein         | ✅ ja                                                       |
| Fertige Fragmente                    | ✅ ja                                        | ✅ ja           | ✅ ja                                                       |
| Multi-Domain-Unterstützung           | ❌ über Umwege                               | ❌ n/a             | ✅ ja                                                       |
| Frei erweiterbare Felder             | ❌ nein                                      | ❌ nein         | ✅ ja (via YForm)                                           |
| YOrm-Model                           | ❌ nein                                      | ❌ nein         | ✅ ja (News-Einträge, Kategorien, Autoren, Sprachen)        |
| CSV-Import                           | ❌ nein                                      | ❌ nein         | ✅ ja (via YForm)                                           |
| CSV-Export                           | ❌ nein                                      | ❌ nein         | ✅ ja (via YForm)                                           |
| RESTful API                          | ❌ nein                                      | ❌ nein         | ✅ ja (via YForm)                                           |

## Migration von News Manager zu Neues

### Automatische Daten-Migration von News Manager zu Neues 4

Es gibt eine automatische Migration von News Manager-Einträgen zu Neues 4.

Diese liegt der finalen Version des News Managers bei. Alternativ müssen folgenden Schritte erfolgen.

### Manuelle Daten-Migration von News Manager zu Neues 4

1. Backup der Datenbank und des Dateisystems
2. `Neues` installieren (`YForm`, `YForm Field`, `URL` müssen bereits installiert und aktiviert sein)
3. Bestehende News-Einträge und Kategorien in Neues importieren
4. Module, Templates und URL-Profile anpassen
5. `News Manager` deinstallieren.

### SQL-Befehle zur Migration der Daten

> Hinweis: Die Autoren müssen manuell oder mit eigenen Anpassungen übertragen werden, da es hierfür eine eigene Tabelle gibt.

```SQL
INSERT INTO rex_neues_category
    (id, name, image, status, createuser, createdate, updateuser, updatedate)
SELECT 
    pid,
    name,
    '',
    '1', 
    createuser,
    createdate,
    updateuser,
    updatedate
FROM rex_newsmanager_categories;

INSERT INTO rex_neues_entry
    (id, status, name, teaser, description, domain_ids, lang_id, publishdate, author_id, url, image, images, createdate, createuser, updatedate, updateuser)
SELECT 
    pid,
    IF(status=1, '1', '0'),
    title,
    subtitle,
    richtext,
    '',
    clang_id,
    createdate,
    0,
    seo_canonical,
    '',
    images,
    createdate,
    createuser,
    updatedate,
    updateuser
FROM rex_newsmanager;

INSERT INTO rex_neues_entry_category_rel (entry_id, category_id)
SELECT rex_newsmanager.pid , rex_newsmanager_categories.id
FROM rex_newsmanager
INNER JOIN rex_newsmanager_categories
ON FIND_IN_SET(rex_newsmanager_categories.id, REPLACE(REPLACE(rex_newsmanager.newsmanager_category_id, '|', ','), ' ', '')) > 0;
```

## Migration von Blogger zu Neues

### Automatische Daten-Migration von Blogger zu Neues 4.1

Es wird eine automatische Migration von Blogger-Einträgen zu Neues 4.1.

Diese liegt der finalen Version des News Managers bei. Alternativ müssen folgenden Schritte erfolgen.

### Manuelle Daten-Migration von Blogger zu Neues 4

1. Backup der Datenbank und des Dateisystems
2. `Neues` installieren (`YForm`, `YForm Field`, `URL` müssen bereits installiert und aktiviert sein)
3. Bestehende News-Einträge und Kategorien in Neues importieren
4. Module, Templates und URL-Profile anpassen
5. `News Manager` deinstallieren.

### SQL-Befehle zur Migration der Daten von Blogger zu Neues 4

> Hinweis: Die Tags müssen manuell oder mit eigenen Anpassungen übertragen werden, da es hierfür eine eigene Tabelle gibt.

folgt...

```SQL
```

## Migration von Neues 3 zu Neues 4

Der Wechsel von Neues 3 zu 4 sollte vergleichsweise einfach vonstatten gehen. Im Zuge des Versionswechsels wurden lange verschobene, jedoch notwendige kleine Änderungen vorgenommen, die zwingend beachtet werden müssen:

1. Vereinheitlichung von Methodennamen

2. Vereinheitlichung von Datenbankfeldern und Feldnamen
