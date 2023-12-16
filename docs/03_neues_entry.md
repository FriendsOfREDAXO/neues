# Die Klasse `neues_entry`

Kind-Klasse von `rex_yform_manager_dataset`, damit stehen alle Methoden von YOrm Datasets zur Verfügung. Greift auf die Tabelle `rex_neues_entry` zu.

> Es werden nachfolgend zur die durch dieses Addon ergänzte Methoden beschrieben. Lerne mehr über YOrm und den Methoden für Querys, Datasets und Collections in der [YOrm Doku](https://github.com/yakamara/yform/blob/master/docs/04_yorm.md)

## Alle Einträge erhalten

```php
$entries = neues_entry::query()->find(); // YOrm-Standard-Methode zum Finden von Einträgen, lässt sich mit where(), Limit(), etc. einschränken und Filtern.
$entries = neues_entry::findOnline(); // Alle Online-Einträge
$entries = neues_entry::findByCategory($category_id [, $status]) // Alle Einträge einer Kategorie
```

## Beispiel-Ausgabe einer News

```php
$entry = neues_entry::get(3); // News mit der id=3
// dump($entry);

echo $entry->getName();
echo $entry->getAuthor();
echo $entry->getDomain();
echo $entry->getTeaser();
echo $entry->getImage();
echo $entry->getMedia();
echo $entry->getDescriptionAsPlaintext();
echo $entry->getDescription();
echo $entry->getExternalUrl();
echo $entry->getExternalLabel();
echo $entry->getPublishDate();
echo $entry->getPublishDateTime();
echo $entry->getFormattedPublishDate($format); // IntlDateFormatter::FULL
echo $entry->getFormattedPublishDateTime($format); // [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]
echo $entry->getStatus();
echo $entry->getUrl(); // opt. URL-Schlüssel angeben
```

```php
$categories = neues_entry::get(3)->getCategories();
// dump($categories);

foreach($categories as $category) {
// ...
}
```
