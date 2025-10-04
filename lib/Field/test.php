<?php
/**
 * Test template for custom YForm fields
 * 
 * This can be used to test the custom field types:
 * - domain
 * - datetime_local  
 * - choice_status
 */

echo '<h3>Custom YForm Fields Test</h3>';

// Test domain field
if (rex_addon::get('yrewrite')->isAvailable()) {
    $domains = rex_yrewrite::getDomains();
    echo '<p>Available YRewrite domains: ' . count($domains) . '</p>';
} else {
    echo '<p><strong>Warning:</strong> YRewrite not available - domain field will show fallback message</p>';
}

// Test datetime_local field
echo '<p>Current datetime for datetime_local: ' . date('Y-m-d\TH:i') . '</p>';

// Test choice_status field  
echo '<p>Status options will be loaded from callback methods</p>';

echo '<hr>';
echo '<p><em>Fields are ready for use in YForm Manager</em></p>';
?>