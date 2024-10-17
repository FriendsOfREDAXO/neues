<?php

namespace FriendsOfRedaxo\Neues;

use rex_i18n;
use rex_yform_manager_collection;
use rex_yform_manager_dataset;

/**
 * Klasse Category (ex. neues_category).
 *
 * Diese Klasse repräsentiert eine Kategorie in der News-Verwaltung.
 * Sie erbt von der rex_yform_manager_dataset Klasse.
 *
 * Class Category (ex. neues_category).
 *
 * This class represents a Category in the news management.
 * It inherits from the rex_yform_manager_dataset class.
 *
 * $category = FriendsOfRedaxo\Neues\Categoy::create();
 * $category->setValue('name', 'Neue Kategorie');
 * $category->save();
 */
class Category extends rex_yform_manager_dataset
{
    /** @api */
    public const DRAFT = -1;
    /** @api */
    public const ONLINE = 1;

    /**
     * Gibt den Namen der Kategorie zurück.
     * Returns the name of the Category.
     *
     * @return string Der Name der Kategorie. / The name of the Category.
     *
     * Beispiel / Example:
     * $name = $category->getName();
     *
     * @api
     */
    public function getName(): string
    {
        if ($this->hasValue('name')) {
            return $this->getValue('name');
        }
        return '';
    }

    /**
     * Setzt den Namen der Kategorie.
     * Sets the name of the Category.
     *
     * @param string $name Der neue Name der Kategorie. / The new name of the Category.
     *
     * Beispiel / Example:
     * $category->setName('Neuer Name');
     *
     * @api
     */
    public function setName(string $name): self
    {
        $this->setValue('name', $name);
        return $this;
    }

    /**
     * Gibt die Einträge der Kategorie zurück.
     * Returns the entries of the Category.
     *
     * @return rex_yform_manager_collection<Entry> Die Einträge der Kategorie oder leere Liste, wenn keine Einträge vorhanden sind. / The entries of the Category or empty list if no entries are present.
     *
     * Beispiel / Example:
     * $entries = $category->getEntries();
     *
     * @api
     */
    public function getEntries(): rex_yform_manager_collection
    {
        return $this->getRelatedCollection('date_id');
    }

    /**
     * Gibt die URL der Kategorie zurück.
     * Returns the URL of the Category.
     *
     * @param string $profile Das Profil, das für die URL-Erstellung verwendet wird. Standardmäßig 'neues-category-id'. / The profile used for URL creation. Defaults to 'neues-category-id'.
     * @return string Die URL der Kategorie. / The URL of the Category.
     *
     * Beispiel / Example:
     * $url = $category->getUrl();
     *
     * @api
     */
    public function getUrl(string $profile = 'neues-category-id'): string
    {
        return rex_getUrl(null, null, [$profile => $this->getId()]);
    }

    /**
     * Callback für das Entry-Formular: Auswahlmöglichkeiten des Status-Feldes
     * FriendsOfRedaxo\Neues\Category::statusChoice.
     * @api
     * @return array<int,string>
     */
    public static function statusChoice(): array
    {
        return [
            self::DRAFT => rex_i18n::msg('neues_status_draft'),
            self::ONLINE => rex_i18n::msg('neues_status_online'),
        ];
    }
}
