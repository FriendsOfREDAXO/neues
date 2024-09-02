<?php

/** @var rex_addon $this */

/**
 * für docs gilt: es sind Vorarbeiten notwendig, damit das Menü korrekt angezeigt
 * wird. Daher abweichender Ablauf.
 */
if('neues/docs' === rex_be_controller::getCurrentPage()) {
    $this->includeFile(__DIR__ . '/neues.docs.php');
    return;
}

echo rex_view::title(rex_addon::get('neues')->getProperty('page')['title']);
rex_be_controller::includeCurrentPageSubPath();
