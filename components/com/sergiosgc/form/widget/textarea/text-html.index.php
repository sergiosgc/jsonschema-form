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
    'type' => 'text'
];
foreach(array_merge(\sergiosgc\form\Form::$htmlPropertyMaps['global'], \sergiosgc\form\Form::$htmlPropertyMaps['textarea']) as $widgetProperty => $htmlProperty) {
    if (isset($_REQUEST['property'][$widgetProperty])) $properties[$htmlProperty] = $_REQUEST['property'][$widgetProperty];
}
if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) $properties['class'] = isset($properties['class']) ? sprintf('%s error', $properties['class']) : 'error';
$label = isset($_REQUEST['property']['ui:title']) ? sprintf('%s%s', 
    $_REQUEST['property']['ui:title'],
    isset($properties['required']) && $properties['required'] ? '<span class="required">*</span>' : ''
) : false;
$descriptionClass = sprintf("field-description field-description-%s", $properties['name']);

    // Template components
?><?php if ($label) { ?><label for="<?= strtr(@$properties['name'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(@$label);print(ob_get_clean()); print('</label>'); // label
?><?php } ?><?php if (isset($_REQUEST['property']['ui:description'])) { ?><p class="<?= strtr(@$descriptionClass, [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(@$_REQUEST['property']['ui:description']);print(ob_get_clean()); print('</p>'); // p
?><?php } ?><?php ob_start(); // com/sergiosgc/Element
print(@$_REQUEST['property']['value']);
\app\Template::component(
    'com/sergiosgc/Element',
    [
        'content' => ob_get_clean(),
        'tagname' => 'textarea', 
        'properties' => @$properties
    ]
);
?><?php  if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) { ?><?php   if (count($_REQUEST['property']['errors']) == 1) { ?><span class="errors"><?php ob_start();print(@$_REQUEST['property']['errors'][0]);print(ob_get_clean()); print('</span>'); // span
?><?php  } else { 
printf('<ul class="errors">%s</ul>', implode('', array_map(
    function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
    $_REQUEST['property']['errors']
)));
} ?><?php  } ?><?php })(get_defined_vars());