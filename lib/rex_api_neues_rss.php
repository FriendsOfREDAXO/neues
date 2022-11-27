<?php

class rex_api_neues_rss extends rex_api_function
{
    protected $published = true;  // Aufruf aus dem Frontend erlaubt

    public function execute()
    {
        $category_id = rex_request('category_id', 'int', 0);
        $event_id = rex_request('domain_id', 'int', 0);
        $lang_id = rex_request('lang_id', 'int', 0);

        header('Content-Type: application/rss+xml; charset=utf-8');
        exit(neues::getRssFeed(neues_enty::findOnline()));
    }
}
