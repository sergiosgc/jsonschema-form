<?php
$properties = [ 'name' => $tvars['propertyName'] ];
foreach([
    'ui:class' => 'class',
    'ui:size' => 'size',
    'ui:autofocus' => 'autofocus',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
    'ui:tabIndex' => 'tabindex',
    'ui:placeholder' => 'placeholder'
] as $widgetPropertyName => $htmlProperty) {
    if (isset($tvars['property'][$widgetPropertyName])) $properties[$htmlProperty] = $tvars['property'][$widgetPropertyName];
}
if (isset($tvars['property']['readonly'])) $properties['disabled'] = 'disabled';
foreach ($tvars['property'] as $widgetPropertyName => $widgetProperty) {
    if (substr($widgetPropertyName, 0, strlen('ui:data-')) == 'ui:data-') $properties[substr($widgetPropertyName, strlen('ui:'))] = $widgetProperty;
}
if (isset($tvars['property']['errors']) && $tvars['property']['errors']) $properties['class'] = isset($properties['class']) ? sprintf('%s error', $properties['class']) : 'error';
if (isset($tvars['property']['value']) && is_bool($tvars['property']['value'])) $tvars['property']['value'] = $tvars['property']['value'] ? 'true' : 'false';
if (isset($tvars['property']['value'])) foreach($tvars['property']['options'] as $idx => $option) $tvars['property']['options'][$idx]['selected'] = ((string) $option['value'] == (string) $tvars['property']['value']);
if (isset($tvars['property']['ui:title'])) printf('<label for="%s">%s%s</label>', 
    $properties['name'], 
    $tvars['property']['ui:title'], 
    isset($properties['required']) && $properties['required'] ? '<span class="required">*</span>' : ''
);
if (isset($tvars['property']['ui:description'])) printf('<p class="field-description field-description-%s">%s</p>', $properties['name'], $tvars['property']['ui:description']);
if (isset($tvars['property']['readonly'])) printf('<input type="hidden" name="%s" value="%s">',
    htmlspecialchars($tvars['propertyName']),
    htmlspecialchars($tvars['property']['value'])
);
printf('<select %s>', implode(' ', array_filter(array_map(
    function($k, $v) {
        if (is_bool($v)) return $v ? $k : '';
        return sprintf('%s="%s"', $k, htmlspecialchars($v));
    }, 
    array_keys($properties),
    $properties
))));
if (isset($tvars['property']['ui:placeholder'])) printf('<option value="">%s</option>', htmlspecialchars($tvars['property']['ui:placeholder']));
foreach($tvars['property']['options'] as $option) printf('<option value="%s"%s>%s</option>', htmlspecialchars($option['value']), $option['selected'] ? ' selected' : '', htmlspecialchars($option['label']));
printf('</select>');
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