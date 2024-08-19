<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_article;
use rex_config;
use rex_file;
use rex_media;
use rex_media_service;
use rex_path;
use rex_sql;
use rex_yform_manager_table_api;

use function count;

$sql = rex_sql::factory();

/* Tablesets aktualisieren */
if (rex_addon::get('yform')->isAvailable()) {
    rex_yform_manager_table_api::importTablesets(rex_file::get(__DIR__ . '/install/tableset.json'));

    // Vorhandene leere UUID-Felder aktualisieren
    $sql->setQuery('UPDATE ' . rex::getTable('neues_author') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');
    $sql->setQuery('UPDATE ' . rex::getTable('neues_category') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');
    $sql->setQuery('UPDATE ' . rex::getTable('neues_entry') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');

    require_once __DIR__ . '/install/update_scheme.php';
}

if (null === rex_media::get('neues_entry_fallback_image.png')) {
    rex_file::copy(__DIR__ . '/install/neues_entry_fallback_image.png', rex_path::media('neues_entry_fallback_image.png'));
    $data = [];
    $data['title'] = 'Aktuelles - Fallback-Image';
    $data['category_id'] = 0;
    $data['file'] = [
        'name' => 'neues_entry_fallback_image.png',
        'path' => rex_path::media('neues_entry_fallback_image.png'),
    ];

    rex_media_service::addMedia($data, false);
}

/* Cronjob installieren */
if (rex_addon::get('cronjob')->isAvailable()) {
    $cronjobPublish = 'FriendsOfRedaxo\\Neues\\Cronjob\\Publish';

    /**
     * ggf Update von früheren Versionen mit dem alten Klassennamen
     * -> rex_cronjob_neues_publish ändern in FriendsOfRedaxo\Neues\Cronjob\Publish.
     */
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setValue('type', $cronjobPublish);
    $sql->setWhere('`type` = :class', [':class' => 'rex_cronjob_neues_publish']);
    $sql->update();

    /**
     * Fehlenden CronJob eintragen.
     */
    $sql = rex_sql::factory();
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setWhere('`type` = :class', [':class' => $cronjobPublish]);
    $sql->select();

    if (0 === $sql->getRows()) {
        $query = rex_file::get(__DIR__ . '/install/rex_cronjob_neues_publish.sql');
        rex_sql::factory()->setQuery($query);
    }
}

/* URL-Profile installieren */
if (rex_addon::get('url')->isAvailable()) {
    if (false === rex_config::get('neues', 'url_profile', false)) {
        $rex_neues_category = array_filter(rex_sql::factory()->getArray("SELECT * FROM rex_url_generator_profile WHERE `table_name` = '1_xxx_rex_neues_category'"));
        if (0 < count($rex_neues_category)) {
            $query = str_replace('999999', (string) rex_article::getSiteStartArticleId(), rex_file::get(__DIR__ . '/install/rex_url_profile_neues_category.sql'));
            rex_sql::factory()->setQuery($query);
        }
        $rex_neues_entry = array_filter(rex_sql::factory()->getArray("SELECT * FROM rex_url_generator_profile WHERE `table_name` = '1_xxx_rex_neues_entry'"));
        if (0 < count($rex_neues_entry)) {
            $query = str_replace('999999', (string) rex_article::getSiteStartArticleId(), rex_file::get(__DIR__ . '/install/rex_url_profile_neues_entry.sql'));
            rex_sql::factory()->setQuery($query);
        }
        /* URL-Profile wurden bereits einmal installiert, daher nicht nochmals installieren und Entwickler-Einstellungen respektieren */
        rex_config::set('neues', 'url_profile', true);
    }
}
