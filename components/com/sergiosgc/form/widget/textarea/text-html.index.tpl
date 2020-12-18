<?php
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
?>
----
<![CDATA[<?php if ($label) { ?>]]>
 <label for="$properties['name']">$label</label>
<![CDATA[<?php } ?>]]>
<![CDATA[<?php if (isset($_REQUEST['property']['ui:description'])) { ?>]]>
 <p class="$descriptionClass">$_REQUEST['property']['ui:description']</p>
<![CDATA[<?php } ?>]]>
 <com.sergiosgc.element tagname="textarea" properties="$properties">$_REQUEST['property']['value']</com.sergiosgc.element>
<![CDATA[<?php  if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) { ?>]]>
<![CDATA[<?php   if (count($_REQUEST['property']['errors']) == 1) { ?>]]>
<span class="errors">$_REQUEST['property']['errors'][0]</span>
<![CDATA[<?php  } else { 
printf('<ul class="errors">%s</ul>', implode('', array_map(
    function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
    $_REQUEST['property']['errors']
)));
} ?>]]>
<![CDATA[<?php  } ?>]]>