<?php

namespace FriendsOfRedaxo\Neues;

use Alexplusde\ManagerResponsive\Media;
use IntlDateFormatter;
use rex_addon;
use rex_config;
use rex_csrf_token;
use rex_extension;
use rex_extension_point;
use rex_formatter;
use rex_i18n;
use rex_media;
use rex_url;
use rex_yform;
use rex_yform_list;
use rex_yform_manager_collection;
use rex_yform_manager_dataset;
use rex_yform_manager_query;
use rex_yform_manager_table;

use function count;
use function is_bool;
use function is_int;
use function is_string;

/**
 * Class Entry (ex. neues_entry).
 *
 * Diese Klasse repräsentiert einen neuen Eintrag.
 * This class represents a new entry.
 *
 * Beispiel / Example:
 * $entry = FriendsOfRedaxo\Neues\Entry::get($id);
 *
 * @package rex_yform_manager_dataset
 */
class Entry extends rex_yform_manager_dataset
{
    /** @api */
    public const DELETED = -2;
    /** @api */
    public const DRAFT = -1;
    /** @api */
    public const PLANNED = 0;
    /** @api */
    public const ONLINE = 1;

    /**
     * Standards für das Formular anpassen
     * - Editor-Konfiguration einfügen.
     *
     * @api
     */
    public function getForm(): rex_yform
    {
        $yform = parent::getForm();
        $elements = $yform->objparams['form_elements'];

        $elements = rex_extension::registerPoint(new rex_extension_point(
            'NEUES_ENTRY_FORM',
            $elements,
            [
                'form_elements' => $elements,
                'yform' => $yform,
            ],
        ));

        $suchtext = '###neues-settings-editor###';
        foreach ($elements as $k => &$e) {
            if ('textarea' === $e[0] && str_contains($e[5], $suchtext)) {
                $e[5] = str_replace($suchtext, rex_config::get('neues', 'editor'), $e[5]);
            }
        }

        return $yform;
    }

    /**
     * @api
     */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /**
     * Setzt den Namen des Eintrags.
     * Sets the name of the entry.
     *
     * @param string $name Der neue Name des Eintrags. / The new name of the entry.
     *
     * @api
     */
    public function setName(string $name): self
    {
        $this->setValue('name', $name);
        return $this;
    }

    /**
     * YFORM_DATA_LIST: passt die Listendarstellung an.
     *
     * @param rex_extension_point<rex_yform_list> $ep
     * @return void|rex_yform_list
     * @api
     */
    public static function epYformDataList(rex_extension_point $ep)
    {
        /** @var rex_yform_manager_table $table */
        $table = $ep->getParam('table');
        if ($table->getTableName() !== self::table()->getTableName()) {
            return;
        }

        /** @var rex_yform_list $list */
        $list = $ep->getSubject();

        $list->setColumnFormat(
            'name',
            'custom',
            static function ($a) {
                $_csrf_key = Entry::table()->getCSRFKey();
                $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

                $params = [];
                $params['table_name'] = Entry::table()->getTableName();
                $params['rex_yform_manager_popup'] = '0';
                $params['_csrf_token'] = $token['_csrf_token'];
                $params['data_id'] = $a['list']->getValue('id');
                $params['func'] = 'edit';

                return '<a href="' . rex_url::backendPage('neues/entry', $params) . '">' . $a['value'] . '</a>';
            },
        );
        $list->setColumnFormat(
            'neues_category_id',
            'custom',
            static function ($a) {
                $_csrf_key = Category::table()->getCSRFKey();
                $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

                $params = [];
                $params['table_name'] = Category::table()->getTableName();
                $params['rex_yform_manager_popup'] = '0';
                $params['_csrf_token'] = $token['_csrf_token'];
                $params['data_id'] = $a['list']->getValue('id');
                $params['func'] = 'edit';

                $return = [];

                $category_ids = array_filter(array_map('intval', explode(',', $a['value'])));

                foreach ($category_ids as $category_id) {
                    /** @var Category|null $neues_category */
                    $neues_category = Category::get($category_id);
                    if (null !== $neues_category) {
                        $return[] = '<a href="' . rex_url::backendPage('neues/category', $params) . '">' . $neues_category->getName() . '</a>';
                    }
                }
                return implode('<br>', $return);
            },
        );
    }

    /**
     * Gibt den Autor des Eintrags zurück.
     * Returns the author of the entry.
     *
     * @return Author|null Der Autor des Eintrags oder null, wenn kein Autor gesetzt ist. / The author of the entry or null if no author is set.
     *
     * Beispiel / Example:
     * $author = $entry->getAuthor();
     *
     * @api
     */
    public function getAuthor(): ?Author
    {
        if ($this->hasValue('author_id')) {
            return Author::get($this->getValue('author_id'));
        }
        return null;
    }

    /**
     * @api
     */
    public function getDomain(): string
    {
        if ($this->hasValue('domain')) {
            return (string) $this->getValue('domain');
        }
        return '';
    }

    /**
     * Setzt die Domain des Eintrags.
     * Sets the domain of the entry.
     *
     * @param mixed $domain Die neue Domain des Eintrags. / The new domain of the entry.
     *
     * @api
     */
    public function setDomain(mixed $domain): self
    {
        $this->setValue('domain', $domain);
        return $this;
    }

    /**
     * Gibt den Teaser des Eintrags zurück.
     * Returns the teaser of the entry.
     *
     * @return string Der Teaser des Eintrags. / The teaser of the entry.
     *
     * Beispiel / Example:
     * $teaser = $entry->getTeaser();
     *
     * @api
     */
    public function getTeaser(): string
    {
        if ($this->hasValue('teaser')) {
            return $this->getValue('teaser');
        }
        return '';
    }

    /**
     * Gibt die Kategorien des Eintrags zurück.
     * Returns the categories of the entry.
     *
     * @return rex_yform_manager_collection<Category> Die Kategorien des Eintrags oder eine leere Liste. / The categories of the entry or an empty list.
     *
     * Beispiel / Example:
     * $categories = $entry->getCategories();
     *
     * @api
     */
    public function getCategories(): rex_yform_manager_collection
    {
        return $this->getRelatedCollection('category_ids');
    }

    /**
     * Gibt das Bild des Eintrags zurück.
     * Returns the image of the entry.
     *
     * @return string Das Bild des Eintrags. / The image of the entry.
     *
     * Beispiel / Example:
     * $image = $entry->getImage();
     *
     * @api
     */
    public function getImage(): string
    {
        if ($this->hasValue('image')) {
            $image = $this->getValue('image');
        } else {
            $image = '';
        }
        if ('' == $image) {
            $image = rex_config::get('neues', 'default_thumbnail');
            $this->setImage($image);
        }
        return $image;
    }

    /**
     * Setzt das Bild des Eintrags.
     * Sets the image of the entry.
     *
     * @param string $image Das neue Bild des Eintrags. / The new image of the entry.
     *
     * @api
     */
    public function setImage(string $image): self
    {
        $this->setValue('image', $image);
        return $this;
    }

    /**
     * Gibt die Bilder des Eintrags zurück.
     * Returns the images of the entry.
     *
     * @return array<string> Die Bilder des Eintrags oder [], wenn keine Bilder gesetzt sind. / The images of the entry or [] if no images are set.
     *
     * Beispiel / Example:
     * $images = $entry->getImages();
     * @api
     */
    public function getImages(): array
    {
        if ($this->hasValue('images')) {
            $images = $this->getValue('images');
            return array_filter(explode(',', $images));
        }
        return [];
    }

    /**
     * Setzt die Bilder des Eintrags.
     * Sets the images of the entry.
     *
     * @param array<string> $images Die neuen Bilder des Eintrags. / The new images of the entry.
     *
     * @api
     */
    public function setImages(array $images = []): self
    {
        $this->setValue('images', implode(',', $images));
        return $this;
    }

    /**
     * Gibt das Medium des Eintrags zurück.
     * Returns the media of the entry.
     *
     * @return rex_media|null Das Medium des Eintrags oder null, wenn kein Medium gesetzt ist. / The media of the entry or null if no media is set.
     *
     * Beispiel / Example:
     * $media = $entry->getMedia();
     *
     * @api
     */
    public function getMedia(): ?rex_media
    {
        $image = $this->getImage();
        if ('' !== $image) {
            if (rex_addon::get('media_manager_responsive')->isAvailable()) {
                return Media::get($image);
            }
            return rex_media::get($image);
        }
        return null;
    }

    /**
     * Setzt das Medium des Eintrags.
     * Sets the media of the entry.
     *
     * @param rex_media|null $media Das neue Medium des Eintrags oer null für Entfernen. / The new media of the entry or null to delete.
     *
     * @api
     */
    public function setMedia(?rex_media $media): self
    {
        if (null !== $media) {
            $this->setValue('image', $media->getFileName());
        } else {
            $this->setValue('image', '');
        }
        return $this;
    }

    /**
     * Gibt die Beschreibung des Eintrags als reinen Text zurück.
     * Returns the description of the entry as plain text.
     *
     * @return string Die Beschreibung des Eintrags als reinen Text. / The description of the entry as plain text.
     *
     * Beispiel / Example:
     * $descriptionPlainText = $entry->getDescriptionAsPlaintext();
     *
     * @api
     */
    public function getDescriptionAsPlaintext(): string
    {
        return strip_tags($this->getDescription());
    }

    /**
     * Gibt die Beschreibung des Eintrags zurück.
     * Returns the description of the entry.
     *
     * @return string Die Beschreibung des Eintrags. / The description of the entry.
     *
     * Beispiel / Example:
     * $description = $entry->getDescription();
     *
     * @api
     */
    public function getDescription(): string
    {
        if ($this->hasValue('description')) {
            return $this->getValue('description');
        }
        return '';
    }

    /**
     * Setzt die Beschreibung des Eintrags.
     * Sets the description of the entry.
     *
     * @param string $description Die neue Beschreibung des Eintrags. / The new description of the entry.
     *
     * @api
     */
    public function setDescription(string $description): self
    {
        $this->setValue('description', $description);
        return $this;
    }

    /**
     * Gibt die externe URL des Eintrags zurück.
     * Returns the external URL of the entry.
     *
     * @return string Die externe URL des Eintrags oder '', wenn keine URL gesetzt ist. / The external URL of the entry or '' if no URL is set.
     *
     * Beispiel / Example:
     * $externalUrl = $entry->getExternalUrl();
     *
     * @api
     */
    public function getExternalUrl(): string
    {
        if ($this->hasValue('url')) {
            return $this->getValue('url');
        }
        return '';
    }

    /**
     * Setzt die externe URL des Eintrags.
     * Sets the external URL of the entry.
     *
     * @param string $url Die neue externe URL des Eintrags. / The new external URL of the entry.
     *
     * @api
     */
    public function setExternalUrl(string $url): self
    {
        $this->setValue('url', $url);
        return $this;
    }

    /**
     * Gibt die Canonical URL des Eintrags zurück.
     * Returns the canonical URL of the entry.
     *
     * @return string Die Canonical URL des Eintrags oder '', wenn keine URL gesetzt ist. / The canonical URL of the entry or '' if no URL is set.
     *
     * Beispiel / Example:
     * $canonicalUrl = $entry->getCanonicalUrl();
     *
     * @api
     */
    public function getCanonicalUrl(): string
    {
        if ($this->hasValue('canonical_url')) {
            return $this->getValue('canonical_url');
        }
        return '';
    }

    /**
     * Setzt die Canonical URL des Eintrags.
     * Sets the canonical URL of the entry.
     *
     * @param string $url Die neue Canonical URL des Eintrags. / The new canonical URL of the entry.
     *
     * @api
     */
    public function setCanonicalUrl(string $url): self
    {
        $this->setValue('canonical_url', $url);
        return $this;
    }

    /**
     * Gibt das Veröffentlichungsdatum des Eintrags zurück.
     * Returns the publish date of the entry.
     *
     * @return string Das Veröffentlichungsdatum des Eintrags. / The publish date of the entry.
     *
     * Beispiel / Example:
     * $publishDate = $entry->getPublishDate();
     *
     * @api
     */
    public function getPublishDate(): string
    {
        if ($this->hasValue('publishdate')) {
            return $this->getValue('publishdate');
        }
        return '';
    }

    /**
     * Setzt das Veröffentlichungsdatum des Eintrags.
     * Sets the publish date of the entry.
     *
     * @param string $publishdate Das neue Veröffentlichungsdatum des Eintrags. / The new publish date of the entry.
     *
     * @api
     */
    public function setPublishDate(string $publishdate): self
    {
        $this->setValue('publishdate', $publishdate);
        return $this;
    }

    /**
     * Gibt das formatierte Veröffentlichungsdatum des Eintrags zurück.
     * Returns the formatted publish date of the entry.
     *
     * @param int $format_date Das Format des Datums. Standardmäßig IntlDateFormatter::FULL. / The format of the date. Defaults to IntlDateFormatter::FULL.
     * @return string Das formatierte Veröffentlichungsdatum des Eintrags. / The formatted publish date of the entry.
     *
     * Beispiel / Example:
     * $formattedPublishDate = $entry->getFormattedPublishDate(IntlDateFormatter::FULL);
     *
     * @api
     */
    public function getFormattedPublishDate($format_date = IntlDateFormatter::FULL): string
    {
        return $this->getFormattedPublishDateTime([$format_date, IntlDateFormatter::NONE]);
    }

    /**
     * Gibt das formatierte Veröffentlichungsdatum und -zeit des Eintrags zurück.
     * Returns the formatted publish date and time of the entry.
     *
     * @param array{int,int} $format Das Format des Datums und der Zeit. Standardmäßig [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]. / The format of the date and time. Defaults to [IntlDateFormatter::FULL, IntlDateFormatter::SHORT].
     * @return string Das formatierte Veröffentlichungsdatum und -zeit des Eintrags. / The formatted publish date and time of the entry.
     *
     * Beispiel / Example:
     * $formattedPublishDateTime = $entry->getFormattedPublishDateTime([IntlDateFormatter::FULL, IntlDateFormatter::SHORT]);
     *
     * @api
     */
    public function getFormattedPublishDateTime($format = [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]): string
    {
        $date = $this->getPublishDate();
        if ('' !== $date) {
            return rex_formatter::intlDateTime($date, $format);
        }
        return '';
    }

    /**
     * Gibt den Status des Eintrags zurück.
     * Returns the status of the entry.
     *
     * @return int Der Status des Eintrags. / The status of the entry.
     *
     * Beispiel / Example:
     * $status = $entry->getStatus();
     *
     * @api
     */
    public function getStatus(): int
    {
        if ($this->hasValue('status')) {
            return $this->getValue('status');
        }
        return self::DRAFT;
    }

    /**
     * Setzt den Status des Eintrags.
     * Sets the status of the entry.
     *
     * @param int $status Der neue Status des Eintrags. / The new status of the entry.
     *
     * @api
     */
    public function setStatus(int $status): self
    {
        $this->setValue('status', $status);
        return $this;
    }

    /**
     * Allgemeine Suche nach Einträgen über Kategorien, Status, Autor und Sprachen
     * General Search for Entries by category, language, author, and status.
     *
     * Anwendungsbeispiele / Usage examples
     * $allEntries = Entry::findBy();
     * $entries = Entry::findBy (status: Entry::IS_ONLINE, category: '1,2');
     * $entries = Entry::findBy (status: Entry::PLANNED, category: [1,2], lang: 'de');
     *
     * @api
     * @param int|array<int>|string $category
     * @param int|array<int>|bool   $status
     * @param int|array<int>|string $author
     * @return rex_yform_manager_collection<static>
     */
    public static function findBy(int|array|string $category = [], int|array|bool $status = [], string|int $lang = -1, int|array|string $author = []): rex_yform_manager_collection
    {
        $query = self::queryBy($category, $status, $lang, $author);
        return $query->find();
    }

    /**
     * Allgemeine Suche nach Einträgen über Kategorien, Status, Autor und Sprachen vorbereiten.
     * An die erzeugte Query können weitere Conditions oder Sortierungen angefügt werden.
     *
     * Prepare a general search for entries by category, language, author, and status
     * Add further conditions or sorts
     *
     * Anwendungsbeispiele / Usage examples
     * $query = Entry::queryBy();
     * $query = Entry::queryBy (status: Entry::IS_ONLINE, category: '1,2');
     * $query = Entry::queryBy (status: Entry::PLANNED, category: [1,2], lang: 'de');
     *
     * $query->sortBy('publishdate', 'desc');
     * $entries = $query->find();
     *
     * @api
     * @param int|array<int>|string $category
     * @param int|array<int>|bool   $status
     * @param int|array<int>|string $author
     * @return rex_yform_manager_query<static>
     */
    public static function queryBy(int|array|string $category = [], int|array|bool $status = [], string|int $lang = -1, int|array|string $author = []): rex_yform_manager_query
    {
        /**
         * Die Abfrage vorbereiten.
         */
        $query = self::query();
        $alias = $query->getTableAlias();
        // Als Beifang wird ein Zusatzfeld mit den IDs der zugeordneten Kategorien
        // erzeugt (categories), das für die Filterung auf Kategorien erforderlich ist
        $query->leftJoinRelation('category_ids', 'c');
        $query->selectRaw('IFNULL(GROUP_CONCAT(c.id),"0")', 'categories');
        $query->groupBy($alias . '.id');

        /**
         * Kategorie in einen normierten Wert umwandeln und ggf. die Query anpassen
         * - String in ein Array umwandeln
         * - Einzelwert in ein Array umandeln
         * - Default ist [] => kein Filter
         * - 0 ist "alle ohne Kategorie".
         */
        if (is_string($category)) {
            $category = array_filter(explode(',', $category));
        }
        if (is_int($category)) {
            $category = [$category];
        }
        if (0 < count($category)) {
            $query->havingListContains('categories', $category);
        }

        /**
         * Status berücksichtigen
         * - Bool-Wert (Entry::IS_ONLINE)
         * - Int-Wert für einen speziellen Status
         * - Array mit mehreren Int-Werten eines Status.
         * - Default ist [] => kein Filter.
         */
        $boolStatus = is_bool($status);
        if (is_bool($status)) {
            $operator = $status ? '>=' : '<';
            $query->where($alias . '.status', self::ONLINE, $operator);
        } elseif (is_int($status)) {
            $query->where($alias . '.status', $status);
        } elseif (0 < count($status)) {
            $query->whereListContains($alias . '.status', $status);
        }

        /**
         * Sprache berücksichtigen
         * - string verweist auf das Feld rex_neues_entry_lang.code (in ID auflösen)
         * - int verweist auf die ID, die aber auch direkt im Datensatz steht.
         * - Default: -1 => keine Einschränkung, alle suchen
         * - Abfrage nur der Entries ohne Sprachzuordnung mit lang=0
         * Immer im Ergebnisset: "alle Sprachen", also Texte ohne spezifische Sprachzuordnung (0).
         */
        if (is_string($lang)) {
            $language = EntryLang::query()->where('code', $lang)->findOne();
            $lang = null === $language ? -1 : $language->getId();
        }
        if (-1 !== $lang) {
            $condition = [0];
            if (0 !== $lang) {
                $condition[] = $lang;
            }
            $query->whereListContains('lang_id', $condition);
        }

        /**
         * Autor eingrenzen
         * - String in ein Array umwandeln
         * - int ist die ID genau eines Autors
         * - array ist eine Autorenliste
         * - 0 liefert die Entries ohne zugeordneten Autor
         * - Default: [] steht für alle Einträge, kein Filter.
         */
        if (is_string($author)) {
            $author = array_filter(explode(',', $author));
        }
        if (is_int($author)) {
            $author = [$author];
        }
        if (0 < count($author)) {
            $query->whereListContains('author_id', $author);
        }

        /**
         * Abfrage bereitstellen.
         */
        return $query;
    }

    /**
     * Findet Online-Einträge. Wenn eine Kategorie-ID angegeben ist, werden nur Einträge aus dieser Kategorie zurückgegeben.
     * Finds online entries. If a Category ID is provided, only entries from this Category are returned.
     *
     * @param int $category_id Die ID der Kategorie. / The ID of the Category.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findOnline(1);
     *
     * @api
     */
    public static function findOnline(int $category_id = 0): rex_yform_manager_collection
    {
        /**
         * TODO: auf Entry::findBy zurückgreifen oder die Methode entfernen weil durch findBy obsolet
         *     return Entry::findBy (category: $category, status: self::IS_ONLINE);.
         */
        if (null !== $category_id) {
            return self::findByCategory($category_id);
        }
        return self::query()->where('status', self::ONLINE, '>=')->find();
    }

    /**
     * Findet Einträge nach Kategorie.
     * Finds entries by Category.
     *
     * @param int $category_id Die ID der Kategorie. / The ID of the Category.
     * @param int $status Der Status der Einträge. / The status of the entries.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findByCategory(1, 1);
     *
     * @api
     */
    public static function findByCategory(int $category_id = 0, int $status = self::ONLINE): rex_yform_manager_collection
    {
        /**
         * TODO: auf Entry::findBy zurückgreifen oder die Methode entfernen weil durch findBy obsolet
         *     return Entry::findBy (category: $category, status: $status);.
         */
        $query = self::query();
        $alias = $query->getTableAlias();
        $query->joinRelation('category_ids', 'c')->where($alias . '.status', $status, '>=')->where('c.id', $category_id);
        return $query->find();
    }

    /**
     * Findet Einträge durch IDs mehrerer Kategorien.
     * Finds entries by multiple Categories.
     *
     * @param string|array<int> $category_ids Die IDs der Kategorien als String oder Array. / The IDs of the Categories as a String or Array.
     * @param int $status Der Status der Einträge. / The status of the entries.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findByCategoryIds('1,2', 1);
     *
     * @api
     */
    public static function findByCategoryIds(string|array $category_ids = [], int $status = self::ONLINE): rex_yform_manager_collection
    {
        /**
         * TODO: auf Entry::findBy zurückgreifen oder die Methode entfernen weil durch findBy obsolet
         *     return Entry::findBy (category: $category, status: $status);.
         */
        $query = self::query()->where('status', $status, '>=');

        if (is_string($category_ids)) {
            $category_ids = explode(',', $category_ids);
        }
        if (0 < count($category_ids)) {
            $query->whereListContains('category_ids', $category_ids);
        }

        return $query->find();
    }

    /**
     * Gibt die URL des Eintrags zurück.
     * Returns the URL of the entry.
     *
     * @param string $profile Das Profil, das für die URL-Erstellung verwendet wird. Standardmäßig 'neues-entry-id'. / The profile used for URL creation. Defaults to 'neues-entry-id'.
     * @return string Die URL des Eintrags oder ein leerer String, wenn keine URL gefunden wurde. / The URL of the entry or an empty string if no URL was found.
     *
     * Beispiel / Example:
     * $url = $entry->getUrl('neues-entry-id');
     *
     * @api
     */
    public function getUrl(string $profile = 'neues-entry-id'): string
    {
        return rex_getUrl(null, null, [$profile => $this->getId()]);
    }

    /**
     * Callback für das Entry-Formular: Auswahlmöglichkeiten des Status-Feldes
     * FriendsOfRedaxo\Neues\Entry::statusChoice.
     * @api
     * @return array<int,string>
     */
    public static function statusChoice(): array
    {
        return [
            self::DELETED => rex_i18n::msg('neues_status_deleted'),
            self::DRAFT => rex_i18n::msg('neues_status_draft'),
            self::PLANNED => rex_i18n::msg('neues_status_planned'),
            self::ONLINE => rex_i18n::msg('neues_status_online'),
        ];
    }
}
