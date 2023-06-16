<?php

rex_config::removeNamespace('neues');
if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_table_api::removeTable('rex_neues_category');
    rex_yform_manager_table_api::removeTable('rex_neues_entry');
    rex_yform_manager_table_api::removeTable('rex_neues_entry_lang');
    rex_yform_manager_table_api::removeTable('rex_neues_entry_category_rel');
}
