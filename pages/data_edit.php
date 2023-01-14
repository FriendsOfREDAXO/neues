<?php
$yform = rex_package::get("neues")->getProperty('pages')[rex_be_controller::getCurrentPagePart(1)]['subpages'][rex_be_controller::getCurrentPagePart(2)]['yform'];

$table_name = $yform['table_name'] ?? '';
$show_title = true === ($yform['show_title'] ?? false);
$wrapper_class = $yform['wrapper_class'] ?? '';

if ($table_name) {
    $_REQUEST['table_name'] = $table_name;
}

if (!$show_title) {
    \rex_extension::register(
        'YFORM_MANAGER_DATA_PAGE_HEADER',
        function (\rex_extension_point $ep) {
            if ($ep->getParam('yform')->table->getTableName() === $ep->getParam('table_name')) {
                return '';
            }
        },
        \rex_extension::EARLY,
        ['table_name'=>$table_name]
    );
}

if ($wrapper_class) {
    echo '<div class="',$wrapper_class,'">';
}

include \rex_path::plugin('yform', 'manager', 'pages/data_edit.php');

if ($wrapper_class) {
    echo '</div>';
}
