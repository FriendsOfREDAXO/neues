<?php

namespace FriendsOfRedaxo\Neues;

use rex_fragment;
use rex_pager;
use rex_sql;

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
}
