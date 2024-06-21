<?php

echo rex_view::title(rex_addon::get('neues')->getProperty('page')['title']);

$addon = rex_addon::get('neues');

$form = rex_config_form::factory($addon->getName());

$field = $form->addMediaField('default_thumbnail');
$field->setPreview(true);
$field->setTypes('jpg,gif,png');
$field->setLabel(rex_i18n::msg('neues_default_thumbnail'));

$field = $form->addInputField('text', 'external_url_label', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('neues_external_url_label'));

$field = $form->addInputField('text', 'default_author', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('neues_default_author'));

$field = $form->addInputField('textarea', 'no_entries_placeholder', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('no_entries_placeholder'));

$field = $form->addInputField('text', 'editor', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('neues_editor'));
$field->setNotice('z.B. <code>class="form-control redactor-editor--default"</code>');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('neues_settings'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
