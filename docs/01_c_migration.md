# Migration von REDAXO `FOR News Manager 3` und `alexplusde\Neues` v3 zu `FriendsOfREEAXO\Neues` v4

## Warum der Wechsel?

Das FOR-Addon News-Manager befindet sich nicht mehr in aktiver Entwicklung. Es wurde nur noch bis Ende 2022 gewartet. Potentielle Sicherheitslücken werden nicht mehr geschlossen.

Um die Lücke zu schließen, wird das Addon `Neues` von @alexplus_de zu FriendsOfREDAXO gespendet. Die Weiterentwicklung des Neues ist gesichert. Es wird ständig an die neuesten REDAXO-Versionen angepasst und erweitert.

Ein wesentlicher Vorteil gegenüber dem News Manager ist die Unterstützung von YForm. Damit lassen sich die News-Einträge und Kategorien komfortabel verwalten und erweitern, viele Funktionen von YForm und YOrm können genutzt werden.

Wir danken Alex für die Bereitschaft, das Addon in die Hände von FriendsOfREDAXO zu geben, Alex bleibt Projekt-Lead des Addons. Sowie @schorschy @skerbis und @eace für die Unterstützung bei der Entwicklung.

## Funktions-Parität und Unterschiede

Was | News Manager `3.0.3` | Neues `^4.0`
--- | --- | ---
Letzte Weiterentwicklung und Wartung | ❌ 28. Dez. 2022 | ✅ aktuell
REDAXO Core-Version | ab `^5.4` | ab `^5.15`
PHP-Version | ab `^5.6` | ab `^7.2`
Addon-Abhängigkeiten | URL ab `^2` | URL ab `^2`, YForm ab `^4`, YForm Field ab `^2`
Position im Backend | `Addons > News Manager` | `Aktuelles` (oben)
News-Übersicht | ✅ `News Manager > "News anlegen"` | ✅ `Aktuelles > Einträge`
Kategorien | ✅ `News Manager > "Kategorien"` | ✅ `Aktuelles > Kategorien`
Kommentare | ✅ als Plugin: `News Manager > "Kommentare"` | ❌ nein
Autoren | ❌ nein | `Aktuelles > Autoren`
Mehrsprachigkeit | ✅ `News Manager > (Sprache auswählen)` | ✅ `Aktuelles > Sprachen`
Dokumentation | ✅ als Plugin | ✅ `Aktuelles > Hilfe`
Einstellungen | ❌ nein | ✅ `Aktuelles > Einstellungen`
WYSIWYG-Editor | ✅ ausschließlich `redactor2` | ✅ frei wählbar (`cke5`, `redactor`, `markitup`, `tinymce`)
Backend-Sprachen | `de,en,es,se` | `de,en,es,se,da,el,fi,nl,no,pl,ro,tr,uk`
RSS | ✅ ja | 🚧 in Arbeit
Fertige Fragmente | ✅ ja | 🚧 in Arbeit
Multi-Domain-Unterstützung | ❌ über Umwege | ✅ ja
YOrm-Model | ❌ nein | ✅ ja (News-Einträge, Kategorien, Autoren, Sprachen)
CSV-Import | ❌ nein | ✅ ja (via YForm)
CSV-Export | ❌ nein | ✅ ja (via YForm)
RESTful API | ❌ nein | ✅ ja (via YForm)

## Migration von News Manager zu Neues 4

1. Backup der Datenbank und des Dateisystems
2. `Neues` installieren (`YForm`, `YForm Field`, `URL` müssen bereits installiert und aktiviert sein)
3. Bestehende News-Einträge und Kategorien in Neues importieren
4. Module, Templates und URL-Profile anpassen
5. `News Manager` deinstallieren.

## Migration von Neues 3 zu Neues 4

Der Wechsel von Neues 3 zu 4 sollte vergleichsweise einfach vonstatten gehen. Im Zuge des Versionswechsels wurden lange verschobene, jedoch notwendige kleine Änderungen vorgenommen, die zwingend beachtet werden müssen:

1. Vereinheitlichung von Methodennamen

2. Vereinheitlichung von Datenbankfeldern und Feldnamen
