<?php

class neues_category extends \rex_yform_manager_dataset
{
    /** @api */
    public function getName() :string
    {
        return $this->getValue('name');
    }
    /** @api */
    public function getEntries()
    {
        return $this->getRelatedDataset('entry_ids');
    }
}
