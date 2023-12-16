<?php
/**
 * Klasse neues_category.
 *
 * Diese Klasse repräsentiert eine Kategorie in der News-Verwaltung.
 * Sie erbt von der rex_yform_manager_dataset Klasse.
 *
 * Class neues_category
 *
 * This class represents a category in the news management.
 * It inherits from the rex_yform_manager_dataset class.
 *
 * $category = neues_category::create();
 * $category->setValue('name', 'Neue Kategorie');
 * $category->save();
 */
class neues_category extends \rex_yform_manager_dataset
{
    /**
     * Gibt den Namen der Kategorie zurück.
     * Returns the name of the category.
     *
     * @return string Der Name der Kategorie. / The name of the category.
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
     * Sets the name of the category.
     *
     * @param string $name Der neue Name der Kategorie. / The new name of the category.
     *
     * Beispiel / Example:
     * $category->setName('Neuer Name');
     */
    public function setName(string $name): self
    {
        $this->setValue('name', $name);
        return $this;
    }

    /**
     * Gibt die Einträge der Kategorie zurück.
     * Returns the entries of the category.
     *
     * @return rex_yform_manager_collection|null Die Einträge der Kategorie oder null, wenn keine Einträge vorhanden sind. / The entries of the category or null if no entries are present.
     *
     * Beispiel / Example:
     * $entries = $category->getEntries();
     *
     * @api
     */
    public function getEntries(): ?rex_yform_manager_collection
    {
        return $this->getRelatedDataset('entry_ids');
    }

    /**
     * Gibt die URL der Kategorie zurück.
     * Returns the URL of the category.
     *
     * @param string $profile Das Profil, das für die URL-Erstellung verwendet wird. Standardmäßig 'neues-category-id'. / The profile used for URL creation. Defaults to 'neues-category-id'.
     * @return string Die URL der Kategorie oder ein leerer String, wenn keine URL gefunden wurde. / The URL of the category or an empty string if no URL was found.
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
