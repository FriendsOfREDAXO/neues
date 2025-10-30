<?php

/**
 * Cleanup Script für neues Addon Version 7.0.0
 * 
 * Bereinigt doppelte YForm-Felder nach der Migration.
 * Entfernt die alten Feldnamen, falls sowohl alte als auch neue existieren.
 */

if (!rex_addon::get('yform')->isAvailable()) {
    echo "❌ YForm Addon ist nicht verfügbar\n";
    return;
}

$fieldMappings = [
    'choice_status' => 'neues_choice_status',
    'datetime_local' => 'neues_datetime_local',
    'domain' => 'neues_domain',
];

$totalCleaned = 0;

foreach ($fieldMappings as $oldFieldName => $newFieldName) {
    // Prüfen ob sowohl alte als auch neue Felder existieren
    $sql = rex_sql::factory();
    $sql->setQuery('
        SELECT 
            (SELECT COUNT(*) FROM ' . rex::getTable('yform_field') . ' WHERE type_name = :old_name AND table_name LIKE "rex_neues_%") as old_count,
            (SELECT COUNT(*) FROM ' . rex::getTable('yform_field') . ' WHERE type_name = :new_name AND table_name LIKE "rex_neues_%") as new_count
    ', [
        ':old_name' => $oldFieldName,
        ':new_name' => $newFieldName
    ]);
    
    $oldCount = (int) $sql->getValue('old_count');
    $newCount = (int) $sql->getValue('new_count');
    
    if ($oldCount > 0 && $newCount > 0) {
        // Beide Felder existieren - alte entfernen
        $sql = rex_sql::factory();
        $sql->setQuery('
            DELETE FROM ' . rex::getTable('yform_field') . ' 
            WHERE type_name = :old_name 
            AND table_name LIKE "rex_neues_%"
        ', [':old_name' => $oldFieldName]);
        
        $deleted = $sql->getRows();
        if ($deleted > 0) {
            $totalCleaned += $deleted;
            echo "🧹 Removed duplicate old field '{$oldFieldName}' ({$deleted} fields deleted)\n";
        }
    } elseif ($oldCount > 0 && $newCount === 0) {
        // Nur alte Felder existieren - umbenennen
        $sql = rex_sql::factory();
        $sql->setQuery('
            UPDATE ' . rex::getTable('yform_field') . ' 
            SET type_name = :new_name 
            WHERE type_name = :old_name 
            AND table_name LIKE "rex_neues_%"
        ', [
            ':old_name' => $oldFieldName,
            ':new_name' => $newFieldName
        ]);
        
        $updated = $sql->getRows();
        if ($updated > 0) {
            $totalCleaned += $updated;
            echo "✅ Migrated field '{$oldFieldName}' → '{$newFieldName}' ({$updated} fields updated)\n";
        }
    } elseif ($newCount > 0) {
        echo "✓ Field '{$newFieldName}' already exists and is up to date\n";
    } else {
        echo "ℹ️  No fields found for '{$oldFieldName}' or '{$newFieldName}'\n";
    }
}

if ($totalCleaned > 0) {
    // Tableset neu importieren
    rex_yform_manager_table_api::importTablesets(rex_file::get(__DIR__ . '/tableset.json', '[]'));
    
    // YForm-Cache leeren
    rex_yform_manager_table_api::generateTableClass();
    
    echo "🎉 Cleanup completed: {$totalCleaned} YForm field operations performed\n";
    echo "📄 Tableset reimported and cache cleared\n";
} else {
    echo "✅ No cleanup needed - all fields are up to date\n";
}