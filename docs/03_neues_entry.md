# Die Klasse `neues_entry`

Kind-Klasse von `rex_yform_manager_dataset`, damit stehen alle Methoden von YOrm Datasets zur Verfügung. Greift auf die Tabelle `rex_neues_entry` zu.

> Es werden nachfolgend zur die durch dieses Addon ergänzte Methoden beschrieben. Lerne mehr über YOrm und den Methoden für Querys, Datasets und Collections in der [YOrm Doku](https://github.com/yakamara/yform/blob/master/docs/04_yorm.md)

## Alle Einträge erhalten

```php
$entries = FriendsOfRedaxo\Neues\Entry::query()->find(); // YOrm-Standard-Methode zum Finden von Einträgen, lässt sich mit where(), Limit(), etc. einschränken und Filtern.
$entries = FriendsOfRedaxo\Neues\Entry::findOnline(); // Alle Online-Einträge
$entries = FriendsOfRedaxo\Neues\Entry::findByCategory($category_id [, $status]) // Alle Einträge einer Kategorie
```

## Methoden und Beispiele

```php
$entry = FriendsOfRedaxo\Neues\Entry::get(3); // News mit der id=3
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
echo $entry->getFormattedPublishDate($format); // IntlDateFormatter::FULL
echo $entry->getFormattedPublishDateTime($format); // [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]
echo $entry->getStatus();
echo $entry->getUrl(); // opt. URL-Schlüssel angeben
```

```php
$categories = FriendsOfRedaxo\Neues\Entry::get(3)->getCategories();
// dump($categories);

foreach($categories as $category) {
// ...
}
```

### getName()

Gibt den Namen des Eintrags zurück.

```php
$name = $entry->getName();
```

### setName(string $name)

Setzt den Namen des Eintrags.

```php
$entry = $entry->setName('Neuer Name');
```

### getAuthor()

Gibt den Autor des Eintrags zurück oder null, wenn kein Autor gesetzt ist.

```php
$author = $entry->getAuthor();
```

### getDomain()

Gibt die Domain des Eintrags zurück.

```php
$domain = $entry->getDomain();
```

### setDomain(mixed $domain)

Setzt die Domain des Eintrags.

```php
$entry = $entry->setDomain('neue-domain.com');
```

### getTeaser()

Gibt den Teaser des Eintrags zurück.

```php
$teaser = $entry->getTeaser();
```

### getCategories()

Gibt die Kategorien des Eintrags zurück oder null, wenn keine Kategorien gesetzt sind.

```php
$categories = $entry->getCategories();
```

### getImage()

Gibt das Bild des Eintrags zurück.

```php
$image = $entry->getImage();
```

### setImage(string $image)

Setzt das Bild des Eintrags.

```php
$entry = $entry->setImage('neues_bild.jpg');
```

### getImages()

Gibt die Bilder des Eintrags zurück oder null, wenn keine Bilder gesetzt sind.

```php
$images = $entry->getImages();
```

### setImages(?array $images)

Setzt die Bilder des Eintrags.

```php
$entry = $entry->setImages(['bild1.jpg', 'bild2.jpg']);
```

### getMedia()

Gibt das Medium des Eintrags zurück oder null, wenn kein Medium gesetzt ist.

```php
$media = $entry->getMedia();
```

### setMedia(?rex_media $media)

Setzt das Medium des Eintrags.

```php
$entry = $entry->setMedia($newMedia);
```

### getDescriptionAsPlaintext()

Gibt die Beschreibung des Eintrags als reinen Text zurück.

```php
$descriptionPlainText = $entry->getDescriptionAsPlaintext();
```

### getDescription()

Gibt die Beschreibung des Eintrags zurück.

```php
$description = $entry->getDescription();
```

### setDescription(string $description)

Setzt die Beschreibung des Eintrags.

```php
$entry = $entry->setDescription('Neue Beschreibung');
```

### getExternalUrl()

Gibt die externe URL des Eintrags zurück oder null, wenn keine URL gesetzt ist.

```php
$externalUrl = $entry->getExternalUrl();
```

### setExternalUrl(string $url)

Setzt die externe URL des Eintrags.

```php
$entry = $entry->setExternalUrl('http://neue-url.com');
```

### getPublishDate()

Gibt das Veröffentlichungsdatum des Eintrags zurück.

```php
$publishDate = $entry->getPublishDate();
```

### setPublishDate(string $publishdate)

Setzt das Veröffentlichungsdatum des Eintrags.

```php
$entry = $entry->setPublishDate('2022-01-01');
```

### getFormattedPublishDate($format_date = IntlDateFormatter::FULL)

Gibt das formatierte Veröffentlichungsdatum des Eintrags zurück.

```php
$formattedPublishDate = $entry->getFormattedPublishDate(IntlDateFormatter::FULL);
```

### getFormattedPublishDateTime($format = [IntlDateFormatter::FULL, IntlDateFormatter::SHORT])

Gibt das formatierte Veröffentlichungsdatum und -zeit des Eintrags zurück.

```php
$formattedPublishDateTime = $entry->getFormattedPublishDateTime([IntlDateFormatter::FULL, IntlDateFormatter::SHORT]);
```

### getStatus()

Gibt den Status des Eintrags zurück.

```php
$status = $entry->getStatus();
```

### setStatus(int $status)

Setzt den Status des Eintrags.

```php
$entry = $entry->setStatus(1);
```

### findOnline(?int $category_id = null)

Findet Online-Einträge. Wenn eine Kategorie-ID angegeben ist, werden nur Einträge aus dieser Kategorie zurückgegeben.

```php
$entries = FriendsOfRedaxo\Neues\Entry::findOnline(1);
```

### findByCategory(?int $category_id = null, int $status = 1)

Findet Einträge nach Kategorie.

```php
$entries = FriendsOfRedaxo\Neues\Entry::findByCategory(1, 1);
```

### getUrl(string $profile = 'neues-entry-id')

Gibt die URL des Eintrags zurück.

```php
$url = $entry->getUrl();
```
