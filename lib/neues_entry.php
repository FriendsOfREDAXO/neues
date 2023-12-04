<?php

class neues_entry extends \rex_yform_manager_dataset
{
    private $categories;

    /** @api */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    public function setName(string $name): self
    {
        $this->setValue('name', $name);
        return $this;
    }

    /** @api */
    public function getAuthor(): string
    {
        return $this->getValue('author');
    }

    public function setAuthor(string $author): self
    {
        $this->setValue('author', $author);
        return $this;
    }

    /** @api */
    public function getDomain(): string
    {
        return $this->getValue('domain');
    }

    public function setDomain(string $domain): self
    {
        $this->setValue('domain', $domain);
        return $this;
    }

    /** @api */
    public function getTeaser(): string
    {
        return $this->getValue('teaser');
    }

    public function setTeaser(string $teaser): self
    {
        $this->setValue('teaser', $teaser);
        return $this;
    }

    /** @api */
    public function getCategories(): ?rex_yform_manager_collection
    {
        if (!$this->categories) {
            $this->categories = $this->getRelatedCollection('category_ids');
            return $this->categories;
        }
    }

    public function setCategories(?rex_yform_manager_collection $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    /** @api */
    public function getImage(): string
    {
        if ('' == $this->getValue('image')) {
            $this->image = rex_config::get('neues', 'default_thumbnail');
        } else {
            $this->image = $this->getValue('image');
        }
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->setValue('image', $image);
        return $this;
    }

    /** @api */
    public function getImages(): ?array
    {
        return array_filter(explode(',', $this->getValue('images')));
    }

    public function setImages(?array $images): self
    {
        $this->setValue('images', implode(',', $images));
        return $this;
    }

    /** @api */
    public function getMedia(): ?rex_media
    {
        return rex_media::get($this->getImage());
    }

    public function setMedia(?rex_media $media): self
    {
        if (null !== $media) {
            $this->setValue('image', $media->getFileName());
        } else {
            $this->setValue('image', null);
        }
        return $this;
    }

    /** @api */
    public function getDescriptionAsPlaintext(): string
    {
        return strip_tags($this->getValue('description'));
    }

    public function setDescriptionAsPlaintext(string $description): self
    {
        $this->setValue('description', $description);
        return $this;
    }

    /** @api */
    public function getDescription(): string
    {
        return $this->getValue('description');
    }

    public function setDescription(string $description): self
    {
        $this->setValue('description', $description);
        return $this;
    }

    /** @api */
    public function getExternalUrl(): ?string
    {
        return $this->getValue('url');
    }

    public function setExternalUrl(string $url): self
    {
        $this->setValue('url', $url);
        return $this;
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

    public function setExternalLabel(string $label): self
    {
        $this->setValue('url_label', $label);
        return $this;
    }

    /** @api */
    public function getPublishDate(): string
    {
        return $this->getValue('publishdate');
    }

    public function setPublishDate(string $publishdate): self
    {
        $this->setValue('publishdate', $publishdate);
        return $this;
    }

    /** @api */
    public function getFormattedPublishDate(string $format_date = IntlDateFormatter::FULL): string
    {
        return $this->getFormattedPublishDateTime([$format_date, IntlDateFormatter::NONE]);
    }

    /** @api */
    public function getFormattedPublishDateTime(string $format = [IntlDateFormatter::FULL, IntlDateFormatter::SHORT]): string
    {
        return rex_formatter::intlDateTime($this->getPublishDate(), $format);
    }

    /** @api */
    public function getStatus(): string
    {
        return $this->getValue('status');
    }

    public function setStatus(int $status): self
    {
        $this->setValue('status', $status);
        return $this;
    }

    public static function findOnline(): ?rex_yform_manager_collection
    {
        return self::query()->where('status', 1, '>=')->find();
    }

    public static function findByCategory(int $category_id, $status = 1): ?rex_yform_manager_collection
    {
        $query = self::query()->joinRelation('category_ids', 'c')->where('rex_neues_entry.status', $status, '>=')->where('c.id', $category_id);
        return $query->find();
    }

    /** @api */
    public function getUrl(string $profile = 'neues-entry-id'): string
    {
        if ($url = rex_getUrl(null, null, [$profile => $this->getId()])) {
            return $url;
        }
        return '';
    }

    public function setUrl(string $url): self
    {
        $this->setValue('url', $url);
        return $this;
    }
}
