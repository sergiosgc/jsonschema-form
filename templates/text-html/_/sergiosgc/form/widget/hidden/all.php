<?php
$properties = [ 'name' => $tvars['propertyName'], 'type' => 'hidden' ];
foreach([
    'ui:class' => 'class',
    'value' => 'value',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
] as $widgetProperty => $htmlProperty) {
    if (isset($tvars['property'][$widgetProperty])) $properties[$htmlProperty] = $tvars['property'][$widgetProperty];
}
printf('<input %s>', implode(' ', array_filter(array_map(
    function($k, $v) {
        if (is_bool($v)) return $v ? $k : '';
        return sprintf('%s="%s"', $k, htmlspecialchars($v));
    }, 
    array_keys($properties),
    $properties
))));