<?php
/**
 * @var rex_select $select
 * @var bool $multiple
 * @var bool $expanded  
 * @var array $choices
 */

// Always output the select element - rex_select handles multiple and selected values
echo $select->get();