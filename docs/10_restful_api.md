# RESTful API

Die [Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/05_rest.md) ist über das REST-Plugin von YForm umgesetzt.

## Einrichtung

Zunächst das REST-Plugin von YForm installieren und einen Token einrichten. Den Token auf die jeweiligen Endpunkte legen:

```text
/neues/category
/neues/entry
/neues/author
```

## Endpunkt `entry`

**Auslesen:** GET `example.org/rest/neues/entry/5.0.0/?token=###TOKEN###`

**Auslesen einzelner Termin**  GET `example.org/rest/neues/entry/5.0.0/3?token=###TOKEN###` Eintrag der `id=3`

## Endpunkt `author`

**Auslesen:** GET `example.org/rest/neues/author/5.0.0/?token=###TOKEN###`

**Auslesen einzelner Termin**  GET `example.org/rest/neues/author/5.0.0/3?token=###TOKEN###` Eintrag der `id=3`

## Endpunkt `category`

**Auslesen:** GET `example.org/rest/neues/category/5.0.0/?token=###TOKEN###`

**Auslesen einzelne Kategorie**  GET `example.org/rest/neues/category/5.0.0/7?token=###TOKEN###` Eintrag der `id=7`

## Cronjob

Seit Version 5.0 gibt es einen Cronjob, der Daten einer anderen REDAXO-Installation abruft und in die Datenbank schreibt. Dazu muss der Cronjob in der anderen REDAXO-Installation eingerichtet werden. Der Cronjob ruft die Daten der Endpunkte `entry`, `author` und `category` ab.
