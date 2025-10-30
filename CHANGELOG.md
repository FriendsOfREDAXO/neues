# Changelog

## 30-10-2025 7.0.0 (**Breaking Changes ⚠**)

### YForm-Feldkonflikt mit yform_field Addon aufgelöst

**Breaking Changes:**
- YForm-Feldnamen wurden umbenannt um Konflikte mit dem `yform_field` Addon zu vermeiden:
  - `choice_status` → `neues_choice_status`
  - `datetime_local` → `neues_datetime_local`
  - `domain` → `neues_domain`

**Migration:**
- Beim Update auf Version 7.0.0 werden bestehende YForm-Tabellendefinitionen automatisch migriert
- Bestehende Daten bleiben unverändert erhalten
- Das neues-Addon kann nun parallel mit yform_field verwendet werden

**Weitere Änderungen:**
- Menübezeichnung von "Aktuelles" zu "Neues" geändert für bessere Konsistenz
- Addon-Vergleiche aus der Dokumentation entfernt
- Package-Konfiguration bereinigt (conflicts-Eintrag entfernt)

## xx-xx-2024 x.x.x (**Breaking Changes ⚠**)

> Work in Progress !!!

Version x.x.x ist an vielen Ständen überarbeitet, was auch die Schnittstellen der
Klassen/Methoden betrifft. Beim Update von Versionen vor x.x.x müssen ggf. Anpassungen
im eigenen Code vorgenommen werden:

- Klasse `Author`:
  - alter Klassenname: `neues_author` => neu: `FriendsOfRedaxo\Neues\Author`
  - `$author->getName`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
  - `$author->getNickName`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
  - `$author->geText`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
  - `$author->getBeUserId`: liefert immer eine Id vom Typ`int` (0=unbekannt); `null` als Rückgabe ist entfernt
- Klasse `Category`:
  - alter Klassenname: `neues_category` => neu: `FriendsOfRedaxo\Neues\Category`
  - `$cat->getName`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
- Klasse `Entry`:
  - alter Klassenname: `neues_entry` => neu: `FriendsOfRedaxo\Neues\Entry`
  - `$post->getImages`: API angepasst auf `array`, da ohnehin stets ein ggf. leeres Array geliefert wurde, aber nie `null`
  - `$post->setImages`: `null` für "leer also löschen" wird nicht mehr akzeptiert; statt dessen `[]`benutzen oder den Parameter weglassen.
  - `$post->getExternalUrl`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt.
  - `$post->getStatus`: liefert nun richtigerweise `int` statt `string`
  - `Entry::findOnline`: `null` als "suche alle"-Kennung durch `0`ersetzt.
  - `Entry::findByCategory`: `null` als "suche alle"-Kennung durch `0`ersetzt.
  - `Entry::findByCategoryIds`: `null` als "suche alle"-Kennung durch `0`ersetzt.
  - neu: `$post->getCanonicalUrl`
  - neu: `$post->setCanonicalUrl`
  - neu: `Entry::queryBy`
  - neu: `Entry::findBy`
- Klasse `EntryLang`
  - alter Klassenname: `neues_entry_lang` => neu: `FriendsOfRedaxo\Neues\EntryLang`
  - `$cat->getCode`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
  - `$cat->geName`: liefert immer einen ggf. leeren `string`; `null` als Rückgabe ist entfernt
- Klasse `Entry`:
  - alter Klassenname: `neues` => neu: `FriendsOfRedaxo\Neues\Neues`
