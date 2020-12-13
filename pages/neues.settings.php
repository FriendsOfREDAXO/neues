<?php

$addon = rex_addon::get('neues');

$form = rex_config_form::factory($addon->name);

$field = $form->addMediaField('default_thumbnail');
$field->setPreview(1);
$field->setTypes('jpg,gif,png');
$field->setLabel('Vorschau-Bild');

$field = $form->addInputField('text', 'default_url_label', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('default_url_label'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('neues_settings'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
