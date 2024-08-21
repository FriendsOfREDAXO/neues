<?php

/**
 * Job "Publish" konfigurieren.
 *
 * @var rex_sql $sql Kommt aus dem aufrufenden install.php
 * @var string $installUser Kommt aus dem aufrufenden install.php
 */

use FriendsOfRedaxo\Neues\Cronjob\Publish;

$job_intervall = [
    'minutes' => 'all',
    'hours' => 'all',
    'days' => 'all',
    'weekdays' => 'all',
    'month' => 'all',
];

$timestamp = rex_cronjob_manager_sql::calculateNextTime($job_intervall);

$sql->setTable(rex::getTable('cronjob'));
$sql->setValue('name', '[neues] Geplante Beiträge veröffentlichen');
$sql->setValue('description', 'Veröffentlicht alle Beiträge (status = 1), deren Status geplant (status = 0) ist und deren Veröffentlichungszeitpunkt erreicht wurde (publishdate < now()).');
$sql->setValue('type', Publish::class);
$sql->setValue('parameters', '[]');
$sql->setValue('interval', json_encode($job_intervall));
$sql->setValue('nexttime', rex_sql::datetime($timestamp));
$sql->setValue('environment', '|frontend|backend|script|');
$sql->setValue('execution_moment', 0);
$sql->setValue('execution_start', '0000-00-00 00:00:00');
$sql->setValue('status', 1);
$sql->addGlobalUpdateFields($installUser);
$sql->addGlobalCreateFields($installUser);
$sql->insert();
