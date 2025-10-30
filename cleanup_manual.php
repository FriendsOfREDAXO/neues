#!/usr/bin/env php
<?php

/**
 * Manuelles Cleanup Script für neues Addon Version 7.0.0
 * 
 * Führen Sie dieses Script aus, um doppelte YForm-Felder zu bereinigen.
 * 
 * Ausführung: php cleanup_manual.php
 */

// REDAXO Bootstrap (passen Sie den Pfad an Ihre Installation an)
$redaxoPath = __DIR__ . '/../../../../../../../../';
if (!file_exists($redaxoPath . 'redaxo/bin/console')) {
    echo "❌ REDAXO nicht gefunden. Bitte Pfad anpassen.\n";
    exit(1);
}

require_once $redaxoPath . 'redaxo/src/core/boot.php';
rex::setProperty('setup', true);

echo "🔧 Cleanup für neues Addon Version 7.0.0\n";
echo "==========================================\n\n";

if (!rex_addon::get('yform')->isAvailable()) {
    echo "❌ YForm Addon ist nicht verfügbar\n";
    exit(1);
}

$fieldMappings = [
    'choice_status' => 'neues_choice_status',
    'datetime_local' => 'neues_datetime_local',
    'domain' => 'neues_domain',
];

$totalCleaned = 0;

foreach ($fieldMappings as $oldFieldName => $newFieldName) {
    echo "Prüfe Feld: {$oldFieldName} → {$newFieldName}\n";
    
    // Zähle alte und neue Felder
    $sql = rex_sql::factory();
    $sql->setQuery('SELECT COUNT(*) as count FROM ' . rex::getTable('yform_field') . ' WHERE type_name = ? AND table_name LIKE "rex_neues_%"', [$oldFieldName]);
    $oldCount = (int) $sql->getValue('count');
    
    $sql->setQuery('SELECT COUNT(*) as count FROM ' . rex::getTable('yform_field') . ' WHERE type_name = ? AND table_name LIKE "rex_neues_%"', [$newFieldName]);
    $newCount = (int) $sql->getValue('count');
    
    echo "  Alte Felder: {$oldCount}, Neue Felder: {$newCount}\n";
    
    if ($oldCount > 0 && $newCount > 0) {
        // Beide existieren - alte löschen
        echo "  → Lösche doppelte alte Felder...\n";
        $sql->setQuery('DELETE FROM ' . rex::getTable('yform_field') . ' WHERE type_name = ? AND table_name LIKE "rex_neues_%"', [$oldFieldName]);
        $totalCleaned += $oldCount;
        echo "  ✅ {$oldCount} alte Felder gelöscht\n";
        
    } elseif ($oldCount > 0 && $newCount === 0) {
        // Nur alte existieren - umbenennen
        echo "  → Benenne alte Felder um...\n";
        $sql->setQuery('UPDATE ' . rex::getTable('yform_field') . ' SET type_name = ? WHERE type_name = ? AND table_name LIKE "rex_neues_%"', [$newFieldName, $oldFieldName]);
        $totalCleaned += $oldCount;
        echo "  ✅ {$oldCount} Felder umbenannt\n";
        
    } elseif ($newCount > 0) {
        echo "  ✓ Bereits korrekt migriert\n";
    } else {
        echo "  ℹ️  Keine Felder gefunden\n";
    }
    echo "\n";
}

if ($totalCleaned > 0) {
    echo "📄 Lade Tableset neu...\n";
    rex_yform_manager_table_api::importTablesets(rex_file::get(__DIR__ . '/tableset.json', '[]'));
    
    echo "🔄 Leere YForm-Cache...\n";
    rex_yform_manager_table_api::generateTableClass();
    
    echo "\n🎉 Cleanup abgeschlossen: {$totalCleaned} Feldoperationen durchgeführt\n";
} else {
    echo "✅ Kein Cleanup notwendig - alle Felder sind aktuell\n";
}

echo "\n==========================================\n";
echo "Cleanup abgeschlossen!\n";