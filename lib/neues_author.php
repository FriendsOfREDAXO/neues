<?php

namespace FriendsOfRedaxo\neues;

use rex_user;
use rex_yform_manager_dataset;

/**
 * Klasse neues_author.
 *
 * Diese Klasse repräsentiert einen Autor in der News-Verwaltung.
 * Sie erbt von der rex_yform_manager_dataset Klasse.
 *
 * Class neues_author
 *
 * This class represents an author in the news management.
 * It inherits from the rex_yform_manager_dataset class.
 *
 *
 * $author = neues_author::create();
 * $author->setValue('name', 'Neuer Autor');
 * $author->save();
 */

class neues_author extends rex_yform_manager_dataset
{
    /* translate:neues_author_name */
    /**
     * Gibt den Namen des Autors zurück.
     * Returns the name of the author.
     *
     * @return string|null Der Name des Autors oder null, wenn kein Name gesetzt ist. / The name of the author or null if no name is set.
     *
     * Beispiel / Example:
     * $name = $author->getName();
     *
     * @api
     */
    public function getName(): ?string
    {
        return $this->getValue('name');
    }

    /**
     * Setzt den Namen des Autors.
     * Sets the name of the author.
     *
     * @param string $value Der neue Name des Autors. / The new name of the author.
     *
     * @api
     */
    public function setName(string $value): self
    {
        $this->setValue('name', $value);
        return $this;
    }

    /* translate:neues_author_nickname */
    /**
     * Gibt den Spitznamen des Autors zurück.
     * Returns the nickname of the author.
     *
     * @return string|null Der Spitzname des Autors oder null, wenn kein Spitzname gesetzt ist. / The nickname of the author or null if no nickname is set.
     *
     * Beispiel / Example:
     * $nickname = $author->getNickname();
     *
     * @api
     */
    public function getNickname(): ?string
    {
        return $this->getValue('nickname');
    }

    /**
     * Setzt den Spitznamen des Autors.
     * Sets the nickname of the author.
     *
     * @param string $value Der neue Spitzname des Autors. / The new nickname of the author.
     *
     * @api
     */
    public function setNickname(string $value): self
    {
        $this->setValue('nickname', $value);
        return $this;
    }

    /* translate:neues_author_text */
    /**
     * Gibt den Text des Autors zurück.
     * Returns the text of the author.
     *
     * @param bool $asPlaintext Wenn true, wird der Text ohne HTML-Tags zurückgegeben. / If true, the text is returned without HTML tags.
     * @return string|null Der Text des Autors oder null, wenn kein Text gesetzt ist. / The text of the author or null if no text is set.
     *
     * Beispiel / Example:
     * $text = $author->getText(true);
     *
     * @api
     */
    public function getText(bool $asPlaintext = false): ?string
    {
        if ($asPlaintext) {
            return strip_tags($this->getValue('text'));
        }
        return $this->getValue('text');
    }

    /**
     * Setzt den Text des Autors.
     * Sets the text of the author.
     *
     * @param string $value Der neue Text des Autors. / The new text of the author.
     *
     * @api
     */
    public function setText(string $value): self
    {
        $this->setValue('text', $value);
        return $this;
    }

    /* translate:neues_author_be_user_id */
    /**
     * Gibt die Benutzer-ID des Autors zurück.
     * Returns the user ID of the author.
     *
     * @return int|null Die Benutzer-ID des Autors oder null, wenn keine Benutzer-ID gesetzt ist. / The user ID of the author or null if no user ID is set.
     *
     * Beispiel / Example:
     * $beUserId = $author->getBeUserId();
     *
     * @api
     */
    public function getBeUserId(): ?int
    {
        return (int) $this->getValue('be_user_id');
    }

    /**
     * Setzt die Benutzer-ID des Autors.
     * Sets the user ID of the author.
     *
     * @param int $value Die neue Benutzer-ID des Autors. / The new user ID of the author.
     *
     * @api
     */
    public function setBeUserId(int $value): self
    {
        $this->setValue('be_user_id', $value);
        return $this;
    }

    /**
     * Gibt den Benutzer des Autors zurück.
     * Returns the user of the author.
     *
     * @return rex_user|null Der Benutzer des Autors oder null, wenn kein Benutzer gefunden wurde. / The user of the author or null if no user was found.
     *
     * Beispiel / Example:
     * $beUser = $author->getBeUser();
     *
     * @api
     */
    public function getBeUser(): ?rex_user
    {
        return rex_user::get($this->getBeUserId());
    }
}
