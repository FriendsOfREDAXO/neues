<?php

namespace FriendsOfRedaxo\Neues;

use rex_cronjob;
use rex_i18n;

class rex_cronjob_neues_publish extends rex_cronjob
{
    public function execute()
    {
        /* Collection von Neues-Einträgen, die noch nicht veröffentlicht sind, aber es sein sollten. (geplant) */
        $neues_entry_to_publish = Entry::query()->where('status', 0)->where('publishdate', date('Y-m-d'), '<')->find();
        $neues_entry_to_publish->setValue('status', 1);
        if (!$neues_entry_to_publish->save()) {
            $this->setMessage(sprintf(rex_i18n::msg('neues_entry_publish_error'), count($neues_entry_to_publish)));
            return false;
        }

        $this->setMessage(sprintf(rex_i18n::msg('neues_entry_publish_success'), count($neues_entry_to_publish)));
        return true;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('neues_entry_publish_cronjob');
    }

    public function getParamFields()
    {
        return [];
    }
}
