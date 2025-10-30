# URL-Profile

## Schnelle Einrichtung von Profilen

Die Konfiguration der URL-Profile ist abhängig vom gewünschten Projekt und Umfang. Deshalb werden keine URL-Profile bei der Installation eingerichtet.

Nachfolgend Beispiel-Konfigurationen für verschiedene Szenarien:

## News-Einträge mit einer Domain

Mit der Methode `getUrl()` kann die URL des aktuellen News-Eintrags geholt werden. Dazu muss ein URL-Profil mit dem Schlüssel `neues-entry-id` angelegt sein.

Beispiel:

![sunstrom de_redaxo_index php_page=url_generator_profiles func=edit id=1 list=5fa6c979(Surface Pro 7)](https://user-images.githubusercontent.com/3855487/209814035-f194a7d6-ae9a-463c-979a-7d8b542d239c.png)

### News-Einträge im Kontext von YRewrite und Multidomains / mehreren Sprachen

Die Methode `getUrl()` erlaubt optional als Parameter anstelle von `neues-entry-id` ein eigenes Profil zu übergeben, z.B. `"neues-entry-id-domain-x"`. Somit lassen sich weitere Profile mit Filter je Domain erstellen und diese mit einem eigenen definitierten Schlüssel übergeben.
