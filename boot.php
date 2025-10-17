<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_api_function;
use rex_config;
use rex_cronjob_manager;
use rex_extension;
use rex_extension_point;
use rex_plugin;
use rex_yform_manager_dataset;

/**
 * Tabellen in YForm mit eigener Model-Class.
 */
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

/**
 * RSS-Fead via rex-api anbieten.
 */
rex_api_function::register('neues_rss', Api\Rss::class);

/**
 * Optionale Dienste:
 * - Cronjobs nur bereitstellen, wenn das Addon verfügbar ist
 * - REST-API nur aktivieren wenn das YForm-REST-Plugin aktiviert ist
 */
if (rex_addon::get('cronjob')->isAvailable()) {
    rex_cronjob_manager::registerType(Cronjob\Publish::class);
    rex_cronjob_manager::registerType(Cronjob\Sync::class);
}
rex_extension::register('PACKAGES_INCLUDED', static function () {
    if (rex_plugin::get('yform', 'rest')->isAvailable()) {
        Api\Restful::init();
    }
});

if (rex::isBackend()) {
    /**
     * Individualiserte Liste für Enries.
     */
    rex_extension::register('YFORM_DATA_LIST', Entry::epYformDataList(...));

    /**
     * Plus(Add)-Button im Hauptmenü-Punkt des Addon bereitstellen.
     */
    rex_extension::register('PAGES_PREPARED', Neues::epPagesPrepared(...));

    /**
     * Für die korrekte Editor auswahl.
     */
    rex_extension::register('OUTPUT_FILTER', static function (rex_extension_point $ep) {
        $suchmuster = 'class="###neues-settings-editor###"';
        $ersetzen = rex_config::get('neues', 'editor') ?? 'class="form-control"';
        $ep->setSubject(str_replace($suchmuster, $ersetzen, $ep->getSubject()));
    });
}
