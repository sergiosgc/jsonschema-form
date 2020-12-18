<?php
$properties = [ 'name' => $_REQUEST['name'], 'type' => 'submit' ];
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
?>
----
<com.sergiosgc.element tagname="input" properties="$properties" />