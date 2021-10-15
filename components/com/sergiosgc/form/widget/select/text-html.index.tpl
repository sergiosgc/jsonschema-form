<?php
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
?>
----
<![CDATA[<?php if (1 == count($_REQUEST['property']['options']) && $_REQUEST['property']['ui:hideonsinglevalue']) { 
    $value = array_values(array_merge(
        array_map(function($option) { return $option['value']; }, 
            array_filter($_REQUEST['property']['options'], function($option) { return $option['selected'] ?? false; })
        ),
        [ $_REQUEST['property']['value'] ?? "" ]
    ))[0];
    ?>]]>
 <input type="hidden" name="$_REQUEST['name']" value="$value" />
<![CDATA[<?php } else { ?>]]>
<![CDATA[<?php  if ($label) { ?>]]>
 <label for="$properties['name']">$label</label>
<![CDATA[<?php  } ?>]]>
<![CDATA[<?php  if (isset($_REQUEST['property']['ui:description'])) { ?>]]>
 <p class="$descriptionClass">$_REQUEST['property']['ui:description']</p>
<![CDATA[<?php  } ?>]]>
<![CDATA[<?php  if (isset($_REQUEST['property']['readonly'])) { ?>]]>
 <input type="hidden" name="$_REQUEST['name']" value="$_REQUEST['property']['value']" />
<![CDATA[<?php  } ?>]]>
 <com.sergiosgc.element tagname="select" properties="$properties">
<![CDATA[<?php   if (isset($_REQUEST['property']['ui:placeholder'])) { ?>]]>
  <option value="">$_REQUEST['property']['ui:placeholder']</option>
<![CDATA[<?php   } ?>]]>
<![CDATA[<?php   foreach ($_REQUEST['property']['options'] as $option) { 
                  if ($option['selected'] ?? false) { ?>]]>
  <option value="$option['value']" selected="selected">$option['label']</option>
<![CDATA[<?php    } else { ?>]]>
  <option value="$option['value']">$option['label']</option>
<![CDATA[<?php    } ?>]]>
<![CDATA[<?php   } ?>]]>
 </com.sergiosgc.element>
<![CDATA[<?php   if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) { ?>]]>
<![CDATA[<?php    if (count($_REQUEST['property']['errors']) == 1) { ?>]]>
<span class="errors">$_REQUEST['property']['errors'][0]</span>
<![CDATA[<?php    } else { 
printf('<ul class="errors">%s</ul>', implode('', array_map(
    function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
    $_REQUEST['property']['errors']
)));
} ?>]]>
<![CDATA[<?php   } ?>]]>
<![CDATA[<?php } ?>]]>
