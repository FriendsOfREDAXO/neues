<?php

class neues_entry extends \rex_yform_manager_dataset
{
    private $categories;

    /** @api */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /** @api */
    public function getAuthor(): string
    {
        return $this->getValue('author');
    }

    /** @api */
    public function getDomain(): string
    {
        return $this->getValue('domain');
    }

    /** @api */
    public function getTeaser(): string
    {
        return $this->getValue('teaser');
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

    /** @api */
    public function getPublishDate(): string
    {
        return $this->getValue('publishdate');
    }

    /** @api */
    public function getFormattedPublishDate($format_date = IntlDateFormatter::FULL)
    {
        return $this->getFormattedPublishDateTime([$format_date, IntlDateFormatter::NONE]);
    }

    /** @api */
    public function getFormattedPublishDateTime($format = [IntlDateFormatter::FULL, IntlDateFormatter::SHORT])
    {
        return rex_formatter::intlDateTime($this->getPublishDate(), $format);
    }

    /** @api */
    public function getStatus(): string
    {
        return $this->getValue('status');
    }

    public static function findOnline()
    {
        return self::query()->where('status', 1, '>=')->find();
    }

    public static function findByCategory($category_id, $status = 1)
    {
        $query = self::query()->joinRelation('category_ids', 'c')->where('rex_neues_entry.status', $status, '>=')->where('c.id', $category_id);
        return $query->find();
    }

    /** @api */
    public function getUrl($profile = 'neues-entry-id'): string
    {
        if ($url = rex_getUrl(null, null, [$profile => $this->getId()])) {
            return $url;
        }
        return '';
    }
}
