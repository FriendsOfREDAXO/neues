<?php
/**
 * @var rex_yform_value_datetime_local $this
 * @var array $attributes
 */

$notice = [];
if ($this->getElement('notice') != '') {
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

// Build attributes string
$attr_string = '';
foreach ($attributes as $key => $value) {
    if ($value !== '') {
        $attr_string .= ' ' . rex_escape($key) . '="' . rex_escape($value) . '"';
    } else {
        $attr_string .= ' ' . rex_escape($key);
    }
}

?>

<div class="<?= rex_escape($class_group) ?>">
    <?php if ($this->getElement('label') != ''): ?>
        <label class="control-label" for="<?= $this->getFieldId() ?>"><?= rex_i18n::translate($this->getElement('label'), false) ?></label>
    <?php endif; ?>
    
    <input<?= $attr_string ?> />
    
    <?php if ($notice != ''): ?>
        <p class="help-block small"><?= $notice ?></p>
    <?php endif; ?>
</div>