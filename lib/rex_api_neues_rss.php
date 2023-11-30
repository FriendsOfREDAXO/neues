<?php

class rex_api_neues_rss extends rex_api_function
{
    protected $published = true;  // Aufruf aus dem Frontend erlaubt

    public function execute(): void
    {
        $category_id = rex_request('category_id', 'int', 0);
        $event_id = rex_request('domain_id', 'int', 0);
        $lang_id = rex_request('lang_id', 'int', 0);

        header('Content-Type: application/rss+xml; charset=utf-8');
        exit(self::getRssFeed(neues_entry::findOnline()));
    }

    public static function getRssFeed(array $collection, string $domain, string $lang, string $filename): array
    {
        return self::createRssFeed($collection, $domain, $lang, $filename);
    }

    public static function createRssFeed(array $collection = null, string $domain = null, string $lang = null, string $filename = 'rss.neues.xml'): void
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

            $item_pubDate = $xml->createElement('pubDate', date('r', strottime($entry->getPublishDate())));
            $item->appendChild($item_pubDate);

            $item_guid = $xml->createElement('guid', $entry->getUuid());
            $item->appendChild($item_guid);
        }

        return $xml->save(rex_path::base($filename));
    }
}
