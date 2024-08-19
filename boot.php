<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_api_function;
use rex_cronjob_manager;
use rex_csrf_token;
use rex_extension;
use rex_plugin;
use rex_url;
use rex_yform_manager_dataset;

use function count;

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
if (rex_plugin::get('yform', 'rest')->isAvailable()) {
    Api\Restful::init();
}

if (rex::isBackend()) {
    $addon = rex_addon::get('neues');
    $pages = $addon->getProperty('pages');

    /**
     * Individualiserte Liste für Enries.
     */
    rex_extension::register('YFORM_DATA_LIST', Entry::epYformDataList(...));

    /**
     * Plus(Add)-Button im Hauptmenü-Punkt des Addon bereitstellen.
     *
     * RexStan: Using $_REQUEST is forbidden, use rex_request::request() or rex_request() instead.
     * Kommentar: Für diese Nutzung ist keine rex-Alternative verfügbar
     * @phpstan-ignore-next-line
     */
    if (0 < count($_REQUEST)) {
        $_csrf_key = Entry::table()->getCSRFKey();

        $params = rex_csrf_token::factory($_csrf_key)->getUrlParams();

        $params['table_name'] = Entry::table()->getTableName(); // Tabellenname anpassen
        $params['rex_yform_manager_popup'] = '0';
        $params['func'] = 'add';

        $href = rex_url::backendPage('neues/entry', $params);

        $pages['neues']['title'] .= ' <a class="label label-primary tex-primary" style="position: absolute; right: 18px; top: 10px; padding: 0.2em 0.6em 0.3em; border-radius: 3px; color: white; display: inline; width: auto;" href="' . $href . '">+</a>';
        $addon->setProperty('pages', $pages);
    }
}
