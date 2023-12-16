# RESTful API

Die [Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/05_rest.md) ist über das REST-Plugin von YForm umgesetzt.

## Einrichtung

Zunächst das REST-Plugin von YForm installieren und einen Token einrichten. Den Token auf die jeweiligen Endpunkte legen:

```text
/neues/3/entry
/neues/3/category
```

## Endpunkt `entry`

**Auslesen:** GET `example.org/rest/neues/3/date/?token=###TOKEN###`

**Auslesen einzelner Termin**  GET `example.org/rest/neues/3//entry/7/?token=###TOKEN###` Eintrag der `id=7`

## Endpunkt `category`

**Auslesen:** GET `example.org/rest/neues/3/category/?token=###TOKEN###`

**Auslesen einzelne Kategorie**  GET `example.org/rest/neues/3/category/7/?token=###TOKEN###` Eintrag der `id=7`
