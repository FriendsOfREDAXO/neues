<?php
/** @var rex_yform_value_choice_status $this */

$notice = [];
if ('' != $this->getElement('notice')) {
    $notice[] = rex_i18n::translate($this->getElement('notice'), false);
}
if (isset($this->params['warning_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notice[] = '<span class="text-warning">' . rex_i18n::translate($this->params['warning_messages'][$this->getId()], false) . '</span>';
}
if (isset($this->params['error_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notice[] = '<span class="text-danger">' . rex_i18n::translate($this->params['error_messages'][$this->getId()], false) . '</span>';
}

$notice = implode('<br />', $notice);

$class_group = trim('form-group ' . $this->getElement('css_class'));

// Get choices
$choices_string = $this->getElement('choices');
$choices = [];

if (str_contains($choices_string, '::')) {
    // Handle callback format like "ClassName::methodName"
    if (is_callable($choices_string)) {
        $choices = call_user_func($choices_string);
    } else {
        // Fallback or log warning
        $choices = [];
    }
} else {
    // Parse other formats (e.g., pipe-separated, JSON)
    $choices = array_filter(array_map('trim', explode('|', $choices_string)));
}

$value = $this->getValue();
$multiple = '1' == $this->getElement('multiple');
$expanded = '1' == $this->getElement('expanded');

?>

<div class="<?= rex_escape($class_group) ?>">
    <?php if ('' != $this->getElement('label')): ?>
        <label class="control-label" for="<?= $this->getFieldId() ?>"><?= rex_i18n::translate($this->getElement('label'), false) ?></label>
    <?php endif ?>

    <?php if ($expanded): ?>
        <!-- Expanded as radio buttons or checkboxes -->
        <?php $input_type = $multiple ? 'checkbox' : 'radio' ?>
        <?php foreach ($choices as $choice_value => $choice_label): ?>
            <div class="<?= $input_type ?>">
                <label>
                    <input type="<?= $input_type ?>"
                           name="<?= $this->getFieldName() ?><?= $multiple ? '[]' : '' ?>"
                           value="<?= rex_escape($choice_value) ?>"
                           <?= ($multiple ? (is_array($value) && in_array($choice_value, $value)) : ($value == $choice_value)) ? 'checked="checked"' : '' ?>
                           class="neues-status-choice-radio" />
                    <span class="status-label status-<?= rex_escape($choice_value, 'html_attr') ?>"><?= rex_escape($choice_label) ?></span>
                </label>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <!-- As select dropdown -->
        <select class="form-control selectpicker neues-status-choice"
                id="<?= $this->getFieldId() ?>"
                name="<?= $this->getFieldName() ?><?= $multiple ? '[]' : '' ?>"
                <?= $multiple ? 'multiple="multiple" data-live-search="true" data-actions-box="true"' : '' ?>
                data-style="btn-default"
                data-size="auto">

            <?php if (!$multiple && $this->getElement('placeholder')): ?>
                <option value=""><?= rex_escape($this->getElement('placeholder')) ?></option>
            <?php endif ?>

            <?php foreach ($choices as $choice_value => $choice_label): ?>
                <option value="<?= rex_escape($choice_value) ?>"
                        class="status-option status-<?= rex_escape($choice_value, 'html_attr') ?>"
                        <?= ($multiple ? (is_array($value) && in_array($choice_value, $value)) : ($value == $choice_value)) ? 'selected="selected"' : '' ?>>
                    <?= rex_escape($choice_label) ?>
                </option>
            <?php endforeach ?>
        </select>
    <?php endif ?>

    <?php if ('' != $notice): ?>
        <p class="help-block small"><?= $notice ?></p>
    <?php endif ?>
</div>
