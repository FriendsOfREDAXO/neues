<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_api_function;
use rex_cronjob_manager;
use rex_cronjob_neues_publish;
use rex_cronjob_neues_sync;
use rex_csrf_token;
use rex_extension;
use rex_plugin;
use rex_url;
use rex_yform_manager_dataset;

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType(rex_cronjob_neues_publish::class);
    rex_cronjob_manager::registerType(rex_cronjob_neues_sync::class);
}

if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_dataset::setModelClass(
        rex::getTable('neues_entry'),
        Entry::class,
    );
    rex_yform_manager_dataset::setModelClass(
        rex::getTable('neues_category'),
        Category::class,
    );
    rex_yform_manager_dataset::setModelClass(
        rex::getTable('neues_author'),
        Author::class,
    );
    rex_yform_manager_dataset::setModelClass(
        rex::getTable('neues_entry_lang'),
        EntryLang::class,
    );
}

rex_api_function::register('neues_rss', neues_rss_api::class);

if (rex_plugin::get('yform', 'rest')->isAvailable() && !rex::isSafeMode()) {
    RestfulApi::init();
}

rex_extension::register('YFORM_DATA_LIST', Entry::epYformDataList(...));

if (rex::isBackend() && rex_addon::get('neues') && rex_addon::get('neues')->isAvailable() && !rex::isSafeMode()) {
    $addon = rex_addon::get('neues');
    $pages = $addon->getProperty('pages');

    if ($_REQUEST) {
        $_csrf_key = Entry::table()->getCSRFKey();

        $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

        $params = [];
        $params['table_name'] = Entry::table()->getTableName(); // Tabellenname anpassen
        $params['rex_yform_manager_popup'] = '0';
        $params['_csrf_token'] = $token['_csrf_token'];
        $params['func'] = 'add';

        $href = rex_url::backendPage('neues/entry', $params);

        $pages['neues']['title'] .= ' <a class="label label-primary tex-primary" style="position: absolute; right: 18px; top: 10px; padding: 0.2em 0.6em 0.3em; border-radius: 3px; color: white; display: inline; width: auto;" href="' . $href . '">+</a>';
        $addon->setProperty('pages', $pages);
    }
}
