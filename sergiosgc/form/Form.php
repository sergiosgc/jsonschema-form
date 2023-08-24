<?php
namespace sergiosgc\form;

class Form {
    public $title = '';
    public $description = '';
    public $action = '';
    public $enctype = 'application/x-www-form-urlencoded';
    public $htmlID = '';
    public $class = '';
    public $required = [];
    public $properties = [];
    public static $componentCallback = [ 'sergiosgc\form\Form', 'defaultComponentCallback' ];
    public static function defaultComponentCallback() { throw new Exception('Component callback has not beed configured'); }
    public static $htmlPropertyMaps = [
        'global' => [
            'ui:accesskey' => 'accesskey',
            'ui:autocapitalize' => 'autocapitalize',
            'ui:class' => 'class',
            'ui:contenteditable' => 'contenteditable',
            'ui:contextmenu' => 'contextmenu',
            'ui:dir' => 'dir',
            'ui:draggable' => 'draggable',
            'ui:dropzone' => 'dropzone',
            'ui:exportparts' => 'exportparts',
            'ui:hidden' => 'hidden',
            'ui:id' => 'id',
            'ui:inputmode' => 'inputmode',
            'ui:is' => 'is',
            'ui:itemid' => 'itemid',
            'ui:itemprop' => 'itemprop',
            'ui:itemref' => 'itemref',
            'ui:itemscope' => 'itemscope',
            'ui:itemtype' => 'itemtype',
            'ui:lang' => 'lang',
            'ui:part ' => 'part ',
            'ui:slot' => 'slot',
            'ui:spellcheck ' => 'spellcheck ',
            'ui:style' => 'style',
            'ui:tabindex' => 'tabindex',
            'ui:title' => 'title',
            'ui:translate ' => 'translate ',
        ],
        'textarea' => [
            'ui:autocomplete' => 'autocomplete',
            'ui:autofocus' => 'autofocus',
            'ui:cols' => 'cols',
            'ui:disabled' => 'disabled',
            'ui:form' => 'form',
            'ui:maxlength' => 'maxlength',
            'ui:minlength' => 'minlength',
            'ui:name' => 'name',
            'ui:placeholder' => 'placeholder',
            'ui:readonly' => 'readonly',
            'ui:required' => 'required',
            'ui:rows' => 'rows',
            'ui:spellcheck' => 'spellcheck',
            'ui:wrap' => 'wrap',
        ]
    ];

    public static $typeWidgetMap = [
        'hidden' => 'hidden',
        'text' => 'text',
        'integer' => 'text',
        'color' => 'text',
        'date' => 'text',
        'time' => 'text',
        'timestamp' => 'text',
        'email' => 'text',
        'range' => 'text',
        'telephone' => 'text',
        'url' => 'text',
        'password' => 'text',
        'submit' => 'submit',
        'json' => 'textarea',
        'fieldset' => 'Fieldset',
        'wizard-step' => 'WizardStep'
    ];
    public static $typeUIOptionsMap = [
        'integer' => [ 'ui:inputType' => 'number' ],
        'color' => [ 'ui:inputType' => 'color' ],
        'date' => [ 'ui:inputType' => 'date' ],
        'time' => [ 'ui:inputType' => 'time' ],
        'timestamp' => [ 'ui:inputType' => 'datetime-local' ],
        'email' => [ 'ui:inputType' => 'email' ],
        'range' => [ 'ui:inputType' => 'range', 'min' => 1, 'max' => 10 ],
        'telephone' => [ 'ui:inputType' => 'tel' ],
        'url' => [ 'ui:inputType' => 'url' ],
    ];
    public static $uiDefaultsMap = [
        'ui:title' => 'title',
        'ui:description' => 'description',
        'ui:value' => 'default'
    ];
    public static $propertyDefaultHandlers = [ ['\sergiosgc\form\Form', 'setWidgetDefaults' ]];
    public function __construct($definition) {
        if (is_string($definition)) $definition = json_decode($definition, true);
        foreach (['title', 'description', 'action', 'method', 'enctype', 'htmlID', 'class', 'required', 'properties'] as $field) if (isset($definition[$field])) $this->$field = $definition[$field];
    }
    public function setValues($values) {
        if (interface_exists('\sergiosgc\crud\Describable') && $values instanceof \sergiosgc\crud\Describable) {
            $values = array_reduce(array_keys($values::describeFields()), 
                function($acc, $field) use ($values) { 
                    try {
                        if (isset($values->$field)) $acc[$field] = $values->$field; 
                    } catch (\Error $e) { } // Access is not possible (property is either private or protected and no __get handles access. Ignore property
                    return $acc; 
                }, 
                []);
        } else {
            $values = array_reduce(array_keys((array) $values), function($acc, $field) use ($values) { $acc[$field] = $values[$field]; return $acc; }, []);
        }
        foreach ($values as $k => $v) $this->setValue($k, $v);
    }
    public function setValue($propertyName, $value) {
        if (isset($this->properties[$propertyName])) $this->properties[$propertyName]['value'] = $value;
    }
    public function getValue($propertyName) {
        if (!isset($this->properties[$propertyName]) || !isset($this->properties[$propertyName]['value'])) return null;
        return $this->properties[$propertyName]['value'];
    }
    public function setErrors($values, &$on = null) {
        if (is_null($on)) {
            $values = array_map(function($value) { 
                if (!is_array($value)) $value = [ (string) $value ];
                return array_values($value);
            }, $values);
            $on = &$this->properties;
        }
        foreach($on as $propertyName => &$property) {
            if (!isset($property['errors'])) $property['errors'] = [];
            if (!is_array($property['errors'])) $property['errors'] = [ (string) $property['errors'] ];
            if (isset($values[$propertyName])) $property['errors'] = array_merge($property['errors'], $values[$propertyName]);
            if (isset($property['properties'])) $this->setErrors($values, $property['properties']);
        }
        return $on;
    }
    public static function addPropertyDefaultHandler($handler) {
        if (!is_callable($handler)) throw new Exception('Handler must be callable');
        \array_unshift(static::$propertyDefaultHandlers, $handler);
    }
    protected static function _setDefaults($existing, $defaults) {
        return array_reduce(
            array_keys($defaults),
            function ($carry, $k) use ($defaults) {
                $carry[$k] = isset($carry[$k]) ? $carry[$k] : $defaults[$k];
                return $carry;
            },
            $existing);
    }
    public static function setWidgetDefaults($properties) {
        foreach ($properties as $name => $definition) {
            $defaults = [];
            if (isset(static::$typeWidgetMap[$definition['type']])) $defaults['ui:widget'] = static::$typeWidgetMap[$definition['type']];
            foreach (static::$uiDefaultsMap as $uiProperty => $baseProperty) if (isset($definition[$baseProperty])) $defaults[$uiProperty] = $definition[$baseProperty];
            $properties[$name] = static::_setDefaults($properties[$name], $defaults);
            $properties[$name] = static::_setDefaults($properties[$name], isset(static::$typeUIOptionsMap[$definition['type']]) ? static::$typeUIOptionsMap[$definition['type']] : []);
            if (isset($properties[$name]['properties'])) $properties[$name]['properties'] = static::setWidgetDefaults($properties[$name]['properties']);
        }
        return $properties;
    }
    public function runDefaultHandlers() {
        foreach (static::$propertyDefaultHandlers as $handler) $this->properties = call_user_func($handler, $this->properties);
    }
}
