<?php

namespace FriendsOfRedaxo\Neues;

use rex_yform_manager_collection;
use rex_yform_manager_dataset;

/**
 * Klasse neues_category.
 *
 * Diese Klasse repräsentiert eine Kategorie in der News-Verwaltung.
 * Sie erbt von der rex_yform_manager_dataset Klasse.
 *
 * Class neues_category
 *
 * This class represents a Category in the news management.
 * It inherits from the rex_yform_manager_dataset class.
 *
 * $category = FriendsOfRedaxo\Neues\Categoycreate();
 * $category->setValue('name', 'Neue Kategorie');
 * $category->save();
 */
class Category extends rex_yform_manager_dataset
{
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
        return $this->getValue('name');
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
     * @return rex_yform_manager_collection|null Die Einträge der Kategorie oder null, wenn keine Einträge vorhanden sind. / The entries of the Category or null if no entries are present.
     *
     * Beispiel / Example:
     * $entries = $category->getEntries();
     *
     * @api
     */
    public function getEntries(): ?rex_yform_manager_collection
    {
        return $this->getRelatedCollection('entry_ids');
    }

    /**
     * Gibt die URL der Kategorie zurück.
     * Returns the URL of the Category.
     *
     * @param string $profile Das Profil, das für die URL-Erstellung verwendet wird. Standardmäßig 'neues-category-id'. / The profile used for URL creation. Defaults to 'neues-category-id'.
     * @return string Die URL der Kategorie oder ein leerer String, wenn keine URL gefunden wurde. / The URL of the Category or an empty string if no URL was found.
     *
     * Beispiel / Example:
     * $url = $category->getUrl();
     *
     * @api
     */
    public function getUrl(string $profile = 'neues-category-id'): ?string
    {
        if ($url = rex_getUrl(null, null, [$profile => $this->getId()])) {
            return $url;
        }
        return null;
    }
}
