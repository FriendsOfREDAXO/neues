<?php

class rex_api_neues_rss extends rex_api_function
{
    protected $published = true;  // Aufruf aus dem Frontend erlaubt

    public function execute(): void
    {
        $domain_id = rex_request('domain_id', 'int', null);
        $lang_id = rex_request('lang_id', 'int', null);
        $category_id = rex_request('category_id', 'int', null);

        if($category_id && $category = neues_category::get($category_id)) {
            /** @var neues_category $category */
            $collection = neues_entry::findOnline($category_id);
            $filename = 'rss.neues.' . rex_string::normalize($category->getName()) . '.xml';
        } else {
            $collection = neues_entry::findOnline();
            $filename = 'rss.neues.xml';
        }
        header('Content-Type: application/rss+xml; charset=utf-8');
        exit(self::getRssFeed($collection, $domain_id, $lang_id, $filename));
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
            /** @var neues_entry $entry */
            $item = $xml->createElement('item');
            $channel->appendChild($item);

            $item_title = $xml->createElement('title', htmlspecialchars($entry->getName()));
            $item->appendChild($item_title);

            $item_description = $xml->createElement('description', htmlspecialchars(strip_tags($entry->getDescription())));
            $item->appendChild($item_description);

            $item_link = $xml->createElement('link', rex::getServer() . $entry->getUrl());
            $item->appendChild($item_link);

            $item_pubDate = $xml->createElement('pubDate', date('r', strtotime($entry->getPublishDate())));
            $item->appendChild($item_pubDate);

            $item_guid = $xml->createElement('guid', self::guidv4(str_pad($entry->getId(), 16, '0', STR_PAD_LEFT)));
            $item->appendChild($item_guid);
        }

        $return = $xml->saveXML();
        $xml->save(rex_path::base($filename));
        return $return;
    }
    
    public static function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data ??= random_bytes(16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
