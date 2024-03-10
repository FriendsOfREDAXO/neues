<?php

namespace FriendsOfRedaxo\neues;

use rex_yform_manager_collection;
use rex_yform_manager_dataset;

/**
 * Class neues_entry_lang.
 *
 * Diese Klasse repräsentiert einen neuen Eintrag.
 * This class represents a new entry.
 *
 * Beispiel / Example:
 * $entry = neues_entry_lang::get($id);
 *
 * @package rex_yform_manager_dataset
 */
class EntryLang extends rex_yform_manager_dataset
{
    /**
     * Gibt die News-Einträge der aktuellen Sprache zurück.
     * Returns the entries of the language.
     *
     * @return rex_yform_manager_collection|null Die Einträge der Sprache oder null, wenn keine Einträge gefunden wurden. / The entries of the language or null if no entries were found.
     *
     * Beispiel / Example:
     * $entries = $language->getEntries();
     *
     * @api
     */
    public function getEntries(): ?rex_yform_manager_collection
    {
        return $this->getRelatedCollection('entry');
    }

    /**
     * Gibt den Code der Sprache zurück.
     * Returns the code of the language.
     *
     * @return string|null Der Code der Sprache oder null, wenn kein Code gesetzt ist. / The code of the language or null if no code is set.
     *
     * Beispiel / Example:
     * $code = $language->getCode();
     *
     * @api
     */
    public function getCode(): ?string
    {
        return $this->getValue('code');
    }

    /**
     * Setzt den Code der Sprache.
     * Sets the code of the language.
     *
     * @param string $value Der neue Code der Sprache. / The new code of the language.
     *
     * @api
     */
    public function setCode(string $value): self
    {
        $this->setValue('code', $value);
        return $this;
    }

    /**
     * Gibt den Namen der Sprache zurück.
     * Returns the name of the language.
     *
     * @return string Der Name der Sprache. / The name of the language.
     *
     * Beispiel / Example:
     * $name = $language->getName();
     *
     * @api
     */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /**
     * Setzt den Namen der Sprache.
     * Sets the name of the language.
     *
     * @param string $value Der neue Name der Sprache. / The new name of the language.
     *
     * @api
     */
    public function setName(string $value): self
    {
        $this->setValue('name', $value);
        return $this;
    }
}
