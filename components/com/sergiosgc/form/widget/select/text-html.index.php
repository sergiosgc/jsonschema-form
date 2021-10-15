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
if (isset($_REQUEST['property']['value'])) foreach($_REQUEST['property']['options'] as $idx => $option) $_REQUEST['property']['options'][$idx]['selected'] = ((string) $option['value'] == (string) $_REQUEST['property']['value']);

$label = isset($_REQUEST['property']['ui:title']) ? sprintf('%s%s', 
    $_REQUEST['property']['ui:title'],
    isset($properties['required']) && $properties['required'] ? '<span class="required">*</span>' : ''
) : false;
$descriptionClass = sprintf("field-description field-description-%s", $properties['name']);
foreach ([
    'ui:hideonsinglevalue' => false,
    'ui:readonlyonsinglevalue' => false,
    'ui:selectfirstonsinglevalue' => false
] as $property => $default) if (!isset($_REQUEST['property'][$property])) $_REQUEST['property'][$property] = $default;
foreach ([
    'ui:hideonsinglevalue',
    'ui:readonlyonsinglevalue',
    'ui:selectfirstonsinglevalue',
] as $booleanProperty) $_REQUEST['property'][$booleanProperty] = is_string($_REQUEST['property'][$booleanProperty]) ? $_REQUEST['property'][$booleanProperty] == "true" : (bool) $_REQUEST['property'][$booleanProperty];
if (1 == count($_REQUEST['property']['options']) && $_REQUEST['property']['ui:readonlyonsinglevalue']) {
    $properties['disabled'] = true;
}
if (1 == count($_REQUEST['property']['options']) && $_REQUEST['property']['ui:selectfirstonsinglevalue']) $_REQUEST['property']['options'][0]['selected'] = true;

    // Template components
?><?php if (1 == count($_REQUEST['property']['options']) && $_REQUEST['property']['ui:hideonsinglevalue']) { 
    $value = array_values(array_merge(
        array_map(function($option) { return $option['value']; }, 
            array_filter($_REQUEST['property']['options'], function($option) { return $option['selected'] ?? false; })
        ),
        [ $_REQUEST['property']['value'] ?? $_REQUEST['property']['options'][0]['value'] ]
    ))[0];
    ?><input type="hidden" name="<?= strtr(@$_REQUEST['name'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>" value="<?= strtr(@$value, [ '&' => '&amp;', '"' => '&quot;' ]) ?>"<?php ob_start();
(function($content) { // /input
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</input>');
    }
})(ob_get_clean());?><?php } else { ?><?php  if ($label) { ?><label for="<?= strtr(@$properties['name'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(@$label);print(ob_get_clean()); print('</label>'); // label
?><?php  } ?><?php  if (isset($_REQUEST['property']['ui:description'])) { ?><p class="<?= strtr(@$descriptionClass, [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(@$_REQUEST['property']['ui:description']);print(ob_get_clean()); print('</p>'); // p
?><?php  } ?><?php  if (isset($_REQUEST['property']['readonly'])) { ?><input type="hidden" name="<?= strtr(@$_REQUEST['name'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>" value="<?= strtr(@$_REQUEST['property']['value'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>"<?php ob_start();
(function($content) { // /input
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</input>');
    }
})(ob_get_clean());?><?php  } ?><?php ob_start(); // com/sergiosgc/Element

\app\Template::componentPre(
    'com/sergiosgc/Element',
    [
        'tagname' => 'select', 
        'properties' => @$properties
    ]
);
?><?php   if (isset($_REQUEST['property']['ui:placeholder'])) { ?><option value=""><?php ob_start();print(@$_REQUEST['property']['ui:placeholder']);print(ob_get_clean()); print('</option>'); // option
?><?php   } ?><?php   foreach ($_REQUEST['property']['options'] as $option) { 
                  if ($option['selected'] ?? false) { ?><option value="<?= strtr(@$option['value'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>" selected="selected"><?php ob_start();print(@$option['label']);print(ob_get_clean()); print('</option>'); // option
?><?php    } else { ?><option value="<?= strtr(@$option['value'], [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(@$option['label']);print(ob_get_clean()); print('</option>'); // option
?><?php    } ?><?php   } ?><?php 
\app\Template::component(
    'com/sergiosgc/Element',
    [
        'content' => ob_get_clean(),
        'tagname' => 'select', 
        'properties' => @$properties
    ]
);
?><?php   if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) { ?><?php    if (count($_REQUEST['property']['errors']) == 1) { ?><span class="errors"><?php ob_start();print(@$_REQUEST['property']['errors'][0]);print(ob_get_clean()); print('</span>'); // span
?><?php    } else { 
printf('<ul class="errors">%s</ul>', implode('', array_map(
    function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
    $_REQUEST['property']['errors']
)));
} ?><?php   } ?><?php } ?><?php })(get_defined_vars());