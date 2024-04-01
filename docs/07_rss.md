# RSS-Feed

> Hinweis: Der RSS-Feed befindet sich noch in Arbeit, es gibt noch kein Datum für die Fertigstellung. Beteilige dich an der Entwicklung, wenn du möchtest.

Die `rex_api_neues_rss`-Klasse ist Teil des `Neues`-Addons und ermöglicht die Generierung eines RSS-Feeds für News-Einträge. Der Feed wird über die URL `index.php?rex-api-call=neues_rss` aufgerufen. Die Ausgabe wird durch verschiedene URL-Parameter beeinflusst:

- `domain_id`: Dieser Parameter ermöglicht es, den Feed auf eine bestimmte Domain zu beschränken. Zum Beispiel wird `index.php?rex-api-call=neues_rss&domain_id=1` verwendet, um den Feed für die Domain mit der ID 1 zu generieren.

- `lang_id`: Mit diesem Parameter wird der Feed auf eine bestimmte Sprache beschränkt. Zum Beispiel wird `index.php?rex-api-call=neues_rss&lang_id=2` verwendet, um den Feed in der Sprache mit der ID 2 zu generieren.

- `category_id`: Dieser Parameter ermöglicht es, den Feed auf eine bestimmte Kategorie zu beschränken. Zum Beispiel wird `index.php?rex-api-call=neues_rss&category_id=3` verwendet, um den Feed für die Kategorie mit der ID 3 zu generieren.

Die Parameter `domain_id`, `lang_id` und `category_id` sind optional und der Feed kann auch ohne diese Parameter generiert werden. Wenn sie nicht angegeben werden, werden alle Domains, Sprachen und Kategorien in den Feed einbezogen.
