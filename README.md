# News-Verwaltung für REDAXO 5 (Aktuelles, Pressemitteilungen, Pressestimmen, ...)

![friendsofredaxo neues](https://github.com/FriendsOfREDAXO/neues/assets/3855487/e6ead321-154e-4a1f-be29-53bbabadec06)


Mit diesem Addon können News-Beiträge anhand von YForm und YOrm im Backend verwaltet und im Frontend ausgegeben werden. Auf Wunsch auch multidomainfähig und mehrsprachig.

## Features

* Vollständig mit **YForm** umgesetzt: Alle Features und Anpassungsmöglichkeiten von YForm verfügbar
* Einfach: Die Ausgabe erfolgt über [`rex_sql`](https://redaxo.org/doku/master/datenbank-queries) oder objektorientiert über [YOrm](https://github.com/yakamara/redaxo_yform/blob/master/docs/04_yorm.md)
* Flexibel: **Zugriff** über die [YForm Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/05_rest.md)
* Sinnvoll: Nur ausgewählte **Rollen**/Redakteure haben Zugriff
* Bereit für Multidomain-Newsverwaltung mit YRewrite
* Bereit für **mehrsprachige** Websites: Reiter für Sprachen auf Wunsch anzeigen oder ausblenden
* Bereit für viel mehr: Kompatibel zum [URL2-Addon](https://github.com/tbaddade/redaxo_url)

> **Hinweis:** Neues benötigt ab Version 3 [`yform_field`](https://github.com/alexplusde/yform_field/) für die Auswahl von (YRewrite-)Domains und Dem Auswahldatum für Veröffentlichungen

> **Tipp:** Neues arbeitet hervorragend zusammen mit den Addons [`yform_usability`](https://github.com/FriendsOfREDAXO/yform_usability/) und [`yform_geo_osm`](https://github.com/FriendsOfREDAXO/yform_geo_osm)

## Installation

Im REDAXO-Installer das Addon `neues` herunterladen und installieren. Anschließend erscheint im Hauptmenü ein neuer Menüpunkt `Aktuelles`.

![image](https://user-images.githubusercontent.com/3855487/209792457-b6f824dc-7fd8-4295-a7c7-2eab046d19c7.png)

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/neues/blob/master/LICENSE)  

## Autor

**Friends of REDAXO**  

## Credits

**Projekt-Lead**  
[Alexander Walther](https://github.com/alexplusde)

Neues basiert auf: [YForm](https://github.com/yakamara/redaxo_yform), spezieller Dank zu YOrm an [Gregor Harlan](https://github.com/gharlan)
