# Beispiel und Fragmente

Wurde Neues mit dem URL-Addon installiert und entsprechend der [Anleitung](/redaxo/index.php?page=neues/docs&mdfile=06_url) konfiguriert, kannst du dir eine Beispielvorlage für die Ausgabe im Template ausgeben lassen.

## Anpassung der Ausgabe

Die Ausgabe erfolgt über Fragment-Dateien. Diese findest du im Ordner `fragments/neues`. Die Fragmente können nach Belieben angepasst werden. 
Daher musst du im Modul oder Template (siehe Snippet unten) nichts ändern. Die Änderungen erfolgen in den Fragment-Dateien.

Um sicherzustellen, dass deine Anpassungen updatesicher sind, kopiere die entsprechenden Fragmente in den folgenden Ordner und nimm dort die Änderungen vor:
- bei Verwendung des theme-Addons: `theme/private/fragments/neues`
- bei Verwendung des project-Addons: `redaxo/addons/project/fragments/neues`

Wenn diese Verzeichnisse angelegt sind und dort ebenfalls folgende Dateien enthalten sind, werden **diese** aus dem theme/project-Verzeichnis verwendet und nicht die Dateien aus `/src/addons/fragments/neues`:

```
entry.php
list-entry.php
list.php
```
Dies sind die Dateien, mit denen das HTML für die Ausgabe gesteuert wird. 

Weitere Informationen zu Fragmenten findest du in der [Redaxo-Dokumentation](https://redaxo.org/doku/main/fragmente).

## Template / Modul Code

Im Template, in dem du die Ausgabe von Neues realisieren möchtest, fügst du folgenden Code ein:

```php
<?php
    // Voraussetzung: URL-Addon ist installiert und konfiguriert
    $manager = Url\Url::resolveCurrent();
    use FriendsOfRedaxo\Neues\Neues;

    if($manager) {
        // Ausgabe eines einzelnen Datensatzes
        $postId = $manager->getDatasetId();
        echo neues::getEntry($postId);
    } else {
        // Ausgabe einer Liste
        echo neues::getList();
    }
?>
```

Derselbe Code funktioniert auch in einem Modul.

## Methodenreferenz

### getEntry(int $postId)

Gibt einen einzelnen Datensatz aus. Benötigt wird die ID des Datensatzes.

```php
$entry = neues::getEntry(1);
```

### getList(int $rowsPerPage = 10, string $pageCursor = 'page')

Gibt eine Liste aller Datensätze als HTML aus.

- rowsPerPage: Anzahl der Datensätze pro Seite. Standard: `10`
- pageCursor: Name des GET-Parameters, der die aktuelle Seite enthält. Standard: `page`


```php
$list = neues::getList();
```
