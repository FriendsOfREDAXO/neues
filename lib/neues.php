<?php

namespace FriendsOfRedaxo\Neues;

use rex_fragment;
use rex_pager;
use rex_sql;
use rex_sql_table;
use rex;
use rex_sql_column;
use rex_sql_index;

class Neues
{
    /**
     * Gibt eine HTML-Liste mit den Einträgen zurück.
     * Diese Liste ist paginiert.
     * Die Einträge werden nach dem Veröffentlichungsdatum absteigend sortiert.
     * Der Output wird über ein Fragment erzeugt, diese können nach Belieben angepasst werden.
     * Die Beispiel-Fragmente bauen auf Bootstrap 5 auf.
     *
     * @param int $rowsPerPage Anzahl der Einträge pro Seite
     * @param string $pageCursor Name des GET-Parameters für die Seitennummer
     * @return string HTML-Liste mit den Einträgen
     *
     *  Beispiel / Example:
     *  echo neues::getList(2);
     */
    public static function getList(int $rowsPerPage = 10, string $pageCursor = 'page'): string
    {
        $query = Entry::query()
            ->where('status', 1, '>=')
            ->where('publishdate', rex_sql::datetime(), '<=')
            ->orderBy('publishdate', 'desc');
        $pager = new rex_pager($rowsPerPage, $pageCursor);
        $posts = $query->paginate($pager);

        $fragment = new rex_fragment();
        $fragment->setVar('posts', $posts);
        $fragment->setVar('pager', $pager);
        return $fragment->parse('neues/list.php');
    }

    /**
     * Gibt einen einzelnen Eintrag zurück.
     * Der Output wird über ein Fragment erzeugt, diese können nach Belieben angepasst werden.
     * Die Beispiel-Fragmente bauen auf Bootstrap 5 auf.
     *
     * @param int $postId ID des Eintrags
     * @return string HTML des Eintrags
     *
     *  Beispiel / Example:
     *  echo neues::getEntry(2);
     */
    public static function getEntry(int $postId): string
    {
        $post = Entry::get($postId);
        $fragment = new rex_fragment();
        $fragment->setVar('post', $post);
        return $fragment->parse('neues/entry.php');
    }

    public static function ensureDbScheme()
    {
        rex_sql_table::get(rex::getTable('neues_entry'))
            ->ensurePrimaryIdColumn()
            ->ensureColumn(new rex_sql_column('status', 'int(11)'))
            ->ensureColumn(new rex_sql_column('name', 'varchar(191)', false, ''))
            ->ensureColumn(new rex_sql_column('teaser', 'text'))
            ->ensureColumn(new rex_sql_column('description', 'text'))
            ->ensureColumn(new rex_sql_column('domain_ids', 'text'))
            ->ensureColumn(new rex_sql_column('lang_id', 'int(10) unsigned'))
            ->ensureColumn(new rex_sql_column('publishdate', 'datetime'))
            ->ensureColumn(new rex_sql_column('author_id', 'int(10) unsigned'))
            ->ensureColumn(new rex_sql_column('url', 'varchar(191)', false, ''))
            ->ensureColumn(new rex_sql_column('image', 'text'))
            ->ensureColumn(new rex_sql_column('images', 'text'))
            ->ensureColumn(new rex_sql_column('createdate', 'datetime'))
            ->ensureColumn(new rex_sql_column('createuser', 'varchar(191)'))
            ->ensureColumn(new rex_sql_column('updatedate', 'datetime'))
            ->ensureColumn(new rex_sql_column('updateuser', 'varchar(191)'))
            ->ensureColumn(new rex_sql_column('uuid', 'varchar(36)'))
            ->ensureIndex(new rex_sql_index('uuid', ['uuid'], rex_sql_index::UNIQUE))
            ->ensureIndex(new rex_sql_index('status', ['status']))
            ->ensureIndex(new rex_sql_index('publishdate', ['publishdate']))
            ->ensureIndex(new rex_sql_index('description', ['description'], rex_sql_index::FULLTEXT))
            ->ensure();
        
        rex_sql_table::get(rex::getTable('neues_category'))
            ->ensurePrimaryIdColumn()
            ->ensureColumn(new rex_sql_column('name', 'varchar(191)', false, ''))
            ->ensureColumn(new rex_sql_column('image', 'text'))
            ->ensureColumn(new rex_sql_column('status', 'text'))
            ->ensureColumn(new rex_sql_column('createuser', 'varchar(191)'))
            ->ensureColumn(new rex_sql_column('updateuser', 'varchar(191)'))
            ->ensureColumn(new rex_sql_column('updatedate', 'datetime'))
            ->ensureColumn(new rex_sql_column('createdate', 'datetime'))
            ->ensureColumn(new rex_sql_column('uuid', 'varchar(36)'))
            ->ensureIndex(new rex_sql_index('uuid', ['uuid'], rex_sql_index::UNIQUE))
            ->ensure();
        rex_sql_table::get(rex::getTable('neues_author'))
            ->ensurePrimaryIdColumn()
            ->ensureColumn(new rex_sql_column('name', 'varchar(191)', false, ''))
            ->ensureColumn(new rex_sql_column('nickname', 'varchar(191)', false, ''))
            ->ensureColumn(new rex_sql_column('text', 'text'))
            ->ensureColumn(new rex_sql_column('be_user_id', 'text'))
            ->ensureColumn(new rex_sql_column('uuid', 'varchar(36)'))
            ->ensure();

    }
}
