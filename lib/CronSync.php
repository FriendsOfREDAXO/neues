<?php

use FriendsOfRedaxo\Neues\Author;
use FriendsOfRedaxo\Neues\Category;
use FriendsOfRedaxo\Neues\Entry;

class rex_cronjob_neues_sync extends rex_cronjob
{
    private $rest_urls = ['category' => '/rest/neues/category/5.0.0/',
        'author' => '/rest/neues/author/5.0.0/',
        'entry' => '/rest/neues/entry/5.0.0/'];

    public function execute()
    {
        $data = [];

        foreach ($this->rest_urls as $type => $url) {
            $url = $this->getParam('url') . $url;
            $token = $this->getParam('token');
            $status = $this->getParam('status');

            $socket = rex_socket::factoryUrl($url);
            $socket->addHeader('token', $token);
            /** @var rex_socket_response $response */
            $response = $socket->doGet();

            if (!$response->isOk()) {
                $this->setMessage(sprintf(rex_i18n::msg('neues_entry_sync_error'), $response->getStatusCode()));
                return false;
            }

            $data[$type] = json_decode($response->getBody(), true);
        }

        if (isset($data['category']['data'])) {
            foreach ($data['category']['data'] as $category) {
                $category = $category['attributes'];

                // Überprüfe, ob UUID bereits in der Datenbank vorhanden ist
                $neues_category = Category::query()->where('uuid', $category['uuid'])->findOne();
                if (null === $neues_category) {
                    $neues_category = Category::create();
                }

                $neues_category->setValue('uuid', $category['uuid']);
                $neues_category->setValue('name', $category['name']);
                $neues_category->setValue('image', $category['image']);
                $neues_category->setValue('status', $status);
                $neues_category->setValue('createdate', $category['createdate']);
                $neues_category->setValue('createuser', 'cronjob');
                $neues_category->setValue('updatedate', $category['updatedate']);
                $neues_category->setValue('updateuser', 'cronjob');
                $neues_category->save();
            }
        }

        if (isset($data['author']['data'])) {
            foreach ($data['author']['data'] as $author) {
                $author = $author['attributes'];

                // Überprüfe, ob UUID bereits in der Datenbank vorhanden ist
                $neues_author = Author::query()->where('uuid', $author['uuid'])->findOne();
                if (null === $neues_author) {
                    $neues_author = Author::create();
                }

                $neues_author->setValue('uuid', $author['uuid']);
                $neues_author->setValue('name', $author['name']);
                $neues_author->setValue('nickname', $author['nickname']);
                $neues_author->setValue('text', $author['text']);
                $neues_author->save();
            }
        }

        foreach ($data['entry']['data'] as $entry) {
            $entry = $entry['attributes'];
            // Überprüfe, ob UUID bereits in der Datenbank vorhanden ist
            $neues_entry = Entry::query()->where('uuid', $entry['uuid'])->findOne();
            if (null === $neues_entry) {
                $neues_entry = Entry::create();
            }

            $neues_entry->setValue('uuid', $entry['uuid']);
            $neues_entry->setValue('name', $entry['name']);
            $neues_entry->setValue('teaser', $entry['teaser']);
            $neues_entry->setValue('description', $entry['description']);
            $neues_entry->setValue('url', $entry['url']);
            $neues_entry->setValue('image', $entry['image']);
            $neues_entry->setValue('images', $entry['images']);
            // $neues_entry->setValue('lang_id', $entry['lang_id']);
            // $neues_entry->setValue('category_id', $entry['category_id']);
            // $neues_entry->setValue('author_id', $entry['author_id']);
            $neues_entry->setValue('status', $status);
            $neues_entry->setValue('publishdate', $entry['publishdate']);
            $neues_entry->setValue('domain_ids', 0);
            $neues_entry->setValue('createuser', 'cronjob');
            $neues_entry->setValue('updateuser', 'cronjob');
            $neues_entry->setValue('createdate', $entry['createdate']);
            $neues_entry->setValue('updatedate', $entry['updatedate']);
            $neues_entry->save();
        }
        $this->setMessage(sprintf(rex_i18n::msg('neues_entry_sync_success'), $response->getStatusCode()));
        return true;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('neues_entry_sync_cronjob');
    }

    public function getParamFields()
    {
        $fields = [
            [
                'name' => 'url',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_url'),
                'type' => 'text',
            ],
            [
                'name' => 'token',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_token'),
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'label' => rex_i18n::msg('neues_entry_sync_cronjob_status'),
                'type' => 'text',
            ],
        ];

        return $fields;
    }
}
