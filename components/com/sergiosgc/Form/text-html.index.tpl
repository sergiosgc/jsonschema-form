<?php
$form = $_REQUEST['form'];
$form->setErrors($_REQUEST['errors'] ?? []);
$form->setValues($_REQUEST['values'] ?? []);
$form->method = $form->method ?? 'POST';
$form->runDefaultHandlers();
$properties = [
 'method' => @$form->method,
 'id' => @$form->htmlID,
 'action' => @$form->action,
 'enctype' => @$form->enctype,
 'class' => @$form->class
];
$properties = array_filter($properties);
?>
----
<com.sergiosgc.Element tagname="form" properties="$properties">
<![CDATA[<?php if ($form->title) { ?>]]><h2>$form->title</h2><![CDATA[<?php } ?>]]>
<![CDATA[<?php if ($form->description) { ?>]]><p class="description">$form->description</p><![CDATA[<?php } ?>]]>
<![CDATA[<?php 
foreach($form->properties as $name => $definition) {
    if (!isset($definition['ui:widget'])) throw new Exception(sprintf('No ui:widget defined for property %s', $name));
    call_user_func( \sergiosgc\form\Form::$componentCallback, 
        'com/sergiosgc/form/widget/' . $definition['ui:widget'],
        [
            'name' => $name,
            'property' => $definition
        ]);
}
?>]]>
</com.sergiosgc.Element>
