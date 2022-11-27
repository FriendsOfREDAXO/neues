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
        return $this->getValue('publishdate');
    }
    public function getPublishDateTime()
    {
        return $this->getDateTime($this->getValue('publishdate'));
    }
    
    public static function formatDate($format_date = IntlDateFormatter::FULL, $format_time = IntlDateFormatter::SHORT, $lang = "de")
    {
        return datefmt_create($lang, $format_date, $format_time, null, IntlDateFormatter::GREGORIAN);
    }

    public function getFormattedPublishDate($format_date = IntlDateFormatter::FULL, $format_time = IntlDateFormatter::NONE, $lang = null)
    {
        return self::formatDate($format_date, $format_time)->format($this->getDateTime($this->getPublishDate(), $this->getStartTime()), $lang);
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
        self::query()->where("status", $status, ">=")->whereRaw("category_ids", "FIND_IN_SET(".$category_id.", `category_ids`)")->find();
    }
}
