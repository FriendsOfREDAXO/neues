<?php

class neues_entry extends \rex_yform_manager_dataset
{
    private $publishdate;
    private $category;
    private $offer;
    private $image = '';
    private $url_label = '';

    public function getCategory()
    {
        $this->category = $this->getRelatedDataset('neues_category_id');
        return $this->category;
    }

    public function getTimezone($lat, $lng)
    {
        $neues_timezone = 'https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $lng . '&timestamp=' . time() . '&sensor=false';
        $neues_location_time_json = file_get_contents($neues_timezone);
        return $neues_location_time_json;
    }

    public function getImage(): string
    {
        if ('' == $this->image) {
            $this->image = rex_config::get('neues', 'default_thumbnail');
        }
        return $this->image;
    }

    public function getMedia()
    {
        return rex_media::get($this->image);
    }

    public function getDescriptionAsPlaintext(): string
    {
        return strip_tags($this->description);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUrlLabel(): string
    {
        if ('' == $this->url_label) {
            $this->url_label = rex_config::get('neues', 'default_url_label');
        }
        return $this->url_label;
    }

    public function getUid()
    {
        if ('' === $this->uid && '' === $this->getValue('uid')) {
            $this->uid = self::generateUuid($this->id);

            rex_sql::factory()->setQuery('UPDATE rex_neues_entry SET uid = :uid WHERE id = :id', [':uid' => $this->uid, ':id' => $this->getId()]);
        }
        return $this->uid;
    }

    private function getDateTime($date = null, $time = '00:00')
    {
        $time = explode(':', $time);
        $dateTime = new DateTime($date);
        $dateTime->setTime($time[0], $time[1]);

        return $dateTime;
    }

    public function getPublishDate()
    {
        $this->publishdate = $this->getDateTime($this->getValue('publishdate'));
        return $this->publishdate;
    }

    public function getName()
    {
        return $this->getValue('name');
    }
}
