<?php

namespace FriendsOfRedaxo\Neues;

use IntlDateFormatter;
use rex_addon;
use rex_config;
use rex_formatter;
use rex_media;
use rex_media_plus;
use rex_yform_manager_collection;
use rex_yform_manager_dataset;

/**
 * Class neues_entry.
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
     * Gibt den Autor des Eintrags zurück.
     * Returns the author of the entry.
     *
     * @return neues_author|null Der Autor des Eintrags oder null, wenn kein Autor gesetzt ist. / The author of the entry or null if no author is set.
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
     * @return rex_yform_manager_collection|null Die Kategorien des Eintrags oder null, wenn keine Kategorien gesetzt sind. / The categories of the entry or null if no categories are set.
     *
     * Beispiel / Example:
     * $categories = $entry->getCategories();
     *
     * @api
     */
    public function getCategories(): ?rex_yform_manager_collection
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
     *
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
     * @return rex_yform_manager_collection|null Die gefundenen Einträge oder null, wenn keine Einträge gefunden wurden. / The found entries or null if no entries were found.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findOnline(1);
     *
     * @api
     */
    public static function findOnline(?int $category_id = null): ?rex_yform_manager_collection
    {
        if ($category_id) {
            return self::findByCategory($category_id);
        }
        return self::query()->where('status', 1, '>=')->find();
    }

    /**
     * Findet Einträge nach Kategorie.
     * Finds entries by Category.
     *
     * @param int|null $category_id Die ID der Kategorie. / The ID of the Category.
     * @param int $status Der Status der Einträge. / The status of the entries.
     * @return rex_yform_manager_collection|null Die gefundenen Einträge oder null, wenn keine Einträge gefunden wurden. / The found entries or null if no entries were found.
     *
     * Beispiel / Example:
     * $entries = FriendsOfRedaxo\Neues\Entry::findByCategory(1, 1);
     *
     * @api
     */
    public static function findByCategory(?int $category_id = null, int $status = 1): ?rex_yform_manager_collection
    {
        $query = self::query()->joinRelation('category_ids', 'c')->where('rex_neues_entry.status', $status, '>=')->where('c.id', $category_id);
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
        if ($url = rex_getUrl(null, null, [$profile => $this->getId()])) {
            return $url;
        }
        return '';
    }
}
