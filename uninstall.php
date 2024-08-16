<?php

rex_config::removeNamespace('neues');
if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_table_api::removeTable(rex::getTable('neues_category'));
    rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry'));
    rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry_category_rel'));
    rex_yform_manager_table_api::removeTable(rex::getTable('neues_author'));
    rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry_lang'));
}
