<?php

namespace FriendsOfRedaxo\Neues;

use rex_yform_manager_collection;
use rex_yform_manager_dataset;

/**
 * Class EntryLang (ex. neues_entry_lang).
 *
 * Diese Klasse repräsentiert einen neuen Eintrag.
 * This class represents a new entry.
 *
 * Beispiel / Example:
 * $entry = EntryLang::get($id);
 *
 * @package rex_yform_manager_dataset
 */
class EntryLang extends rex_yform_manager_dataset
{
    // Single point of truth for field names
    public const string ID = 'id';
    public const string ENTRY = 'entry';
    public const string CODE = 'code';
    public const string NAME = 'name';

    public const array FIELD_CONFIG = [
        self::ID,
        self::ENTRY => [],
        self::CODE => [],
        self::NAME => [],
    ];

    /**
     * Gibt die News-Einträge der aktuellen Sprache zurück.
     * Returns the entries of the language.
     *
     * @return rex_yform_manager_collection<Entry> Die Einträge der Sprache oder null, wenn keine Einträge gefunden wurden. / The entries of the language or null if no entries were found.
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
     * @return string Der Code der Sprache oder '', wenn kein Code gesetzt ist. / The code of the language or '' if no code is set.
     *
     * Beispiel / Example:
     * $code = $language->getCode();
     *
     * @api
     */
    public function getCode(): string
    {
        if ($this->hasValue('code')) {
            return $this->getValue('code');
        }
        return '';
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
        if ($this->hasValue('name')) {
            return $this->getValue('name');
        }
        return '';
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
