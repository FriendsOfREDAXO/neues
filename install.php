<?php

namespace FriendsOfRedaxo\Neues;

use FriendsOfRedaxo\Neues\Cronjob\Publish;
use rex;
use rex_addon;
use rex_config;
use rex_file;
use rex_media;
use rex_media_service;
use rex_path;
use rex_sql;
use rex_yform_manager_table_api;
use Url\Cache;
use Url\Profile;

/** @var rex_addon $this */

$sql = rex_sql::factory();

/**
 * Tablesets aktualisieren
 * - Datenbanktabellen anlegen bzw. das Schema aktualisieren
 * - YForm-Tablesets eintragen bzw. aktualisieren (soweit das möglich ist)
 * - bei vorhandenen Datensätzen ggf. leere UUIDs füllen.
 */
$this->includeFile(__DIR__ . '/install/update_scheme.php');

rex_yform_manager_table_api::importTablesets(rex_file::get(__DIR__ . '/install/tableset.json', '[]'));

$sql->setQuery('UPDATE ' . rex::getTable('neues_author') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');
$sql->setQuery('UPDATE ' . rex::getTable('neues_category') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');
$sql->setQuery('UPDATE ' . rex::getTable('neues_entry') . ' SET uuid = uuid() WHERE uuid IS NULL OR uuid = ""');

/**
 * Fallback-Image bereitstellen falls noch nicht in der Mediathek.
 */
$fallbackImage = 'neues_entry_fallback_image.png';
if (null === rex_media::get($fallbackImage)) {
    rex_file::copy(__DIR__ . '/install/' . $fallbackImage, rex_path::media($fallbackImage));
    $data = [];
    $data['title'] = 'Aktuelles - Fallback-Image';
    $data['category_id'] = 0;
    $data['file'] = [
        'name' => $fallbackImage,
        'path' => rex_path::media($fallbackImage),
    ];

    rex_media_service::addMedia($data, false);
}

/**
 * für nachfolgende $this->includeFile.
 */
$subScriptParams = ['sql' => $sql, 'installUser' => 'neues'];

/**
 * Optional: Cronjob installieren falls das Cronjob-Addon aktiviert ist.
 */
if (rex_addon::get('cronjob')->isAvailable()) {
    /**
     * ggf Update von früheren Versionen mit dem alten Klassennamen
     * -> rex_cronjob_neues_publish ändern in FriendsOfRedaxo\Neues\Cronjob\Publish.
     */
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setValue('type', Publish::class);
    $sql->setWhere('`type` = :class', [':class' => 'rex_cronjob_neues_publish']);
    $sql->update();

    /**
     * Fehlenden CronJob eintragen.
     */
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setWhere('`type` = :class', [':class' => Publish::class]);
    $sql->select();

    if (0 === $sql->getRows()) {
        $this->includeFile(__DIR__ . '/install/cronjob_publish.php', $subScriptParams);
    }
}

/**
 * Optional: URL-Profile installieren falls das Url-Addon aktiviert ist
 * Nach einer erfolgreichen Installation wird in der Config ein Flag gesetzt
 * um eine erneute Installation zu vermeiden
 * (rex_config::set('neues', 'url_profile', true)).
 */
if (rex_addon::get('url')->isAvailable()) {
    if (false === rex_config::get('neues', 'url_profile', false)) {
        $urlProfileTable = rex::getTable(Profile::TABLE_NAME);
        // Category
        $sql->setTable($urlProfileTable);
        $sql->setWhere('table_name = :tn', [':tn' => '1_xxx_rex_neues_category']);
        $sql->select();
        if (0 === $sql->getRows()) {
            $this->includeFile(__DIR__ . '/install/url_profile_category.php', $subScriptParams);
        }

        // Entry
        $sql->setTable($urlProfileTable);
        $sql->setWhere('table_name = :tn', [':tn' => '1_xxx_rex_neues_entry']);
        $sql->select();
        if (0 === $sql->getRows()) {
            $this->includeFile(__DIR__ . '/install/url_profile_entry.php', $subScriptParams);
        }

        Cache::deleteProfiles();

        // URL-Profile als installiert markieren
        rex_config::set('neues', 'url_profile', true);
    }
}
