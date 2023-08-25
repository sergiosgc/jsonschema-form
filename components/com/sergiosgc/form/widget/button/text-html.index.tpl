<?php
$properties = [ 'name' => $_REQUEST['name'], 'type' => 'button' ];
foreach([
    'ui:class' => 'class',
    'value' => 'value',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
    'ui:formaction' => 'formaction',
    'ui:formenctype' => 'formenctype',
    'ui:formmethod' => 'formmethod',
    'ui:formnovalidate' => 'formnovalidate',
    'ui:formtarget' => 'formtarget',
] as $widgetProperty => $htmlProperty) {
    if (isset($_REQUEST['property'][$widgetProperty])) $properties[$htmlProperty] = $_REQUEST['property'][$widgetProperty];
}
if (isset($_REQUEST['property']['ui:data'])) foreach($_REQUEST['property']['ui:data'] as $k => $v) $properties[sprintf("data-%s", $k)] = $v;
?>
----
<com.sergiosgc.element tagname="input" properties="$properties" />