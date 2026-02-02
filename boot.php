<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_api_function;
use rex_config;
use rex_cronjob_manager;
use rex_extension;
use rex_extension_point;
use rex_path;
use rex_plugin;
use rex_view;
use rex_yform;
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
 * RSS-Feed via rex-api anbieten.
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

if (version_compare(rex_addon::get('yform')->getVersion(), '5.0.0', '<')) {
    if (rex_plugin::get('yform', 'rest')->isAvailable()) {
        Api\Restful::init();
    }
} else {
    Api\Restful::init();
}

if (rex::isBackend()) {
    $uuidTables = [
        Entry::table()->getTableName(),
        Category::table()->getTableName(),
        Author::table()->getTableName(),
    ];

    /**
     * CSS für Custom Fields laden.
     */
    rex_view::addCssFile(rex_addon::get('neues')->getAssetsUrl('neues-fields.css'));

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

    /**
     * Ensure cloned datasets receive a fresh UUID to avoid duplicate keys.
     */
    rex_extension::register('YFORM_DATA_UPDATE', static function (rex_extension_point $ep) use ($uuidTables) {
        if ('clone' !== rex_request('func', 'string')) {
            return $ep->getSubject();
        }

        $dataset = $ep->getParam('data');
        if (!$dataset instanceof rex_yform_manager_dataset) {
            return $ep->getSubject();
        }

        $tableName = $dataset->getTable()->getTableName();
        if (!in_array($tableName, $uuidTables, true)) {
            return $ep->getSubject();
        }

        if (!$dataset->hasValue('uuid')) {
            return $ep->getSubject();
        }

        $newUuid = \rex_yform_value_uuid::guidv4();
        $dataset->setValue('uuid', $newUuid);

        $yform = $ep->getSubject();
        if ($yform instanceof rex_yform) {
            $yform->setFieldValue('uuid', [], $newUuid);
        }

        return $ep->getSubject();
    });
}

// Register custom YForm template path
rex_yform::addTemplatePath(rex_path::addon('neues', 'ytemplates'));
