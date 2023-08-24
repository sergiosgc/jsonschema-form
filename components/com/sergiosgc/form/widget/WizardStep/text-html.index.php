<fieldset class="wizard-step"><?php
if (isset($_REQUEST['property']['label'])) printf('<label>%s</label>', $_REQUEST['property']['label']);
if (isset($_REQUEST['property']['logo'])) printf('<img class="wizard-logo" src="%s">', $_REQUEST['property']['logo']);
if (isset($_REQUEST['property']['title'])) printf('<h1 class="wizard-title">%s</h1>', $_REQUEST['property']['title']);
if (isset($_REQUEST['property']['subtitle'])) printf('<h2 class="wizard-subtitle">%s</h1>', $_REQUEST['property']['subtitle']);
foreach($_REQUEST['property']['properties'] as $name => $definition) {
    if (!isset($definition['ui:widget'])) throw new Exception(sprintf('No ui:widget defined for property %s', $name));
    call_user_func( \sergiosgc\form\Form::$componentCallback, 
        'com/sergiosgc/form/widget/' . $definition['ui:widget'],
        [
            'name' => $name,
            'property' => $definition
        ]);
}?>
<input name="back" type="button" class="wizard" value="<?= __('Go back') ?>" >
<input name="continue" type="button" class="wizard primary" value="<?= __('Continue') ?>" >
<?php if (isset($_REQUEST['property']['help'])) printf('<div class="wizard-help">%s</h1>', $_REQUEST['property']['help']);
?></fieldset>
