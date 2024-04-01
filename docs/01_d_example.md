# Beispiel und Fragmente

Wurde Neues mit dem URL-Addon installiert und entsprechend der [Anleitung](/redaxo/index.php?page=neues/docs&mdfile=06_url) konfiguriert, kannst du dir eine Beispielvorlage für die Ausgabe im Template ausgeben lassen.

Die Ausgabe erfolgt über Fragment-Dateien. Diese findest du im Ordner `fragments/neues`. Die Fragmente können nach Belieben angepasst werden. Weitere Informationen zu Fragmenten findest du in der [Redaxo-Dokumentation](https://redaxo.org/doku/main/fragmente).

Im Template, in dem du die Ausgabe von Neues realisieren möchtest, fügst du folgenden Code ein:

```php
<?php
    // Voraussetzung: URL-Addon ist installiert und konfiguriert
    $manager = Url\Url::resolveCurrent();
    
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


## Methoden

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