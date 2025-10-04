<?php
class rex_yform_value_choice_status extends rex_yform_value_abstract
{
    public function enterObject()
    {
        $choices = [];
        $choices_string = $this->getElement('choices');
        
        if (false !== strpos($choices_string, '::')) {
            if (is_callable($choices_string)) {
                $choices = call_user_func($choices_string);
                if (!is_array($choices)) {
                    $choices = [];
                }
            }
        } elseif ('' !== trim((string) $choices_string)) {
            foreach (preg_split('/\r\n|\r|\n|,/', (string) $choices_string) as $choice_line) {
                $choice_line = trim($choice_line);
                if ('' === $choice_line) {
                    continue;
                }
                $parts = array_map('trim', explode('=', $choice_line, 2));
                $valuePart = $parts[0];
                $labelPart = $parts[1] ?? $parts[0];
                $choices[$valuePart] = $labelPart;
            }
        }

        $value = $this->getValue();
        
        $select = new rex_select();
        $select->setId($this->getFieldId());
        $select->setName($this->getFieldName());
        $select->setSize(1);
        $select->setAttribute('class', 'form-control selectpicker neues-status-choice');

        foreach ($choices as $choice_value => $choice_label) {
            $select->addOption($choice_label, $choice_value);
        }

        $select->setSelected($value);

        $this->params['form_output'][$this->getId()] = $this->parse('value.choice_status.tpl.php', compact('select', 'choices'));
    }

    public function preValidateAction(): void
    {
        $value = $this->getValue();
        $is_form_submission = isset($_POST['FORM']);
        
        if ($is_form_submission) {
            $this->params['value_pool']['sql'][$this->getName()] = (string) $value;
        }
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'choice_status',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'choices' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choices')],
            ],
            'db_type' => ['text', 'varchar(191)', 'int'],
        ];
    }

    public static function getListValue($value)
    {
        // Handle array values - extract the actual value
        if (is_array($value)) {
            if (isset($value['subject'])) {
                $actual_value = $value['subject'];
            } else {
                $actual_value = reset($value);
            }
        } else {
            $actual_value = $value;
        }
        
        // Ensure we have a scalar value
        if (is_array($actual_value)) {
            $actual_value = (string) reset($actual_value);
        }
        $actual_value = (string) $actual_value;
        
        if ('' === $actual_value && '0' !== $actual_value) {
            return '-';
        }

        $choices = [];
        if (class_exists('FriendsOfRedaxo\Neues\Entry') && method_exists('FriendsOfRedaxo\Neues\Entry', 'statusChoice')) {
            $choices = \FriendsOfRedaxo\Neues\Entry::statusChoice();
        }

        $status_value = isset($choices[$actual_value]) ? $choices[$actual_value] : $actual_value;
        
        $status_class = '';
        if (is_numeric($actual_value)) {
            $int_value = (int) $actual_value;
            if ($int_value >= 1) {
                $status_class = 'text-success';
            } elseif ($int_value === 0) {
                $status_class = 'text-warning';
            } else {
                $status_class = 'text-danger';
            }
        }
        
        return '<span class="' . $status_class . '">' . rex_escape($status_value) . '</span>';
    }
}
