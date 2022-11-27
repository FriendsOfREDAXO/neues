<?php

$addon = rex_addon::get('neues');

$form = rex_config_form::factory($addon->name);

$field = $form->addMediaField('default_thumbnail');
$field->setPreview(1);
$field->setTypes('jpg,gif,png');
$field->setLabel(rex_i18n::msg('neues_default_thumbnail'));

$field = $form->addInputField('text', 'default_url_label', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('neues_default_url_label'));

$field = $form->addInputField('text', 'default_author', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('neues_default_author'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('neues_settings'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
