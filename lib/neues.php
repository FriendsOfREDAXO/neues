<?php
class neues
{
    public static function getRssFeed($collection = null, $domain = null, $lang = null, $filename)
    {
        return self::createRssFeed($collection, $domain, $lang, $filename);
    }

    public static function createRssFeed($collection = null, $domain = null, $lang = null, $filename = 'rss.neues.xml')
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

        $head_description = $xml->createElement('description', "");
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
