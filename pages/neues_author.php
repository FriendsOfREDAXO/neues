<?php

class neues_author extends \rex_yform_manager_dataset
{
    /** @api */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /** @api */
    public function getNickname(): string
    {
        return $this->getValue('nickname');
    }

    /** @api */
    public function getEntries()
    {
        return $this->getRelatedDataset('entry_ids');
    }
}
