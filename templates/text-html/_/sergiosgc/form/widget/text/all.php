<?php
$properties = [ 'name' => $tvars['propertyName'], 'type' => $tvars['property']['type'] ];
foreach([
    'ui:class' => 'class',
    'value' => 'value',
    'ui:value' => 'value',
    'maxLength' => 'maxlength', 
    'minLength' => 'minLength', 
    'validation:pattern' => 'pattern',
    'placeholder' => 'placeholder',
    'readonly' => 'readonly',
    'ui:size' => 'size',
    'ui:autofocus' => 'autofocus',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
    'ui:tabIndex' => 'tabindex',
] as $widgetProperty => $htmlProperty) {
    if (isset($tvars['property'][$widgetProperty])) $properties[$htmlProperty] = $tvars['property'][$widgetProperty];
}
if (isset($tvars['property']['errors']) && $tvars['property']['errors']) $properties['class'] = isset($properties['class']) ? sprintf('%s error', $properties['class']) : 'error';
if (isset($tvars['property']['ui:title'])) printf('<label for="%s">%s%s</label>', 
    $properties['name'], 
    $tvars['property']['ui:title'], 
    isset($properties['required']) && $properties['required'] ? '<span class="required">*</span>' : ''
);
if (isset($tvars['property']['ui:description'])) printf('<p class="field-description field-description-%s">%s</p>', $properties['name'], $tvars['property']['ui:description']);
printf('<input %s>', implode(' ', array_filter(array_map(
    function($k, $v) {
        if (is_bool($v)) return $v ? $k : '';
        return sprintf('%s="%s"', $k, htmlspecialchars($v));
    }, 
    array_keys($properties),
    $properties
))));
if (isset($tvars['property']['errors']) && $tvars['property']['errors']) {
    if (count($tvars['property']['errors']) == 1) {
        printf('<span class="errors">%s</span>', htmlspecialchars($tvars['property']['errors'][0]));
    } else {
        printf('<ul class="errors">%s</ul>', implode('', array_map(
            function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
            $tvars['property']['errors']
        )));
    }
}