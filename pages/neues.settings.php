<?php

$addon = rex_addon::get('events');

$form = rex_config_form::factory($addon->name);

$field = $form->addInputField('text', 'currency', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('events_currency'));

$field = $form->addInputField('text', 'timezone_api_code', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('events_timezone'));
$field->setNotice(rex_i18n::msg('events_timezone_notice'). '<a href="https://developers.google.com/maps/documentation/timezone/intro?hl=de">https://developers.google.com/maps/documentation/timezone/intro?hl=de</a>');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('events_settings'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
