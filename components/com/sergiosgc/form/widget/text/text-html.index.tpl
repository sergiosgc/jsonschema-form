<?php
$properties = [ 
    'name' => $_REQUEST['name'],
    'type' => $_REQUEST['property']['type']
];
foreach([
    'ui:class' => 'class',
    'value' => 'value',
    'ui:value' => 'value',
    'maxLength' => 'maxlength', 
    'minLength' => 'minLength', 
    'validation:pattern' => 'pattern',
    'placeholder' => 'placeholder',
    'readonly' => 'readonly',
    'ui:size' => 'size',
    'ui:autofocus' => 'autofocus',
    'ui:disabled' => 'disabled',
    'validation:required' => 'required',
    'ui:tabIndex' => 'tabindex',
    'ui:inputType' => 'type',
] as $widgetProperty => $htmlProperty) {
    if (isset($_REQUEST['property'][$widgetProperty])) $properties[$htmlProperty] = $_REQUEST['property'][$widgetProperty];
}
if (isset($properties['value']) && $properties['value'] instanceof \DateTime) $properties['value'] = $properties['value']->format(\DateTimeInterface::ISO8601);
$properties['class'] = implode(' ', array_filter([
    isset($properties['class']) ? $properties['class'] : '',
    isset($tvars['property']['errors']) && $tvars['property']['errors'] ? 'error' : ''
]));
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
<com.sergiosgc.element tagname="input" properties="$properties" />
<![CDATA[<?php if (isset($_REQUEST['property']['errors']) && $_REQUEST['property']['errors']) { ?>]]>
<![CDATA[<?php  if (count($_REQUEST['property']['errors']) == 1) { ?>]]>
<span class="errors">$_REQUEST['property']['errors'][0]</span>
<![CDATA[<?php } else { 
printf('<ul class="errors">%s</ul>', implode('', array_map(
    function($err) { return sprintf('<li>%s</li>', htmlspecialchars($err)); },
    $_REQUEST['property']['errors']
)));
} ?>]]>
<![CDATA[<?php } ?>]]>














