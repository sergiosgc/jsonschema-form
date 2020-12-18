<?php
$properties = [ 
    'name' => $_REQUEST['name'],
    'type' => 'hidden'
];
foreach([
    'ui:class' => 'class',
    'value' => 'value',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
] as $widgetProperty => $htmlProperty) {
    if (isset($_REQUEST['property'][$widgetProperty])) $properties[$htmlProperty] = $_REQUEST['property'][$widgetProperty];
}
?>
----
<com.sergiosgc.element tagname="input" properties="$properties" />