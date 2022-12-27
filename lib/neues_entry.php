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

    /** @api */
    public function getName() :string
    {
        return $this->getValue('name');
    }

/** @api */
    public function getAuthor() :string
    {
        return $this->getValue('author');
    }
    
/** @api */
    public function getDomain() :string
    {
        return $this->getValue('domain');
    }

/** @api */
    public function getTeaser() :string
    {
        return $this->getValue('teaser');
    }

/** @api */
    public function getCategory()
    {
        $this->category = $this->getRelatedDataset('category_ids');
        return $this->category;
    }

/** @api */
    public function getCategories()
    {
        if (!$this->categories) {
            $this->categories = $this->getRelatedCollection('category_ids');
            return $this->categories;
        }
    }

/** @api */
    public function getImage(): string
    {
        if ('' == $this->images) {
            $this->image = rex_config::get('neues', 'default_thumbnail');
        } else {
            $this->image = $this->getValue('images');
        }
        return $this->image;
    }

/** @api */
    public function getMedia()
    {
        return rex_media::get($this->getValue('image'));
    }

/** @api */
    public function getDescriptionAsPlaintext(): string
    {
        return strip_tags($this->getValue('description'));
    }

/** @api */
    public function getDescription(): string
    {
        return $this->getValue('description');
    }

/** @api */
    public function getExternalUrl(): ?string
    {
        return $this->getValue('url');
    }

/** @api */
    public function getExternalLabel(): string
    {
        if ('' == $this->externalLabel) {
            $this->externalLabel = rex_config::get('neues', 'default_url_label');
        } else {
            $this->getValue('url_Label');
        }
        return $this->externalLabel;
    }

    private function getDateTime(string $date = null, string $time = '00:00')
    {
        $time = explode(':', $time);
        $dateTime = new DateTime($date);
        $dateTime->setTime($time[0], $time[1]);

        return $dateTime;
    }

/** @api */
    public function getPublishDate() :string
    {
        return $this->getValue('publishdate');
    }
/** @api */
    public function getPublishDateTime()
    {
        return $this->getDateTime($this->getValue('publishdate'));
    }
    
    public static function formatDate($format_date = IntlDateFormatter::FULL, $format_time = IntlDateFormatter::SHORT, $lang = "de")
    {
        return datefmt_create($lang, $format_date, $format_time, null, IntlDateFormatter::GREGORIAN);
    }

/** @api */
    public function getFormattedPublishDate($format_date = IntlDateFormatter::FULL, $format_time = IntlDateFormatter::NONE, $lang = null)
    {
        return self::formatDate($format_date, $format_time)->format($this->getDateTime($this->getPublishDate(), '00:00', $lang));
    }

/** @api */
    public function getStatus() :string
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
    
/** @api */
    public function getUrl() :string
    {
        if($url = rex_getUrl(null, null, ["neues-entry-id" => $this->getId()])) {
            return $url;
        }
        return '';
    }
}
