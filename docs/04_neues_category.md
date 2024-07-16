# Die Klasse `neues_category`

Kind-Klasse von `rex_yform_manager_dataset`, damit stehen alle Methoden von YOrm Datasets zur Verfügung. Greift auf die Tabelle `rex_neues_category` zu.

> Es werden nachfolgend zur die durch dieses Addon ergänzte Methoden beschrieben. Lerne mehr über YOrm und den Methoden für Querys, Datasets und Collections in der [YOrm Doku](https://github.com/yakamara/yform/blob/master/docs/04_yorm.md)

## Beispiel-Ausgabe einer Kategorie

```php
$category = FriendsOfRedaxo\Neues\Category::get(3); // News-Kategorie mit der id=3
// dump($category);

echo $category->getName();

$entries =  $category->getEntries();

foreach($entries as $entry) {
    echo $entry->getName();
// ...
}
```
