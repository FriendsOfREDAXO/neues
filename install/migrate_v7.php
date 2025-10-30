<?php

/**
 * Migration Script für neues Addon Version 7.0.0.
 *
 * Migriert YForm-Feldnamen von den alten Namen zu den neuen Namen
 * um Konflikte mit dem yform_field Addon zu vermeiden.
 */

if (!rex_addon::get('yform')->isAvailable()) {
    return;
}

$fieldMappings = [
    'choice_status' => 'neues_choice_status',
    'datetime_local' => 'neues_datetime_local',
    'domain' => 'neues_domain',
];

$totalUpdated = 0;

foreach ($fieldMappings as $oldFieldName => $newFieldName) {
    // Erst prüfen, ob alte Felder existieren
    $sql = rex_sql::factory();
    $sql->setQuery('
        SELECT COUNT(*) as count
        FROM ' . rex::getTable('yform_field') . ' 
        WHERE type_name = :old_name 
        AND table_name LIKE "rex_neues_%"
    ', [':old_name' => $oldFieldName]);

    $oldFieldsExist = $sql->getValue('count') > 0;

    if ($oldFieldsExist) {
        // Alte Felder zu neuen Feldnamen ändern
        $sql = rex_sql::factory();
        $sql->setQuery('
            UPDATE ' . rex::getTable('yform_field') . ' 
            SET type_name = :new_name 
            WHERE type_name = :old_name 
            AND table_name LIKE "rex_neues_%"
        ', [
            ':old_name' => $oldFieldName,
            ':new_name' => $newFieldName,
        ]);

        $updated = $sql->getRows();
        if ($updated > 0) {
            $totalUpdated += $updated;
            echo "✅ YForm field '{$oldFieldName}' → '{$newFieldName}' ({$updated} fields updated)\n";
        }
    } else {
        echo "ℹ️  No old '{$oldFieldName}' fields found - migration already completed\n";
    }
}

if ($totalUpdated > 0) {
    // Tableset neu importieren
    rex_yform_manager_table_api::importTablesets(rex_file::get(__DIR__ . '/tableset.json', '[]'));

    // YForm-Cache leeren
    rex_yform_manager_table_api::generateTableClass();

    echo "🎉 Migration completed: {$totalUpdated} YForm fields migrated to new names\n";
    echo "📄 Tableset reimported and cache cleared\n";
} else {
    echo "ℹ️  No fields needed migration - all up to date\n";
}
