# Migration von REDAXO `FOR News Manager 3`, `Blogger 1.3.2` und `alexplusde\Neues` 3.x zu `FriendsOfREEAXO\Neues` 4.1

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
