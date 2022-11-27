<?php

class neues_entry extends \rex_yform_manager_dataset
{
    private $publishdate;
    private $category;
    private $categories;
    private $offer;
    private $image = '';
    private $externalUrl = '';
    private $externalLabel = '';

    public function getName()
    {
        return $this->getValue('name');
    }

    public function getAuthor()
    {
        return $this->getValue('author');
    }
    
    public function getDomain()
    {
        return $this->getValue('domain');
    }

    public function getTeaser()
    {
        return $this->getValue('teaser');
    }

    public function getCategory()
    {
        $this->category = $this->getRelatedDataset('category_ids');
        return $this->category;
    }

    public function getCategories()
    {
        if (!$this->categories) {
            $this->categories = $this->getRelatedCollection('category_ids');
            return $this->categories;
        }
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
        } else {
            $this->image = $this->getValue('image');
        }
        return $this->image;
    }

    public function getMedia()
    {
        return rex_media::get($this->getValue('image'));
    }

    public function getDescriptionAsPlaintext(): string
    {
        return strip_tags($this->getValue('description'));
    }

    public function getDescription(): string
    {
        return $this->getValue('description');
    }

    public function getExternalUrl(): string
    {
        return $this->getValue('external_url');
    }

    public function getExternalLabel(): string
    {
        if ('' == $this->externalLabel) {
            $this->externalLabel = rex_config::get('neues', 'default_url_label');
        } else {
            $this->getValue('externalLabel');
        }
        return $this->externalLabel;
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
        return $this->getDateTime($this->getValue('publishdate'));
    }

    public function getStatus()
    {
        return $this->getValue('status');
    }

    public static function findOnline()
    {
        self::query()->where("status", "0", ">")->find();
    }
    public static function findByCategory($category_id, $status = 1)
    {
        self::query()->where("status", "0", ">")->whereRaw("category_ids", "FIND_IN_SET(".$category_id.", `category_ids`)")->find();
    }
}
