<?php

/**
 * Domain field for YForm
 * Provides domain selection from YRewrite domains
 *
 * @package FriendsOfRedaxo\Neues
 */

class rex_yform_value_domain extends rex_yform_value_abstract
{
    public function enterObject()
    {
        $multiple = $this->getElement('multiple') == '1';

        $domains = [];

        // Get domains from YRewrite if available
        if (\rex_addon::get('yrewrite')->isAvailable()) {
            $yrewrite_domains = \rex_yrewrite::getDomains();
            foreach ($yrewrite_domains as $domain) {
                $domains[$domain->getId()] = $domain->getName();
            }
        }

        // If no domains available, show info message
        if (empty($domains)) {
            $domains[0] = \rex_i18n::msg('neues_domain_no_domains_available');
        }

        $value = $this->getValue();

        if ($multiple) {
            if (is_array($value)) {
                // $value is already an array, use it directly
                $selected_values = array_filter(array_map('intval', $value));
            } else {
                // $value is a string, explode it
                $selected_values = $value ? array_filter(array_map('intval', explode(',', (string) $value))) : [];
            }
        } else {
            $selected_values = $value ? (is_array($value) ? $value : [$value]) : [];
        }

        $attributes = [];
        $attributes['class'] = 'form-control selectpicker';
        $attributes['id'] = $this->getFieldId();
        $attributes['name'] = $this->getFieldName() . ($multiple ? '[]' : '');

        if ($multiple) {
            $attributes['multiple'] = 'multiple';
            $attributes['data-live-search'] = 'true';
            $attributes['data-actions-box'] = 'true';
        }

        if ($this->getElement('required')) {
            $attributes['required'] = 'required';
        }

        $select = new \rex_select();
        $select->setId($attributes['id']);
        $select->setName($attributes['name']);
        $select->setMultiple($multiple);
        $select->setAttribute('class', $attributes['class']);

        if ($this->getElement('required')) {
            $select->setAttribute('required', 'required');
        }

        // Add selectpicker specific attributes for multiple selection
        if ($multiple) {
            $select->setAttribute('data-live-search', 'true');
            $select->setAttribute('data-actions-box', 'true');
            $select->setAttribute('title', rex_i18n::msg('neues_domain_please_select'));
        }

        // Add empty option if not multiple and not required
        if (!$multiple && !$this->getElement('required')) {
            $select->addOption('-- ' . rex_i18n::msg('neues_domain_please_select') . ' --', '');
        }

        foreach ($domains as $domain_id => $domain_name) {
            $select->addOption($domain_name, $domain_id);
        }

        if ($multiple) {
            $select->setSelected($selected_values);
        } else {
            $select->setSelected($value);
        }

        $this->params['form_output'][$this->getId()] = $this->parse('value.domain.tpl.php', compact('select', 'multiple'));

        // Set value pool for saving
        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function preValidateAction(): void
    {
        $value = $this->getValue();
        $multiple = $this->getElement('multiple') == '1';

        // Only process if we actually have array data (from form submission)
        if (is_array($value) && !empty($value)) {
            if ($multiple) {
                // Convert array to comma-separated string for database storage
                $newValue = implode(',', array_filter(array_map('intval', $value)));
                $this->setValue($newValue);
            } else {
                // Single value but received as array, take first value
                $newValue = reset($value);
                $this->setValue($newValue);
            }
        }
    }

    public function postValidateAction(): void
    {
        // Optional: Additional validation logic here
    }

    public function getDescription(): string
    {
        return 'domain|name|label|multiple';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'domain',
            'values' => [
                'name' => ['type' => 'name', 'label' => \rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => \rex_i18n::msg('yform_values_defaults_label')],
                'multiple' => ['type' => 'checkbox', 'label' => \rex_i18n::msg('yform_values_choice_multiple')],
            ],
            'description' => \rex_i18n::msg('yform_values_domain_description'),
            'db_type' => ['varchar(191)', 'text'],
        ];
    }

    public static function getSearchField($params)
    {
        if (isset($params['searchform']) && $params['searchform']) {
            $params['searchform']->setValueField('text', ['name' => $params['field']->getName(), 'label' => $params['field']->getLabel()]);
        }
    }

    public static function getListValue($params)
    {
        $value = $params['subject'];

        if (is_array($value)) {
            if (isset($value['subject'])) {
                $value = $value['subject'];
            } else {
                $value = reset($value);
            }
        }

        $value = (string) $value;        if (empty($value)) {
            return '-';
        }

        $domain_ids = array_filter(array_map('intval', explode(',', (string) $value)));
        $domain_names = [];

        if (\rex_addon::get('yrewrite')->isAvailable()) {
            $yrewrite_domains = \rex_yrewrite::getDomains();
            foreach ($domain_ids as $domain_id) {
                foreach ($yrewrite_domains as $domain) {
                    if ($domain->getId() == $domain_id) {
                        $domain_names[] = \rex_escape($domain->getName());
                        break;
                    }
                }
            }
        }

        if (empty($domain_names)) {
            // Fallback: show IDs if names not found
            return 'IDs: ' . implode(', ', $domain_ids);
        }

        return implode(', ', $domain_names);
    }
}
