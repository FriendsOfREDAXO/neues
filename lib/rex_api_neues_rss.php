<?php

class rex_api_neues_rss extends rex_api_function
{
    protected $published = true;  // Erlaubt den Aufruf aus dem Frontend

    public function execute()
    {
        $domain_id = rex_request('domain_id', 'int', null);
        $lang_id = rex_request('lang_id', 'int', null);
        $category_id = rex_request('category_id', 'int', null);

        if ($category_id && $category = neues_category::get($category_id)) {
            $collection = neues_entry::findOnline($category_id);
            $filename = 'rss.neues.' . rex_string::normalize($category->getName()) . '.xml';
        } else {
            $collection = neues_entry::findOnline();
            $filename = 'rss.neues.xml';
        }

        rex_response::cleanOutputBuffers();
        rex_response::sendContentType('application/xml; charset=utf-8');

        // RSS-Feed generieren und ausgeben
        echo self::getRssFeed($collection, $domain_id, $lang_id, $filename);
        exit();
    }

    public static function getRssFeed($collection, $domain, $lang, $filename)
    {
        return self::createRssFeed($collection, $domain, $lang, $filename);
    }

    public static function createRssFeed($collection, $domain, $lang, $filename = 'rss.neues.xml')
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');

        $channel = $xml->addChild('channel');
        $channel->addChild('title', rex::getServerName());
        # $channel->addChild('description', $description);
        $channel->addChild('link', rex::getServer());

        if ($lang) {
            $channel->addChild('language', $lang);
        }

        foreach ($collection as $entry) {
            $item = $channel->addChild('item');
            $item->addChild('title', htmlspecialchars($entry->getName()));
            $item->addChild('description', htmlspecialchars(strip_tags($entry->getDescription())));
            $item->addChild('link', rex::getServer() . $entry->getUrl());
            $item->addChild('pubDate', date('r', strtotime($entry->getPublishDate())));
            $item->addChild('guid', rex::getServer() . $entry->getUrl());
        }

        // Speichern und ausgeben des XML
        $xml->asXML(rex_path::base($filename));
        return $xml->asXML();
    }
}
