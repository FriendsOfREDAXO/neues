<?php

namespace FriendsOfRedaxo\Neues\Api;

use FriendsOfRedaxo\Neues\Category;
use FriendsOfRedaxo\Neues\Entry;
use rex;
use rex_api_function;
use rex_api_result;
use rex_clang;
use rex_request;
use rex_response;
use rex_string;

class JsonFeed extends rex_api_function
{
    protected $published = true;  // Erlaubt den Aufruf aus dem Frontend

    /**
     * JSON Feed 1.1 API für News-Einträge
     * @return never
     * @api
     */
    public function execute(): rex_api_result
    {
        $domain_id = rex_request('domain_id', 'int', null);
        $lang_id = rex_request('lang_id', 'int', null);
        $category_id = rex_request('category_id', 'int', null);
        $limit = rex_request('limit', 'int', 50);
        $offset = rex_request('offset', 'int', 0);

        // Basis-Query für alle Filter
        $query = Entry::query()->where('status', Entry::STATUS_ONLINE);
        
        // Kategorie-Filter
        if (null !== $category_id) {
            $query->whereRaw('FIND_IN_SET(?, category_ids)', [$category_id]);
        }
        
        // Domain-Filter (falls Domain-IDs gesetzt sind)
        if (null !== $domain_id) {
            $query->whereRaw('(domain_ids = "" OR domain_ids IS NULL OR FIND_IN_SET(?, domain_ids))', [$domain_id]);
        }
        
        // Sprach-Filter
        if (null !== $lang_id) {
            $query->where('lang_id', $lang_id);
        }
        
        $collection = $query->orderBy('publishdate', 'DESC')->limit($limit)->offset($offset)->find();

        // Feed-Metadaten generieren
        $feed = $this->createFeedMetadata($category_id, $lang_id, $domain_id);
        
        // Items hinzufügen
        $feed['items'] = [];
        foreach ($collection as $entry) {
            $feed['items'][] = $this->createFeedItem($entry);
        }

        rex_response::cleanOutputBuffers();
        rex_response::sendContentType('application/json; charset=utf-8');

        echo json_encode($feed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Erstellt die Feed-Metadaten basierend auf Filtern
     */
    private function createFeedMetadata(?int $category_id, ?int $lang_id, ?int $domain_id): array
    {
        $title_parts = [rex::getServerName()];
        $description_parts = ['News-Feed'];
        
        // Kategorie-Info hinzufügen
        if (null !== $category_id) {
            $category = Category::get($category_id);
            if ($category) {
                $title_parts[] = $category->getName();
                $description_parts[] = $category->getName();
            }
        }
        
        // Sprach-Info hinzufügen
        if (null !== $lang_id && $lang_id > 0) {
            $lang = rex_clang::get($lang_id);
            if ($lang) {
                $title_parts[] = $lang->getName();
                $description_parts[] = $lang->getName();
            }
        }
        
        // Domain-Info hinzufügen
        if (null !== $domain_id) {
            $title_parts[] = 'Domain ' . $domain_id;
            $description_parts[] = 'Domain ' . $domain_id;
        }

        return [
            'version' => 'https://jsonfeed.org/version/1.1',
            'title' => implode(' | ', $title_parts),
            'description' => implode(' - ', $description_parts),
            'home_page_url' => rex::getServer(),
            'feed_url' => $this->getFeedUrl($category_id, $lang_id, $domain_id),
            'language' => $lang_id && $lang_id > 0 ? rex_clang::get($lang_id)?->getCode() : null,
            'favicon' => rex::getServer() . '/favicon.ico',
            'authors' => [
                [
                    'name' => rex::getServerName(),
                    'url' => rex::getServer()
                ]
            ]
        ];
    }

    /**
     * Erstellt ein JSON Feed Item aus einem News-Eintrag
     */
    private function createFeedItem(Entry $entry): array
    {
        $item = [
            'id' => (string) $entry->getId(),
            'title' => $entry->getName(),
            'content_html' => $entry->getDescription(),
            'content_text' => strip_tags($entry->getDescription()),
            'url' => rex::getServer() . $entry->getUrl(),
            'date_published' => date('c', strtotime($entry->getPublishDate()))
        ];

        // Autor hinzufügen falls vorhanden
        if ($entry->getAuthor()) {
            $item['authors'] = [
                [
                    'name' => $entry->getAuthor()->getName()
                ]
            ];
        }

        // Kategorien als Tags hinzufügen
        if ($entry->getCategories()) {
            $item['tags'] = [];
            foreach ($entry->getCategories() as $category) {
                $item['tags'][] = $category->getName();
            }
        }

        // Teaser-Bild hinzufügen falls vorhanden
        if (method_exists($entry, 'getImage') && $entry->getImage()) {
            $item['image'] = rex::getServer() . '/media/' . $entry->getImage();
        }

        // Update-Datum hinzufügen falls verfügbar
        if (method_exists($entry, 'getUpdateDate') && $entry->getUpdateDate()) {
            $item['date_modified'] = date('c', strtotime($entry->getUpdateDate()));
        }

        return $item;
    }

    /**
     * Generiert die Feed-URL mit aktuellen Parametern
     */
    private function getFeedUrl(?int $category_id, ?int $lang_id, ?int $domain_id): string
    {
        $params = ['rex-api-call' => 'neues_json'];
        
        if (null !== $category_id) {
            $params['category_id'] = $category_id;
        }
        
        if (null !== $lang_id) {
            $params['lang_id'] = $lang_id;
        }
        
        if (null !== $domain_id) {
            $params['domain_id'] = $domain_id;
        }

        return rex::getServer() . '/?' . http_build_query($params);
    }
}