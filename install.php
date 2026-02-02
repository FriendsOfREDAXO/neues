<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_addon;
use rex_config;
use rex_file;
use rex_media;
use rex_media_service;
use rex_path;
use rex_sql;
use rex_version;
use rex_yform_manager_table;
use rex_yform_manager_table_api;
use Url\Cache;
use Url\Profile;

use function is_string;

/** @var rex_addon $this */

$sql = rex_sql::factory();

/**
 * YForm-Cache löschen vor dem Import (wie ycom es macht).
 */
rex_yform_manager_table::deleteCache();

/**
 * Tablesets aktualisieren
 * - Datenbanktabellen anlegen bzw. das Schema aktualisieren
 * - YForm-Tablesets eintragen bzw. aktualisieren (soweit das möglich ist)
 * - bei vorhandenen Datensätzen ggf. leere UUIDs füllen.
 */
$this->includeFile(__DIR__ . '/install/update_scheme.php');

// Tableset-Import mit Cache-Kontrolle
$tablesetContent = rex_file::get(__DIR__ . '/install/tableset.json', '[]');
if (is_string($tablesetContent) && '' !== $tablesetContent) {
    rex_yform_manager_table_api::importTablesets($tablesetContent);
}

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
    // TODO: Text nach *.lang verlagern
    $data['title'] = 'Neues - Fallback-Image';
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
    $sql->setValue('type', 'FriendsOfRedaxo\\Neues\\Cronjob\\Publish');
    $sql->setWhere('`type` = :class', [':class' => 'rex_cronjob_neues_publish']);
    $sql->update();

    /**
     * Fehlenden CronJob eintragen.
     */
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setWhere('`type` = :class', [':class' => 'FriendsOfRedaxo\\Neues\\Cronjob\\Publish']);
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

/**
 * Beim Update einer Version vor 5.1.0 wird ein Fehler bei den Status-Werten
 * korrigiert. Deleted wird von 2 auf -2 geändert.
 */
if (rex_version::compare('5.1.0', $this->getVersion(), '>')) {
    $sql = rex_sql::factory();
    $sql->setTable(rex::getTable('neues_entry'));
    $sql->setWhere('status = 2');
    $sql->setValue('status', -2);
    $sql->update();
}

/**
 * Migration für Version 7.0.0: Feldnamen-Migration vor tableset.json Import
 * Verhindert Konflikte mit yform_field Addon.
 */
if (rex_version::compare('7.0.0', $this->getVersion(), '>')) {
    if (rex_addon::get('yform')->isAvailable()) {
        $fieldMappings = [
            'choice_status' => 'neues_choice_status',
            'datetime_local' => 'neues_datetime_local',
            'domain' => 'neues_domain',
        ];

        foreach ($fieldMappings as $oldFieldName => $newFieldName) {
            $sql = rex_sql::factory();
            $sql->setQuery('UPDATE ' . rex::getTable('yform_field') . ' SET type_name = ? WHERE type_name = ? AND table_name LIKE "rex_neues_%"', [$newFieldName, $oldFieldName]);
        }

        // YForm-Cache regenerieren nach Migration
        rex_yform_manager_table_api::generateTablesAndFields();
    }
}

/**
 * Cache löschen am Ende der Installation (wie ycom es macht)
 * Das verhindert doppelte Imports und Cache-Probleme.
 */
rex_delete_cache();
