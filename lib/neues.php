class neues {

    private function getRssFeed($collection = null, $domain = null, $lang = null)
    {
        if(!$collection) {
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

        if($lang) {
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

                $article->setTeasertext($entry->getTeaser());

                $item_link = $xml->createElement('link', $entry->getUrl());
                $item->appendChild($item_link);

                $item_pubDate = $xml->createElement('pubDate', ($entry->getPublishDateFormatted("D, d M Y H:i:s O"));
                $item->appendChild($item_pubDate);

                $item_guid = $xml->createElement('guid', $entry->getUuid());
                $item->appendChild($item_guid);
            }

        echo $xml->save('../rss.neues.xml');
    }
