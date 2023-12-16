<?php

class rex_api_neues_rss extends rex_api_function
{
    protected $published = true;  // Aufruf aus dem Frontend erlaubt

    public function execute(): void
    {
        $domain_id = rex_request('domain_id', 'int', null);
        $lang_id = rex_request('lang_id', 'int', null);
        $category_id = rex_request('category_id', 'int', null);

        header('Content-Type: application/rss+xml; charset=utf-8');
        exit(self::getRssFeed(neues_entry::findOnline($category_id), $domain_id, $lang_id, 'rss.neues.xml'));
    }

    public static function getRssFeed(rex_yform_manager_collection $collection, $domain, $lang, $filename)
    {
        return self::createRssFeed($collection, $domain, $lang, $filename);
    }

    public static function createRssFeed(?rex_yform_manager_collection $collection = null, $domain = null, $lang = null, $filename = 'rss.neues.xml')
    {
        if (!$collection) {
            $collection = neues_entry::findOnline();
        }

        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;

        $rss = $xml->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $xml->appendChild($rss);

        $channel = $xml->createElement('channel');
        $rss->appendChild($channel);

        $head_title = $xml->createElement('title', rex::getServerName());
        $channel->appendChild($head_title);

        $head_description = $xml->createElement('description', '');
        $channel->appendChild($head_description);

        if ($lang) {
            $head_language = $xml->createElement('language', $lang->getCode());
            $channel->appendChild($head_language);
        }

        $head_link = $xml->createElement('link', rex::getServer());
        $channel->appendChild($head_link);

        foreach ($collection as $entry) {
            $item = $xml->createElement('item');
            $channel->appendChild($item);

            $item_title = $xml->createElement('title', htmlspecialchars($entry->getName()));
            $item->appendChild($item_title);

            $entry->setTeasertext($entry->getTeaser());

            $entry->setDescription($entry->getDescription());

            $item_link = $xml->createElement('link', $entry->getUrl());
            $item->appendChild($item_link);

            $item_pubDate = $xml->createElement('pubDate', date('r', strtotime($entry->getPublishDate())));
            $item->appendChild($item_pubDate);

            $item_guid = $xml->createElement('guid', $entry->getUuid());
            $item->appendChild($item_guid);
        }

        return $xml->save(rex_path::base($filename));
    }
}
