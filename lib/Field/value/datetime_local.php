<?php

/**
 * DateTime Local field for YForm
 * Provides HTML5 datetime-local input field.
 *
 * @package FriendsOfRedaxo\Neues
 */

class rex_yform_value_datetime_local extends rex_yform_value_abstract
{
    public function enterObject()
    {
        $value = $this->getValue();

        // Convert database datetime format to HTML5 datetime-local format
        if ($value && '0000-00-00 00:00:00' !== $value) {
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            if ($datetime) {
                $value = $datetime->format('Y-m-d\TH:i');
            }
        } else {
            $value = '';
            // Set current date if specified
            if ('1' == $this->getElement('current_date')) {
                $value = date('Y-m-d\TH:i');
            }
        }

        $attributes = [];
        $attributes['type'] = 'datetime-local';
        $attributes['class'] = 'form-control';
        $attributes['id'] = $this->getFieldId();
        $attributes['name'] = $this->getFieldName();
        $attributes['value'] = $value;

        if ($this->getElement('required')) {
            $attributes['required'] = 'required';
        }

        if ($min = $this->getElement('min')) {
            $attributes['min'] = $min;
        }

        if ($max = $this->getElement('max')) {
            $attributes['max'] = $max;
        }

        $this->params['form_output'][$this->getId()] = $this->parse('value.datetime_local.tpl.php', compact('attributes'));

        // Set value pool for saving
        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'datetime_local|name|label|current_date|min|max';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'datetime_local',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'current_date' => ['type' => 'checkbox', 'label' => rex_i18n::msg('yform_values_datetime_local_current_date')],
                'min' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_datetime_local_min')],
                'max' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_datetime_local_max')],
            ],
            'description' => rex_i18n::msg('yform_values_datetime_local_description'),
            'db_type' => ['datetime'],
        ];
    }

    public function preValidateAction(): void
    {
        $value = $this->getValue();

        if ($value && '' !== $value && '0000-00-00 00:00:00' !== $value) {
            // Only convert if it's HTML5 datetime-local format (contains 'T')
            if (str_contains($value, 'T')) {
                $datetime = DateTime::createFromFormat('Y-m-d\TH:i', $value);
                if ($datetime) {
                    $this->setValue($datetime->format('Y-m-d H:i:s'));
                } else {
                    // Try with seconds
                    $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $value);
                    if ($datetime) {
                        $this->setValue($datetime->format('Y-m-d H:i:s'));
                    }
                }
            }
        } elseif ('0000-00-00 00:00:00' === $value) {
            // Convert invalid database datetime to empty
            $this->setValue('');
        }
    }

    public function postValidateAction(): void
    {
        // Optional: Additional validation logic here
    }

    public static function getSearchField($params)
    {
        if (isset($params['searchform']) && $params['searchform']) {
            $params['searchform']->setValueField('text', ['name' => $params['field']->getName(), 'label' => $params['field']->getLabel()]);
        }
    }

    public static function getListValues($params)
    {
        $value = $params['subject'];

        if (empty($value) || '0000-00-00 00:00:00' === $value) {
            return '-';
        }

        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
        if ($datetime) {
            return $datetime->format('d.m.Y H:i');
        }

        return $value;
    }
}
