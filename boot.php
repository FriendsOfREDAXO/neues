<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_api_function;
use rex_be_controller;
use rex_config;
use rex_cronjob_manager;
use rex_cronjob_neues_publish;
use rex_cronjob_neues_sync;
use rex_csrf_token;
use rex_extension;
use rex_extension_point;
use rex_plugin;
use rex_url;
use rex_yform_manager_dataset;
use rex_yform_manager_table;

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType(rex_cronjob_neues_publish::class);
    rex_cronjob_manager::registerType(rex_cronjob_neues_sync::class);
}

if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_dataset::setModelClass(
        'rex_neues_entry',
        Entry::class,
    );
    rex_yform_manager_dataset::setModelClass(
        'rex_neues_category',
        Category::class,
    );
    rex_yform_manager_dataset::setModelClass(
        'rex_neues_author',
        Author::class,
    );
    rex_yform_manager_dataset::setModelClass(
        'rex_neues_entry_lang',
        EntryLang::class,
    );
}

if (rex::isBackend() && 'neues/entry' == rex_be_controller::getCurrentPage() || 'yform/manager/data_edit' == rex_be_controller::getCurrentPage()) {
    rex_extension::register('OUTPUT_FILTER', static function (rex_extension_point $ep) {
        $suchmuster = 'class="###neues-settings-editor###"';
        $ersetzen = rex_config::get('neues', 'editor');
        $ep->setSubject(str_replace($suchmuster, $ersetzen, $ep->getSubject()));
    });
}

rex_api_function::register('neues_rss', neues_rss_api::class);

if (rex_plugin::get('yform', 'rest')->isAvailable() && !rex::isSafeMode()) {
    RestfulApi::init();
}

rex_extension::register('YFORM_DATA_LIST', static function ($ep) {
    if ('rex_neues_entry' == $ep->getParam('table')->getTableName()) {
        $list = $ep->getSubject();

        $list->setColumnFormat(
            'name',
            'custom',
            static function ($a) {
                $_csrf_key = rex_yform_manager_table::get('rex_neues_entry')->getCSRFKey();
                $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

                $params = [];
                $params['table_name'] = 'rex_neues_entry';
                $params['rex_yform_manager_popup'] = '0';
                $params['_csrf_token'] = $token['_csrf_token'];
                $params['data_id'] = $a['list']->getValue('id');
                $params['func'] = 'edit';

                return '<a href="' . rex_url::backendPage('neues/entry', $params) . '">' . $a['value'] . '</a>';
            },
        );
        $list->setColumnFormat(
            'neues_category_id',
            'custom',
            static function ($a) {
                $_csrf_key = rex_yform_manager_table::get('rex_neues_category')->getCSRFKey();
                $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

                $params = [];
                $params['table_name'] = 'rex_neues_category';
                $params['rex_yform_manager_popup'] = '0';
                $params['_csrf_token'] = $token['_csrf_token'];
                $params['data_id'] = $a['list']->getValue('id');
                $params['func'] = 'edit';

                $return = [];

                $category_ids = array_filter(array_map('intval', explode(',', $a['value'])));

                foreach ($category_ids as $category_id) {
                    /** @var neues_category $neues_category */
                    $neues_category = Category::get($category_id);
                    if ($neues_category) {
                        $return[] = '<a href="' . rex_url::backendPage('neues/category', $params) . '">' . $neues_category->getName() . '</a>';
                    }
                }
                return implode('<br>', $return);
            },
        );
    }
});

if (rex::isBackend() && rex_addon::get('neues') && rex_addon::get('neues')->isAvailable() && !rex::isSafeMode()) {
    $addon = rex_addon::get('neues');
    $pages = $addon->getProperty('pages');

    if ($_REQUEST) {
        $_csrf_key = rex_yform_manager_table::get('rex_neues_entry')->getCSRFKey();

        $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

        $params = [];
        $params['table_name'] = 'rex_neues_entry'; // Tabellenname anpassen
        $params['rex_yform_manager_popup'] = '0';
        $params['_csrf_token'] = $token['_csrf_token'];
        $params['func'] = 'add';

        $href = rex_url::backendPage('neues/entry', $params);

        $pages['neues']['title'] .= ' <a class="label label-primary tex-primary" style="position: absolute; right: 18px; top: 10px; padding: 0.2em 0.6em 0.3em; border-radius: 3px; color: white; display: inline; width: auto;" href="' . $href . '">+</a>';
        $addon->setProperty('pages', $pages);
    }
}
