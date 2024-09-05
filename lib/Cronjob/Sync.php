<?php

namespace FriendsOfRedaxo\Neues\Cronjob;

use FriendsOfRedaxo\Neues\Author;
use FriendsOfRedaxo\Neues\Category;
use FriendsOfRedaxo\Neues\Entry;
use rex;
use rex_cronjob;
use rex_i18n;
use rex_media;
use rex_media_service;
use rex_path;
use rex_socket;
use rex_sql;
use rex_string;

class Sync extends rex_cronjob
{
    private static $ENDPOINT = '/rest/neues/entry/5.0.0/';

    private $counter = [
        'category' => ['created' => 0, 'updated' => 0],
        'author' => ['created' => 0, 'updated' => 0],
        'entry' => ['created' => 0, 'updated' => 0],
    ];

    public function execute()
    {
        $url = rtrim($this->getParam('url'), '/') . self::$ENDPOINT;
        $data = [];

        /* Query zusammenstellen */
        $query = [];
        if ((int) $this->getParam('x_per_page') > 0) {
            $query[] = 'per_page=' . $this->getParam('x_per_page');
        }
        $query[] = 'order[updatedate]=desc';
        $socket_url = $url . '?' . implode('&', $query);
        $token = $this->getParam('token');

        /* Socket erstellen und Daten abrufen */
        $socket = rex_socket::factoryUrl($socket_url);
        $socket->addHeader('token', $token);
        /** @var rex_socket_response $response */
        $response = $socket->doGet();

        if (!$response->isOk()) {
            $this->setMessage(sprintf(rex_i18n::msg('neues_entry_sync_error'), $response->getStatusCode()));
            return false;
        }

        $data = json_decode($response->getBody(), true);

        $entries = $data['data'];

        foreach ($entries as $entry) {
            $this->createEntry($entry);
        }

        $this->setMessage(sprintf(rex_i18n::msg('neues_entry_sync_cronjob_success'), $this->counter['entry']['created'] + $this->counter['entry']['updated'], $this->counter['entry']['created'], $this->counter['entry']['updated']));
        return true;
    }

    public function createEntry(array $current)
    {
        $entry_data = $current['attributes'];

        $entry = Entry::query()->where('uuid', $entry_data['uuid'])->findOne();
        if (null === $entry) {
            $entry = Entry::create();
            $entry->setValue('uuid', $entry_data['uuid']);
            ++$this->counter['entry']['created'];
        } else {
            ++$this->counter['entry']['updated'];
        }

        /* Kategorien abrufen und speichern */
        $target_category_ids = [];
        if ($this->getParam('neues_category_id') > 0 && Category::get($this->getParam('neues_category_id'))) {
            $target_category_ids[] = $this->getParam('neues_category_id');
        } else {
            $categories = $current['relationships']['category_ids']['data'];

            foreach ($categories as $category) {
                $target_category = $this->createCategory($category['attributes']);
                if ($target_category) {
                    $target_category_ids[] = $target_category->getId();
                }
            }
        }
        $entry->setValue('category_ids', implode(',', $target_category_ids));
        /* / Kategorien abrufen und speichern */

        /* Autor abrufen und speichern */
        $author = $current['relationships']['author']['data'];
        if ($author) {
            $target_author = $this->createAuthor($author['attributes']);
            if ($target_author) {
                $entry->setValue('author_id', $target_author->getId());
            }
        }
        /* / Autor abrufen und speichern */

        /* Titelbild abrufen und speichern */
        $updated_image = '';
        $targetname = $entry_data['uuid'] . '_' . $entry_data['image'];
        if ($this->createMedia($entry_data['image'])) {
            $updated_image = $targetname;
        }
        $entry->setValue('image', $updated_image);
        /* / Titelbild abrufen und speichern */

        /* Galerie-Bilder abrufen und speichern */
        $images = array_filter(explode(',', $entry_data['images']));
        $updated_images = [];
        foreach ($images as $image) {
            $targetname = $entry_data['uuid'] . '_' . $image;
            if ($this->createMedia($image, $targetname)) {
                $updated_images[] = $targetname;
            }
        }
        $entry->setValue('images', implode(',', $updated_images));
        /* / Galerie-Bilder abrufen und speichern */

        $entry->setValue('name', $entry_data['name']);
        $entry->setValue('teaser', $entry_data['teaser']);
        $entry->setValue('description', $entry_data['description']);
        $entry->setValue('url', $entry_data['url']);
        // $entry->setValue('lang_id', $entry['lang_id']);
        $entry->setValue('publishdate', $entry_data['publishdate']);
        $entry->setValue('domain_ids', 0);
        $entry->setValue('createuser', 'neues_sync_cronjob');
        $entry->setValue('updateuser', 'neues_sync_cronjob');
        $entry->setValue('createdate', $entry_data['createdate']);
        $entry->setValue('updatedate', $entry_data['updatedate']);
        $entry->save();
    }

    public function createCategory($current)
    {
        $category = Category::query()->where('uuid', $current['uuid'])->findOne();
        if (null === $category) {
            $category = Category::create();
            $category->setValue('uuid', $current['uuid']);
            ++$this->counter['category']['created'];
        } else {
            ++$this->counter['category']['updated'];
        }

        $category->setValue('name', $current['name']);
        $category->setValue('image', $current['image']);
        $category->setValue('status', $current['status']);
        $category->setValue('createdate', $current['createdate']);
        $category->setValue('createuser', 'neues_sync_cronjob');
        $category->setValue('updatedate', $current['updatedate']);
        $category->setValue('updateuser', 'neues_sync_cronjob');
        $category->save();
        return $category;
    }

    public function createAuthor(array $current)
    {
        // Überprüfe, ob UUID bereits in der Datenbank vorhanden ist
        $author = Author::query()->where('uuid', $current['uuid'])->findOne();
        if (null === $author) {
            $author = Author::create();
        }

        $author->setValue('uuid', $current['uuid']);
        $author->setValue('name', $current['name']);
        $author->setValue('nickname', $current['nickname']);
        $author->setValue('text', $current['text']);
        $author->save();
        return $author;
    }

    public function createMedia(string $filename, ?string $prefix = null): bool
    {
        $targetname = rex_string::normalize($prefix) . $filename;
        if ('' === $filename) {
            return false;
        }

        if (rex_media::get($targetname)) {
            return true;
        }

        $socket = rex_socket::factoryUrl($this->getParam('url') . '/media/' . $filename);
        /** @var rex_socket_response $response */
        $response = $socket->doGet();

        if ($response->isOk()) {
            $cache_filepath = rex_path::addonCache('neues', 'cronjob/' . $targetname);
            $response->writeBodyTo($cache_filepath);
            /* Überprüfe, ob die Datei auf dem Dateisystem vorhanden ist */
            return rex_media_service::addMedia([
                'category_id' => $this->getParam('media_category_id'),
                'title' => $filename,
                'createuser' => 'neues_sync_cronjob',
                'file' => ['name' => $targetname,
                    'path' => $cache_filepath]]) ? true : false;
        }
        return true;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('neues_entry_sync_cronjob');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getParamFields()
    {
        $media_categories = rex_sql::factory()->getArray('SELECT id, name FROM ' . rex::getTable('media_category'));
        $media_category_options = ['' => 'Root'];
        foreach ($media_categories as $media_category) {
            $media_category_options[$media_category['id']] = $media_category['name'];
        }

        $neues_categories = Category::getAll();
        $neues_category_options = ['' => 'Original'];
        foreach ($neues_categories as $neues_category) {
            $neues_category_options[$neues_category->getId()] = '📁 ' . $neues_category->getName();
        }
        $fields = [
            [
                'name' => 'url',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_url'),
                'type' => 'text',
                'attributes' => ['required' => 'required', 'type' => 'url'],
            ],
            [
                'name' => 'token',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_token'),
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_status'),
                'type' => 'select',
                'options' => ['1' => '🟢 Veröffentlichen', '0' => '🟡 Entwurf'],
            ],
            [
                'name' => 'media_category_id',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_media_category_id'),
                'type' => 'select',
                'options' => $media_category_options,
            ],
            [
                'name' => 'neues_category_id',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_neues_category_id'),
                'type' => 'select',
                'options' => $neues_category_options,
            ],
            [
                /* Wie viele Einträge sollen abgerufen werden */
                'name' => 'x_per_page',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_x_per_page'),
                'type' => 'text',
                'attributes' => ['type' => 'number', 'min' => 1],
            ],
        ];

        return $fields;
    }
}
