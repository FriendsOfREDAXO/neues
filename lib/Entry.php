<?php

namespace FriendsOfRedaxo\Neues;

use IntlDateFormatter;
use rex_addon;
use rex_config;
use rex_csrf_token;
use rex_extension_point;
use rex_formatter;
use rex_i18n;
use rex_media;
use rex_media_plus;
use rex_url;
use rex_yform;
use rex_yform_list;
use rex_yform_manager_collection;
use rex_yform_manager_dataset;
use rex_yform_manager_table;

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
    public const DELETED = 2; // FIXME: muss auf -2 geändert werden.
    public const DRAFT = -1;
    public const PLANNED = 0;
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

        $suchtext = '###neues-settings-editor###';
        foreach ($yform->objparams['form_elements'] as $k => &$e) {
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
        if ($this->getRelatedDataset('author_id')) {
            return Author::get($this->getRelatedDataset('author_id')->getId());
        }
        return null;
    }

    /**
     * @api
     */
    public function getDomain(): string
    {
        return $this->getValue('domain');
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
        return $this->getValue('teaser');
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
        if ('' == $this->getValue('image')) {
            $this->image = rex_config::get('neues', 'default_thumbnail');
        } else {
            $this->image = $this->getValue('image');
        }
        return $this->image;
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
     * @return array|null Die Bilder des Eintrags oder null, wenn keine Bilder gesetzt sind. / The images of the entry or null if no images are set.
     *
     * Beispiel / Example:
     * $images = $entry->getImages();
     * TODO: null kommt nicht vor
     * @api
     */
    public function getImages(): ?array
    {
        return array_filter(explode(',', $this->getValue('images')));
    }

    /**
     * Setzt die Bilder des Eintrags.
     * Sets the images of the entry.
     *
     * @param array|null $images Die neuen Bilder des Eintrags. / The new images of the entry.
     *
     * @api
     */
    public function setImages(?array $images): self
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
        if (rex_addon::get('media_manager_responsive')->isAvailable()) {
            return rex_media_plus::get($this->getImage());
        }
        return rex_media::get($this->getImage());
    }

    /**
     * Setzt das Medium des Eintrags.
     * Sets the media of the entry.
     *
     * @param rex_media|null $media Das neue Medium des Eintrags. / The new media of the entry.
     *
     * @api
     */
    public function setMedia(?rex_media $media): self
    {
        if (null !== $media) {
            $this->setValue('image', $media->getFileName());
        } else {
            $this->setValue('image', null);
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
        return strip_tags($this->getValue('description'));
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
        return $this->getValue('description');
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
     * @return string|null Die externe URL des Eintrags oder null, wenn keine URL gesetzt ist. / The external URL of the entry or null if no URL is set.
     *
     * Beispiel / Example:
     * $externalUrl = $entry->getExternalUrl();
     *
     * @api
     */
    public function getExternalUrl(): ?string
    {
        return $this->getValue('url');
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
        return $this->getValue('publishdate');
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
     * @param array $format Das Format des Datums und der Zeit. Standardmäßig [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]. / The format of the date and time. Defaults to [IntlDateFormatter::FULL, IntlDateFormatter::SHORT].
     * @return string Das formatierte Veröffentlichungsdatum und -zeit des Eintrags. / The formatted publish date and time of the entry.
     *
     * Beispiel / Example:
     * $formattedPublishDateTime = $entry->getFormattedPublishDateTime([IntlDateFormatter::FULL, IntlDateFormatter::SHORT]);
     *
     * @api
     */
    public function getFormattedPublishDateTime($format = [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]): string
    {
        return rex_formatter::intlDateTime($this->getPublishDate(), $format);
    }

    /**
     * Gibt den Status des Eintrags zurück.
     * Returns the status of the entry.
     *
     * @return string Der Status des Eintrags. / The status of the entry.
     *
     * Beispiel / Example:
     * $status = $entry->getStatus();
     *
     * @api
     */
    public function getStatus(): string
    {
        return $this->getValue('status');
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
     * Findet Online-Einträge. Wenn eine Kategorie-ID angegeben ist, werden nur Einträge aus dieser Kategorie zurückgegeben.
     * Finds online entries. If a Category ID is provided, only entries from this Category are returned.
     *
     * @param int|null $category_id Die ID der Kategorie. / The ID of the Category.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findOnline(1);
     *
     * @api
     */
    public static function findOnline(?int $category_id = null): rex_yform_manager_collection
    {
        if (null !== $category_id) {
            return self::findByCategory($category_id);
        }
        return self::query()->where('status', self::ONLINE, '>=')->find();
    }

    /**
     * Findet Einträge nach Kategorie.
     * Finds entries by Category.
     *
     * @param int|null $category_id Die ID der Kategorie. / The ID of the Category.
     * @param int $status Der Status der Einträge. / The status of the entries.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findByCategory(1, 1);
     *
     * @api
     */
    public static function findByCategory(?int $category_id = null, int $status = self::ONLINE): rex_yform_manager_collection
    {
        $query = self::query();
        $alias = $query->getTableAlias();
        $query->joinRelation('category_ids', 'c')->where($alias . '.status', $status, '>=')->where('c.id', $category_id);
        return $query->find();
    }

    /**
     * Findet Einträge durch IDs mehrerer Kategorien.
     * Finds entries by multiple Categories.
     *
     * @param string|array|null $category_ids Die IDs der Kategorien als String oder Array. / The IDs of the Categories as a String or Array.
     * @param int $status Der Status der Einträge. / The status of the entries.
     * @return rex_yform_manager_collection<static> Die gefundenen Einträge bzw. eine leere Liste. / The found entries or empty list.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findByCategoryIds('1,2', 1);
     *
     * @api
     */
    public static function findByCategoryIds(string|array|null $category_ids = null, int $status = self::ONLINE): rex_yform_manager_collection
    {
        $query = self::query()->where('status', $status, '>=');

        if ($category_ids) {
            // Wenn es ein String ist, in ein Array umwandeln
            if (is_string($category_ids)) {
                $category_ids = explode(',', $category_ids);
            }

            // whereInList anwenden
            // FIXME: whereInList gibt es nicht! Ist $query->whereListContains() gemeint?
            $query->whereInList('category_ids', $category_ids);
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
