<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Model
 *
 * @author Lens
 */

use LFramework;
use LFramework\Base\BaseClass as BaseClass;
use LFramework\Base\Inspector as Inspector;
use LFramework\Helpers\StringMethods as StringMethods;
use LFramework\Exception\InvalidArgumentException as InvalidArgumentException;
use LFramework\Exception\InvalidMethodException as InvalidMethodException;

class Model extends BaseClass {
    
    /**
     * @readwrite
     */
    protected $_table;
    /**
     * @readwrite
     */
    protected $_connector;
    /**
     * @readwrite
     */
    protected $_types = array(
        "autonumber",
        "text",
        "integer",
        "decimal",
        "boolean",
        "datetime"
    );
    /**
     * @var
     * @readwrite
     */
    protected $_columns = array();
    /**
     * @readwrite
     */
    protected $_primaryColumn;
    /**
    * @readwrite
    */
    protected $_validators = array(
        "required" => array(
            "handler" => "_validateRequired",  
            "message" => "The {0} field is required"
        ),
        "alpha" => array(
            "handler" => "_validateAlpha",
            "message" => "The {0} field can only contain letters"
        ),
        "numeric" => array(
            "handler" => "_validateNumeric",
            "message" => "The {0} field can only contain numbers"
        ),
        "alphanumeric" => array(
            "handler" => "_validateAlphaNumeric",
            "message" => "The {0} field can only contain letters and numbers"
        ),
        "max" => array(
            "handler" => "_validateMax",
            "message" => "The {0} field must contain less than {2} characters"
        ),
        "min" => array(
            "handler" => "_validateMin",
            "message" => "The {0} field must contain more than {2} characters"
        )
    );
    /**
    * @readwrite
    */
    protected $_errors = array();
    
    
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->getTable();
        $this->load();
    }   
    public function getTable() {
        if (empty($this->_table)) {
            $table = strtolower(StringMethods::singular(get_class($this)));
            if (strpos($table, "\\")) {
                $pos = strrpos($table, "\\");
                $table = substr($table, $pos+1);
                $this->_table = $table;
            }
        }
        return $this->_table;
    }
    public function getConnector() {
        if (empty($this->_connector)) {
            $db = LFramework::$application->getDb();
            if (!$db) {
                throw new ArgumentException("No connector availible");
            }
            $this->_connector = $db->connect();
        }
        return $this->_connector;
    }
    public function getColumns() {
        if (empty($this->_columns)) {
            $primaries = 0;
            $columns = array();
            $class = get_class($this);
            $types = $this->_types;
            $inspector = new Inspector($this);
            $properties = $inspector->getClassProperties();
            $first = function($array, $key) {
                if (!empty($array[$key]) && sizeof($array[$key]) == 1) {
                    return $array[$key][0];
                }
                return null;
            };
            foreach ($properties as $property) {
                $propertyMeta = $inspector->getPropertyMeta($property);
                if (!empty($propertyMeta["@column"])) {
                    $name = preg_replace("#^_#", "", $property);
                    $primary = !empty($propertyMeta["@primary"]) ? $propertyMeta["@primary"]: null;
                    $type = $first($propertyMeta, "@type");
                    $length = $first($propertyMeta, "@length");
                    $index = !empty($propertyMeta["@index"]);
                    $foreign = $first($propertyMeta, "@foreign");
                    $readwrite = !empty($propertyMeta["@readwrite"]);
                    $read = !empty($propertyMeta["@read"]) || $readwrite;
                    $write = !empty($propertyMeta["@write"]) || $readwrite;
                    $validate = !empty($propertyMeta["@validate"]) ? 
                        $propertyMeta["@validate"] : false;
                    $label = $first($propertyMeta, "@label");
                    if (!in_array($type, $types)) {
                        throw new InvalidArgumentException("{$type} is not a valid type");
                    }
                    if ($primary) {
                        $primaries++;
                    }
                    $columns[$name] = array(
                        "raw" => $property,
                        "name" => $name,
                        "primary" => $primary,
                        "type" => $type,
                        "length" => $length,
                        "index" => $index,
                        "foreign" => $foreign,
                        "read" => $read,
                        "write" => $write,
                        "validate" => $validate,
                        "label" => $label
                    );
                }
            }
            $order = function($columns, $class) {
                $superClass = get_parent_class($class);
                $superProps = get_class_vars($superClass);
                $superColumns = array();
                foreach ($columns as $name => $column) {
                    foreach ($superProps as $props => $value) {
                        if ($props == $column["raw"]) {
                            $superColumns[$name] = $column;
                            unset($columns[$name]);
                        }
                    }
                }
                return $array = array_merge($superColumns, $columns);
            };
            $columns = $order($columns, $class);
            if ($primaries !== 1) {
                    throw new InvalidArgumentException("{$class} must have exactly one primary column");
            }
            $this->_columns = $columns;
        }
        return $this->_columns;
    }
    public function getColumn($name) {
        if (!empty($this->_columns[$name])) {
            return $this->_columns[$name];
        }
        return null;
    }
    public function getPrimaryColumn() {
        if (!isset($this->_primaryColumn)) {
            $primary;
            foreach ($this->getColumns() as $column) {
                if ($column["primary"]) {
                    $primary = $column;
                    break;
                }
            }
            $this->_primaryColumn = $primary;
        }
        return $this->_primaryColumn;
    }
    public function load() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        $name = $primary["name"];
        if (!empty($this->$raw)) {
            $previous = $this->getConnector()
                ->query()
                ->from($this->_table)
                ->where("{$name} = ?", $this->$raw)
                ->first();
            if ($previous == null) {
                throw new InvalidArgumentException("Primary key value invalid");
            }
            foreach ($previous as $key => $value) {
                $prop = "_{$key}";
                if (!empty($value) && !isset($this->$prop)) {
                $this->$key = $value;
                }
            }
        }
    }
    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        $name = $primary["name"];
        $query = $this->getConnector()
            ->query()
            ->from($this->_table);
        if (!empty($this->$raw)) {
            $query->where("{$name} = ?", $this->$raw);
        }
        $data = array();
        foreach ($this->getColumns() as $key => $column) {
            if (!$column["read"]) {
                $prop = $column["raw"];
                $data[$key] = $this->$prop;
                continue;
            }
            if ($column != $this->getPrimaryColumn() && $column) {
                $method = "get".ucfirst($key);
                $data[$key] = $this->$method();
                continue;
            }
        }
        $result = $query->save($data);
        if ($result > 0) {
            $this->$raw = $result;
        }
        return $result;
    }
    public function delete() {
        $primary = $this->getColumns();
        $raw = $primary["raw"];
        $name = $primary["name"];
        if (!empty($this->$raw)) {
            return $this->getConnector()
                ->query()
                ->from($this->_table)
                ->where("{$name} = ?", $this->$raw)
                ->delete();
        }
    }
    public static function deleteAll($where = array()) {
        $instance = new static();
        $query = $instance->getConnector()
            ->query()
            ->from($instance->_table);
        foreach ($where as $clause => $value) {   
            $query->where($clause, $value);
        }
        return $query->delete();
    }
    public static function all($where = array(), $fields = array("*"), $order = null,
        $direction = null, $limit = null, $page = null) 
    {
        $model = new static();
        return $model->_all($where, $fields, $order, $direction, $limit, $page);
    }
    
    protected function _all($where = array(), $fields = array("*"), $order = null, 
        $direction = null, $limit = null, $page = null) 
    {
        $query = $this->getConnector()
            ->query()
            ->from($this->_table, $fields);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        if ($order != null) {
            $query->order($order, $direction);
        }
        if ($limit != null) {
            $query->limit($limit, $page);
        }
        $rows = array();
        $class = get_class($this);
        foreach ($query->all() as $row) {
            $rows[] = new $class(
                $row
            );
        }
        return $rows;
    }
    public static function first($where = array(), $fields = array("*"), $order = null,
        $direction = null) 
    {
        $model = new static();
        return $model->_first($where, $fields, $order, $direction);
    }
    protected function _first($where = array(), $fields = array("*"), $order = null, $direction = null) 
    {
        $query = $this->getConnector()
            ->query()
            ->from($this->_table, $fields);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        if ($order != null) {
            $query->order($order, $direction);
        }
        $first = $query->first();
        $class = get_class($this);
        if ($first) {
            return new $class(
                $query->first()
            );
        }
        return null;
    }
    public static function count($where = array()) {
        $model = new static();
        return $model->_count($where);
    }
    protected function _count($where = array()) {
        $query = $this->getConnector()
            ->query()
            ->from($this->_table);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        return $query->count();
    }
    protected function _validateRequired($value) {
        return !empty($value);
    }
    protected function _validateAlpha($value) {
        return StringMethods::match($value, "#^([a-zA-Z]+)$#");
    }
    protected function _validateNumeric($value) {
        return StringMethods::match($value, "#^([0–9]+)$#");
    }
    protected function _validateAlphaNumeric($value) {
        return StringMethods::match($value, "#^([a-zA-Z0-9]+)$#");
    }
    protected function _validateMax($value, $number) {
        return strlen($value) <= (int) $number;
    }
    protected function _validateMin($value, $number) {
        return strlen($value) >= (int) $number;
    }
    public function validate() {
        $this->_errors = array();
        $columns = $this->getColumns();
        foreach ($columns as $column) {
            if ($column["validate"]) {
                $error = false;
                $pattern = "#[a-z] +\(([a-zA-Z0-9, ]+)\)#";
                $raw = $column["raw"];
                $name = $column["name"];
                $validators = $column["validate"];
                $label = $column["label"];
                $defined = $this->getValidators();
                foreach ($validators as $validator) {
                    $function = $validator;
                    $arguments = array(
                        $this->$raw
                    );
                    $match = StringMethods::match($validator, $pattern);
                    if (count($match)>0) {
                        $matches = StringMethods::split($match[0], ",\s*");
                        $arguments = array_merge($arguments, $matches);
                        $offset = StringMethods::indexOf($validator, "(");
                        $function = substr($validator, 0, $offset);
                    }
                    if (!isset($defined[$function])) {
                        throw new InvalidMethodException("The {$function} validator is not defined");
                    }
                    $template = $defined[$function];
                    if (!call_user_func_array(array($this, $template["handler"]), $arguments)) {
                        $replacements = array_merge(array(
                            $label ? $label : $raw
                        ), $arguments);
                        $message = $template["message"];
                        foreach ($replacements as $i => $replacement) {
                            $message = str_replace("{{$i}}", $replacement, $message);
                        }
                        if (!isset($this->_errors[$name])) {
                            $this->_errors[$name] = array();
                        }
                        $this->_errors[$name][] = $message;
                        $error = true;
                    }
                }
            }
        }
        return !$error;
    }
    
}
