<?php
$properties = [ 
    'name' => $_REQUEST['name'],
    'type' => 'checkbox'
];
foreach([
    'ui:class' => 'class',
    'ui:size' => 'size',
    'ui:autofocus' => 'autofocus',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
    'ui:tabIndex' => 'tabindex',
    'ui:placeholder' => 'placeholder'
] as $widgetProperty => $htmlProperty) {
    if (isset($_REQUEST['property'][$widgetProperty])) $properties[$htmlProperty] = $_REQUEST['property'][$widgetProperty];
}
if (isset($_REQUEST['property']['readonly'])) $properties['disabled'] = 'disabled';
foreach ($_REQUEST['property'] as $widgetPropertyName => $widgetProperty) {
    if (substr($widgetPropertyName, 0, strlen('ui:data-')) == 'ui:data-') $properties[substr($widgetPropertyName, strlen('ui:'))] = $widgetProperty;
}
if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) $properties['class'] = isset($properties['class']) ? sprintf('%s error', $properties['class']) : 'error';
if (isset($_REQUEST['property']['value']) && is_bool($_REQUEST['property']['value'])) $_REQUEST['property']['value'] = $_REQUEST['property']['value'] ? 'true' : 'false';
if (isset($_REQUEST['property']['value']) && $_REQUEST['property']['value'] == 'true') $properties['checked'] = 'checked';

if (!isset($_REQUEST['property']['ui:title'])) $_REQUEST['property']['ui:title'] = $_REQUEST['name'];
$label = sprintf('%s%s', 
    $_REQUEST['property']['ui:title'],
    isset($properties['required']) && $properties['required'] ? '<span class="required">*</span>' : ''
);
$descriptionClass = sprintf("field-description field-description-%s", $properties['name']);
?>
----
<label><com.sergiosgc.element tagname="input" properties="$properties" /><![CDATA[<?php echo $label; ?>]]></label>