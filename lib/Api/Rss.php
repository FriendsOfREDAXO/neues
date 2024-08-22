<?php

namespace FriendsOfRedaxo\Neues\Api;

use FriendsOfRedaxo\Neues\Category;
use FriendsOfRedaxo\Neues\Entry;
use rex;
use rex_api_function;
use rex_api_result;
use rex_clang;
use rex_path;
use rex_response;
use rex_string;
use rex_yform_manager_collection;
use SimpleXMLElement;

class Rss extends rex_api_function
{
    protected $published = true;  // Erlaubt den Aufruf aus dem Frontend

    /**
     * @return never
     * @api
     */
    public function execute(): rex_api_result
    {
        $domain_id = rex_request('domain_id', 'int', null);
        $lang_id = rex_request('lang_id', 'int', null);
        $category_id = rex_request('category_id', 'int', null);

        $category = null;
        if (null !== $category_id) {
            $category = Category::get($category_id);
        }

        if (null !== $category) {
            $collection = Entry::findOnline($category_id);
            $filename = 'rss.neues.' . rex_string::normalize($category->getName()) . '.xml';
            $description = 'RSS-FEED: ' . rex::getServerName() . ' | ' . rex_escape($category->getName());
        } else {
            $collection = Entry::findOnline();
            $filename = 'rss.neues.xml';
            $description = 'RSS-FEED: ' . rex::getServerName();
        }

        rex_response::cleanOutputBuffers();
        rex_response::sendContentType('application/xml; charset=utf-8');

        // RSS-Feed generieren und ausgeben
        echo self::getRssFeed($collection, $domain_id, $lang_id, $description, $filename);
        exit;
    }

    /**
     * @param rex_yform_manager_collection<Entry> $collection
     * @api
     */
    public static function getRssFeed(rex_yform_manager_collection $collection, string $domain, int $lang, string $description, string $filename): string|bool
    {
        return self::createRssFeed($collection, $domain, $lang, $description, $filename);
    }

    /**
     * @api
     */
    public static function joinUrls(string $url1, string $url2): string
    {
        return rtrim($url1, '/') . '/' . ltrim($url2, '/');
    }

    /**
     * @param rex_yform_manager_collection<Entry> $collection
     * @api
     */
    public static function createRssFeed(rex_yform_manager_collection $collection, int $domain_id, int $lang, string $description, string $filename = 'rss.neues.xml'): string|bool
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');

        $channel = $xml->addChild('channel');
        $channel->addChild('title', rex::getServerName());
        $channel->addChild('description', $description);
        $channel->addChild('link', rex::getServer());

        if ($lang > 0) {
            $channel->addChild('language', rex_clang::get($lang)->getCode());
        }

        foreach ($collection as $entry) {
            $item = $channel->addChild('item');
            $item->addChild('title', htmlspecialchars($entry->getName()));
            $item->addChild('description', htmlspecialchars(strip_tags($entry->getDescription())));
            $item->addChild('link', self::joinUrls(rex::getServer(), $entry->getUrl()));
            $item->addChild('pubDate', date('r', strtotime($entry->getPublishDate())));
            $item->addChild('guid', self::joinUrls(rex::getServer(), $entry->getUrl()))->addAttribute('isPermaLink', 'true');
        }

        // Speichern und ausgeben des XML
        $xml->asXML(rex_path::base($filename));
        return $xml->asXML();
    }
}
