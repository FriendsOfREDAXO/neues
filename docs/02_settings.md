# Einstellungen

## Editor für Textfeld "Inhalt" einbinden

* Installiere den Editor deiner Wahl (zum Beispiel *redactor*).
* lege im Editor dein Wunschprofil an.
* nach dem Speichern erscheint die Profilübersicht in *Redactor*.
* hier den Wert kopieren, der in der Spalte `Selektor` steht.
* im Hauptmenü unter `Neues` > `Einstellungen` bei `Editor` den kopierten Eintrag einfügen - achte darauf, dass der Name ohne den Punkt davor eingefügt wird.

**Beispiel**  
Im Redactor wurde das Profil mit dem Namen *Mein-Redactor-Profil* angelegt. Dann steht bei `Selector`: `.redactor-editor--Mein-Redactor-Profil`  
In den Addon-Einstellungen von diesem Addon hier, muss dann bei Editor der Eintrag so aussehen: `class="form-control redactor-editor--Mein-Redactor-Profil"`

Für den Editor CK5 ist es: `class="form-control cke5-editor" data-profile="default" data-lang="de"`
