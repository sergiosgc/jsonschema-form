<?php
(function() {
    // Unpack variables in scope
    foreach (func_get_args()[0] as $veryRandomString137845ToAvoidCollisionsKey => $veryRandomString137845ToAvoidCollisionsValue) $$veryRandomString137845ToAvoidCollisionsKey = $veryRandomString137845ToAvoidCollisionsValue;
    unset($veryRandomVariableNameForThescriptFile);
    unset($veryRandomString137845ToAvoidCollisionsKey);
    unset($veryRandomString137845ToAvoidCollisionsValue);
    // Template PHP code
?><?php
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

    // Template components
ob_start(); // com/sergiosgc/Element
?><?php if ($form->title) { ?><h2 ><?php ob_start();print(@$form->title);print(ob_get_clean()); print('</h2>'); // h2
?><?php } ?><?php if ($form->description) { ?><p class="description"><?php ob_start();print(@$form->description);print(ob_get_clean()); print('</p>'); // p
?><?php } ?><?php 
foreach($form->properties as $name => $definition) {
    if (!isset($definition['ui:widget'])) throw new Exception(sprintf('No ui:widget defined for property %s', $name));
    call_user_func( \sergiosgc\form\Form::$componentCallback, 
        'com/sergiosgc/form/widget/' . $definition['ui:widget'],
        [
            'name' => $name,
            'property' => $definition
        ]);
}
?><?php 
\app\Template::component(
    'com/sergiosgc/Element',
    [
        'content' => ob_get_clean(),
        'tagname' => 'form', 
        'properties' => @$properties
    ]
);
})(get_defined_vars());