# Die Klasse `neues_author`

Kind-Klasse von `rex_yform_manager_dataset`, damit stehen alle Methoden von YOrm Datasets zur Verfügung. Greift auf die Tabelle `rex_neues_author` zu.

> Es werden nachfolgend zur die durch dieses Addon ergänzte Methoden beschrieben. Lerne mehr über YOrm und den Methoden für Querys, Datasets und Collections in der [YOrm Doku](https://github.com/yakamara/yform/blob/master/docs/04_yorm.md)

## Infos zu einem Autor erhalten

```php
$author = FriendsOfRedaxo\Neues\Author::get(3); // Autor mit der id=3
// dump($author);
```

## Alle Autoren erhalten

```php
$authors = FriendsOfRedaxo\Neues\Authorquery()->find();
foreach($authors as $author) {
    echo $author->getName();
}
```

## Neuen Autor erstellen

```php
$author = new FriendsOfRedaxo\Neues\Author::create();
$author->setName('Name');
$author->setNickname('Spitzname');
$author->save();
```

## Methoden

### `getName()`

Gibt den Namen des Autors zurück.

```php
$name = $author->getName();
```

### `setName(string $value)`

Setzt den Namen des Autors.

```php
$author = $author->setName('Neuer Name');
```

### `getNickname()`

Gibt den Spitznamen des Autors zurück.

```php
$nickname = $author->getNickname();
```

### `setNickname(string $value)`

Setzt den Spitznamen des Autors.

```php
$author = $author->setNickname('Neuer Spitzname');
```

### `getText(bool $asPlaintext = false)`

Gibt den Text des Autors zurück. Wenn `$asPlaintext` true ist, wird der Text ohne HTML-Tags zurückgegeben.

```php
$text = $author->getText(true);
```

### `setText(mixed $value)`

Setzt den Text des Autors.

```php
$author = $author->setText('Neuer Text');
```

### `getBeUserId()`

Gibt die Benutzer-ID des Autors zurück.

```php
$beUserId = $author->getBeUserId();
```

### `setBeUserId(int $value)`

Setzt die Benutzer-ID des Autors.

```php
$author = $author->setBeUserId('Neue Benutzer-ID');
```

### `getUser()`

Gibt den Benutzer (`rex_user`) des Autors zurück.

```php
$author = $author->getUser();
```
