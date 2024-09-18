<?php

namespace FriendsOfRedaxo\Neues;

use rex;
use rex_be_controller;
use rex_be_page;
use rex_csrf_token;
use rex_extension_point;
use rex_fragment;
use rex_pager;
use rex_sql;
use rex_url;

use const ENT_QUOTES;

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
     *
     * @api
     */
    public static function getList(int $rowsPerPage = 10, string $pageCursor = 'page'): string
    {
        $query = Entry::query()
            ->where('status', Entry::ONLINE, '>=')
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
     *
     * @api
     */
    public static function getEntry(int $postId): string
    {
        $post = Entry::get($postId);
        $fragment = new rex_fragment();
        $fragment->setVar('post', $post);
        return $fragment->parse('neues/entry.php');
    }

    /**
     * Hilfsklasse für JSON-LD Fragmente.
     *
     * @api
     */
    public static function htmlEncode(string $value): string
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * EP-Callback für PAGES_PREPARED.
     *
     * Ergänzt den Backend-Menüpunkt um einen Plus-Button. Dies aber nur dann,
     * wenn die Instanz nicht via Redaxo-Konsole aufgerufen wurde.
     * Der EP bietet sich an, da er bei Konsolen-Aufrufen nicht durchlaufen wird.
     *
     * @api
     * @param rex_extension_point<array<string,rex_be_page>> $ep
     */
    public static function epPagesPrepared(rex_extension_point $ep): void
    {
        $_csrf_key = Entry::table()->getCSRFKey();

        $params = rex_csrf_token::factory($_csrf_key)->getUrlParams();

        $params['table_name'] = Entry::table()->getTableName(); // Tabellenname anpassen
        $params['rex_yform_manager_popup'] = '0';
        $params['func'] = 'add';

        $href = rex_url::backendPage('neues/entry', $params);

        $neues = rex_be_controller::getPageObject('neues');
        $neues->setTitle(
            $neues->getTitle() .
            ' <a class="label label-primary tex-primary" style="position: absolute; right: 18px; top: 10px; padding: 0.2em 0.6em 0.3em; border-radius: 3px; color: white; display: inline; width: auto;" href="' . $href . '">+</a>',
        );
    }
}
