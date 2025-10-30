-- SQL Script zur Bereinigung ALLER doppelter YForm-Felder nach neues 7.0.0 Update
-- 
-- PROBLEM: Alle YForm-Felder wurden doppelt importiert bei frischer Installation!
-- URSACHE: Cache-Problem beim tableset.json Import
-- LÖSUNG: Entfernt alle doppelten Felder und behält nur jeweils das erste pro Tabelle/Name/Prio
--
-- ACHTUNG: Vorher UNBEDINGT ein Backup der Datenbank erstellen!

-- 1. Zuerst schauen wir uns das Problem an
-- Zeige alle doppelten Felder in neues-Tabellen
SELECT 
    'ANALYSE - DOPPELTE FELDER:' as status,
    table_name, 
    name, 
    type_name, 
    prio,
    COUNT(*) as anzahl_duplikate
FROM rex_yform_field 
WHERE table_name LIKE 'rex_neues_%'
GROUP BY table_name, name, type_name, prio
HAVING COUNT(*) > 1
ORDER BY table_name, prio;

-- 2. ALLE doppelten Felder entfernen (behält immer das mit der niedrigsten ID)
-- Das ist sicherer als spezifische Feldnamen, da es alle Duplikate erfasst
DELETE t1 FROM rex_yform_field t1
INNER JOIN rex_yform_field t2 
WHERE t1.id > t2.id 
AND t1.table_name = t2.table_name
AND t1.name = t2.name  
AND t1.type_name = t2.type_name
AND t1.prio = t2.prio
AND t1.table_name LIKE 'rex_neues_%';

-- 3. Zusätzlich: Entferne alte Feldnamen falls noch vorhanden
DELETE FROM rex_yform_field 
WHERE type_name IN ('choice_status', 'datetime_local', 'domain')
AND table_name LIKE 'rex_neues_%';

-- 4. Kontrollabfrage: Prüfe ob noch Duplikate existieren
SELECT 
    'DUPLIKATE NACH CLEANUP:' as status,
    COUNT(*) as anzahl_duplikate_gesamt
FROM (
    SELECT table_name, name, type_name, prio, COUNT(*) as cnt
    FROM rex_yform_field 
    WHERE table_name LIKE 'rex_neues_%'
    GROUP BY table_name, name, type_name, prio
    HAVING COUNT(*) > 1
) as duplicates;

-- 5. Zeige finale Feldliste der neues-Tabellen (zur Kontrolle)
SELECT 
    'FINALE FELDLISTE:' as status,
    table_name, 
    name, 
    type_name, 
    prio,
    COUNT(*) as anzahl
FROM rex_yform_field 
WHERE table_name LIKE 'rex_neues_%' 
GROUP BY table_name, name, type_name, prio
ORDER BY table_name, prio;

-- 6. WICHTIGE NACHARBEITEN (UNBEDINGT im REDAXO Backend ausführen):
--
-- A) YForm > Tabellen-Manager > "Tabellen-Klassen generieren" klicken
-- B) System > Cache > "Cache löschen" klicken  
-- C) News-Einträge testen: Erstellen/Bearbeiten/Anzeigen
--
-- ODER per PHP (falls verfügbar):
-- rex_yform_manager_table::deleteCache();
-- rex_yform_manager_table_api::generateTableClass();
-- rex_delete_cache();