<fieldset><?php
if (isset($_REQUEST['property']['label'])) printf('<label>%s</label>', $_REQUEST['property']['label']);
foreach($_REQUEST['property']['properties'] as $name => $definition) {
    if (!isset($definition['ui:widget'])) throw new Exception(sprintf('No ui:widget defined for property %s', $name));
    call_user_func( \sergiosgc\form\Form::$componentCallback, 
        'com/sergiosgc/form/widget/' . $definition['ui:widget'],
        [
            'name' => $name,
            'property' => $definition
        ]);
}
?></fieldset>