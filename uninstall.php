<?php

/**
 * De-Installieren des Addons.
 *
 * Der Umfang kann interaktiv festgelegt werden:
 * - Minimal: nur die YForm-Tablesets; ansonsten bleibt alles erhalten
 * - Vollständig: entfernt auch Tabellen, Fallback-Image, Cronjobs usw.
 *
 * Steuerung mit einem Trick: Ohne den Url-Parameter nscope wird die Deinstallation
 * mit einem Fake-Fehler abgebrochen. Die "Fehlermeldung" ist eine Abfrage des
 * Scopes. Je nach Auswahl wird ein Link abgesetzt, der den Zusatzparameter nscope
 * mit der jeweiligen ID (1 oder 2) mitführt. Darüber steuert sich dann der
 * Deinstallations-Umfang.
 */

use FriendsOfRedaxo\Neues\Cronjob\Publish;

/**
 * Url-Parameter nscope auswerten: unbekannt oder ungültig lösen die
 * "Fehlermeldung" mit der Abfrage aus
 * (Text als Markdown schreiben; dann muss man hier nicht soviel HTML basteln)
 */
$scope = rex_request::get('nscope', 'int', 0);
if (!in_array($scope, [1, 2], true)) {
    $context = rex_context::fromGet();
    $msg = '### Bitte den De-Installations-Umfang auswählen' . PHP_EOL;
    $msg .= '- **Minimal** (YForm-Tablesets entfernen) ⇒ **[Start](' . $context->getUrl(['nscope' => 1], false) . ')**' . PHP_EOL;
    $msg .= '- **Vollständig** (Tabellen, Tablesets, Cronjobs etc. entfernen) ⇒ **[Start](' . $context->getUrl(['nscope' => 2], false) . ')**' . PHP_EOL;
    $msg = rex_markdown::factory()->parse($msg);
    throw new rex_functional_exception($msg);
}

/**
 * Minimale Lösch-Aktivitäten: scope in [1,2]
 * - YForm-Tablesets entfernen.
 */
rex_yform_manager_table_api::removeTable(rex::getTable('neues_category'));
rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry'));
rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry_category_rel'));
rex_yform_manager_table_api::removeTable(rex::getTable('neues_author'));
rex_yform_manager_table_api::removeTable(rex::getTable('neues_entry_lang'));

/**
 * Vollständig löschen: scope in [2]
 * - Cronjobs des eigenen Typs
 * - UrlAddon-Profile löschen
 * - Fallback-Bild entfernen
 * - Tabellen.
 */
if (2 !== $scope) {
    return;
}

$sql = rex_sql::factory();

try {
    $sql->setTable(rex::getTable('cronjob'));
    $sql->setWhere('`type` = :class', [':class' => Publish::class]);
    $sql->delete();
} catch (Throwable $th) {
    // void; falls rex_cronjob nicht existiert / cronjob-Addon fehlt
}

try {
    $sql->setTable(rex::getTable('url_generator_profile'));
    $sql->setWhere('table_name LIKE :tn', [':tn' => '1_xxx_rex_neues_%']);
    $sql->delete();
} catch (Throwable $th) {
    // void; falls rex_url_generator_profile nicht existiert / Url-Addon fehlt
}

rex_media_service::deleteMedia('neues_entry_fallback_image.png');

rex_sql_table::get(rex::getTable('neues_category'))->drop();
rex_sql_table::get(rex::getTable('neues_entry'))->drop();
rex_sql_table::get(rex::getTable('neues_entry_category_rel'))->drop();
rex_sql_table::get(rex::getTable('neues_author'))->drop();
rex_sql_table::get(rex::getTable('neues_entry_lang'))->drop();
