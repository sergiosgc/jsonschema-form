<?php
(function() {
    // Unpack variables in scope
    foreach (func_get_args()[0] as $veryRandomString137845ToAvoidCollisionsKey => $veryRandomString137845ToAvoidCollisionsValue) $$veryRandomString137845ToAvoidCollisionsKey = $veryRandomString137845ToAvoidCollisionsValue;
    unset($veryRandomVariableNameForThescriptFile);
    unset($veryRandomString137845ToAvoidCollisionsKey);
    unset($veryRandomString137845ToAvoidCollisionsValue);
    // Template PHP code
?><?php
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

    // Template components
ob_start(); // com/sergiosgc/Element

\app\Template::componentPre(
    'com/sergiosgc/Element',
    [
        'tagname' => 'input', 
        'properties' => @$properties
    ]
);

\app\Template::component(
    'com/sergiosgc/Element',
    [
        'content' => ob_get_clean(),
        'tagname' => 'input', 
        'properties' => @$properties
    ]
);
})(get_defined_vars());