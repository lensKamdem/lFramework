<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */
namespace LFramework\Base;

use LFramework;
use LFramework\Base\Inspector as Inspector;
use LFramework\Helpers\StringMethods as StringMethods;
use LFramework\Exception\InvalidPropertyException as InvalidPropertyException;
use LFramework\Exception\InvalidMethodException as InvalidMethodException;
use LFramework\Base\Configurable as Configurable;

/**
 * BaseClass is the baseclass of all the LFramework classes. It implements the
 * "property" feature (that is, getters and setters).
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class BaseClass implements Configurable
{

    /**
     * @var object the class inspector
     * @readwrite
     */
    protected $_inspector;
    
    /**
     * Instantiates the class
     * 
     * Creates a new inspector and foreach item in the array, 
     * calls __set($key, $value) 
     * 
     * @param array $config
     */
    public function __construct($config = array()) 
    {
        $this->_inspector = new Inspector($this);
        if (!empty($config)) {
            LFramework::configure($this, $config);
        }
        $this->initialize();
    }
    /**
     * Initializes the object with the given configurations
     */
    public function initialize() 
    {
        
    }
    /**
     * 
     * @return String the name of the class that called the method
     */
    public function className()
    {
        return get_called_class();
    }
    /**
     * It handles getters/setters
     * 
     * @param Strings $name name of method
     * @param array $arguments array of method arguments
     * @return \LFramework\Base\BaseClass|null|BaseClass property
     * @throws WriteOnlyException
     * @throws WriteOnlyException
     */
    public function __call($name, $arguments) 
    {
        if (empty($this->_inspector)) {
            $this->_inspector = new Inspector($this);
            
        }
        $getMatches = StringMethods::match($name, "^get([a-zA-Z0-9]+)$");
        if (sizeof($getMatches) > 0) {
            $normalized = lcfirst($getMatches[0]);
            $property = "_{$normalized}"; 
            if (property_exists($this, $property)) {
                $meta = $this->_inspector->getPropertyMeta($property);
                if (empty($meta["@readwrite"]) && empty($meta["@read"])) {
                    throw new InvalidPropertyException("Property {$property} is write only");
                }
                if (isset($this->$property)) {
                    return $this->$property;
                }
                return null;
            }
            else {
                throw new InvalidPropertyException("Property {$property} does not".
                    " exist in {$this->className()}");
            }
        }
       $setMatches = StringMethods::match($name, "^set([a-zA-Z0-9]+)$");
       if (sizeof($setMatches) > 0) {
            $normalized = lcfirst($setMatches[0]);
            $property = "_{$normalized}"; 
            if (property_exists($this, $property)) {
                $meta = $this->_inspector->getPropertyMeta($property);
                if (empty($meta["@readwrite"]) && empty($meta["@write"])) {
                    throw new InvalidPropertyException("Property {$property} is read only".print_f($e));
                }
                
                $this->$property = $arguments[0];
                return $this;
            }
            else {
               throw new InvalidPropertyException("Property {$property} does not".
                    " exist in {$this->className()} && $normalized");
            } 
        }
       throw new InvalidMethodException("Method {$name} is not defined in the class {$this->className()}");
    }
    /**
     * Gets the property $name
     * 
     * @param type $name
     * @return mixed class method
     */
    public function __get($name) 
    {
        $name = ltrim($name, "_");
        $function = "get".ucfirst($name);
        return $this->$function();
    }
    /**
     * Sets the value of the property $name to $value
     * 
     * @param String $name property name
     * @param mixed $value property value
     * @return mixed class method
     */
    public function __set($name, $value) 
    {
        $name = ltrim($name, "_");
        $function = "set".ucfirst($name);
        return $this->$function($value);
    }
    

}
