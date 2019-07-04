<?php
namespace sergiosgc\form;

class Form {
    public $title = '';
    public $description = '';
    public $action = '';
    public $htmlID = '';
    public $class = '';
    public $required = [];
    public $properties = [];
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
        'submit' => 'submit',
    ];
    public static $typeUIOptionsMap = [
        'integer' => [ 'inputType' => 'number' ],
        'color' => [ 'inputType' => 'color' ],
        'date' => [ 'inputType' => 'date' ],
        'time' => [ 'inputType' => 'time' ],
        'timestamp' => [ 'inputType' => 'datetime-local' ],
        'email' => [ 'inputType' => 'email' ],
        'range' => [ 'inputType' => 'range', 'min' => 1, 'max' => 10 ],
        'telephone' => [ 'inputType' => 'tel' ],
        'url' => [ 'inputType' => 'url' ],
    ];
    public static $uiDefaultsMap = [
        'ui:title' => 'title',
        'ui:description' => 'description',
        'ui:value' => 'default'
    ];
    public static $propertyDefaultHandlers = [ ['\sergiosgc\form\Form', 'setWidgetDefaults' ]];
    public function __construct($definition) {
        if (is_string($definition)) $definition = json_decode($definition, true);
        foreach (['title', 'description', 'action', 'htmlID', 'class', 'required', 'properties'] as $field) if (isset($definition[$field])) $this->$field = $definition[$field];
    }
    public function setValues($values) {
        $values = (array) $values;
        foreach ($values as $k => $v) if (isset($this->properties[$k])) $this->properties[$k]['value'] = $v;
    }
    public static function addPropertyDefaultHandler($handler) {
        if (!is_callable($handler)) throw new Exception('Handler must be callable');
        static::$propertyDefaultHandlers[] = $handler;
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
            if (isset(static::$typeUIOptionsMap[$definition['type']])) $properties[$name]['ui:options'] = static::_setDefaults(isset($definition['ui:options']) ? $definition['ui:options'] : [], static::$typeUIOptionsMap[$definition['type']]);
        }
        return $properties;
    }
    public function output() {
        $tvars = [];

        foreach (static::$propertyDefaultHandlers as $handler) $this->properties = call_user_func($handler, $this->properties);
        $attributes = ['method' => 'POST'];
        if ($this->class) $attributes['class'] = htmlspecialchars($this->class);
        if ($this->htmlID) $attributes['id'] = htmlspecialchars($this->htmlID);
        if ($this->action) $attributes['action'] = htmlspecialchars($this->action);
        printf('<form %s>', implode(' ', array_map(
            function($k, $v) { return sprintf('%s="%s"', $k, $v); }, 
            array_keys($attributes), 
            $attributes)));
        if ($this->title) printf('<h2>%s</h2>', $this->title);
        if ($this->description) printf('<p class="description">%s</p>', $this->description);
        foreach ($this->properties as $name => $definition) {
            $tvars['propertyName'] = $name;
            $tvars['property'] = $definition;
            if (!isset($definition['ui:widget'])) throw new Exception(sprintf('No ui:widget defined for property %s', $name));
            \sergiosgc\output\Negotiated::$singleton->template(sprintf('/_/sergiosgc/form/widget/%s/', $definition['ui:widget']), $tvars);
        }
        printf('</form>');
    }
}
\sergiosgc\output\Negotiated::registerComponentTemplatePath(realpath(__DIR__ . '/../../templates'));
