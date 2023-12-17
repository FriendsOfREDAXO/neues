<?php

/* Tablesets aktualisieren */
rex_yform_manager_table_api::importTablesets(rex_file::get(rex_path::addon(rex_addon::get('neues')->getName(), 'install/tableset.json')));

if(!rex_media::get('neues_entry_fallback_image.png')) {

    rex_file::copy(rex_path::addon('neues', '/install/neues_entry_fallback_image.png'), rex_path::media('neues_entry_fallback_image.png'));
    $data = [];
    $data['title'] = 'Aktuelles - Fallback-Image';
    $data['category_id'] = 0;
    $data['file'] = [
        'name' => "neues_entry_fallback_image.png",
        'path' => rex_path::media('neues_entry_fallback_image.png'),
    ];

    rex_media_service::addMedia($data, false);
}

/* Cronjob installieren */
$cronjob = array_filter(rex_sql::factory()->getArray("SELECT * FROM rex_category WHERE `type` = 'rex_cronjob_neues_publish'"));
if(!$cronjob) {
    $query = rex_file::get(rex_path::addon('neues', 'install/rex_cronjob_neues_publish.sql'));
    rex_sql::factory()->setQuery($query);
}
