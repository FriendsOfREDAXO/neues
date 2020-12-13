<?php

/* Tablesets aktualisieren */

rex_yform_manager_table_api::importTablesets(rex_file::get(rex_path::addon($this->name, 'install/tableset.json')));

$modules = scandir(rex_path::addon('neues').'module');

foreach ($modules as $module) {
    if ('.' == $module || '..' == $module) {
        continue;
    }
    $module_array = json_decode(rex_file::get(rex_path::addon('neues').'module/'.$module), 1);

    rex_sql::factory()->setDebug(0)->setTable('rex_module')
    ->setValue('name', $module_array['name'])
    ->setValue('key', $module_array['key'])
    ->setValue('input', $module_array['input'])
    ->setValue('output', $module_array['output'])
    ->setValue('createuser', '')
    ->setValue('updateuser', 'neues')
    ->setValue('createdate', date('Y-m-d H:i:s'))
    ->setValue('updatedate', date('Y-m-d H:i:s'))
    ->insertOrUpdate();
}
