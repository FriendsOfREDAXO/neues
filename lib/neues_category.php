<?php

class neues_category extends \rex_yform_manager_dataset
{
    /** @api */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    public function setName(string $string): self
    {
        $this->setValue('name', $string);
        return $this;
    }

    /** @api */
    public function getEntries(): ?rex_yform_manager_collection
    {
        return $this->getRelatedDataset('entry_ids');
    }

    public function setEntries(rex_yform_manager_collection $entries): self
    {
        $this->setValue('entry_ids', $entries);
        return $this;
    }
}
