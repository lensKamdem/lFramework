<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Base;

use LFramework\Helpers\ArrayMethods as ArrayMethods;
use LFramework\Helpers\StringMethods as StringMethods;

/**
 * Inspector inspects the Doc comments of a class and gives the required metadata
 * needed to change the behavior of a class
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class Inspector
{
    /** 
     * @var Object class 
     */
    protected $_class;
    /**
     * @var array  class metadata
     */
    protected $_meta = array(
        "class" => array(),
        "proporties" => array(),
        "methods" => array()
    );
    /**
     * @var array class properties
     */
    protected $_properties = array();
    /**
     * @var array class methods 
     */
    protected $_methods = array();
    
    /**
     * Instantiates Inspector class
     *    
     * @param Object $class class 
     */
    public function __construct($class) 
    {
        $this->_class = $class;
    }
    /**
     * Gets the class comments of $class
     * 
     * @return String comments
     */
    protected function _getClassComment() 
    {
        $reflection = new \ReflectionClass($this->_class);
        return $reflection->getDocComment();
    }
    /**
     * Gets the class properties of $class
     * 
     * @return String properties
     */
    protected function _getClassProperties() 
    {
        $reflection = new \ReflectionClass($this->_class);
        return $reflection->getProperties(); 
    }
    /**
     * Gets the class methods of $class
     * 
     * @return String methods
     */
    protected function _getClassMethods() 
    {
        $reflection = new \ReflectionClass($this->_class);
        return $reflection->getMethods();
    }
    /**
     * Gets the comments of class property $property
     * 
     * @param String $property class property
     * @return String comments
     */
    protected function _getPropertyComment($property) 
    {
        $reflection = new \ReflectionProperty($this->_class, $property);
        return $reflection->getDocComment();
    }
    /**
     * Gets the comments of the class method $method
     * 
     * @param String $method class method
     * @return String comments
     */
    protected function _getMethodComment($method) 
    {
        $reflection = new \ReflectionMethod($this->_class, $method);
        return $reflection->getDocComment();
    }
    /**
     * Parses comment strings to retrieve metadata
     * 
     * @param String $comment comments
     * @return array metadata
     */
    protected function _parse($comment) 
    {
        $meta = array();
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
        $matches = StringMethods::match($comment, $pattern);
       
        if ($matches != null) {
            foreach ($matches as $match) {
                $parts = ArrayMethods::clean(
                    ArrayMethods::trim(
                        StringMethods::split($match, "[\s]", 2))
                    );
                $meta[$parts[0]] = true;
                if(sizeof($parts) > 1 ) {
                    $meta[$parts[0]] = ArrayMethods::clean(
                        ArrayMethods::trim(
                            StringMethods::split($parts[1], ","))
                        );
                }
            }
        }
        return $meta;
    }
    /**
     * Gets class metadata
     * 
     * @return array metadata of the class
     */
    public function getClassMeta()
    {
        if (!isset($_meta["class"])) {
            $comment = $this->_getClassComment();
            if (!empty($comment)) {
                $_meta["class"] = $this->_parse($comment);
            }
            else {
                $_meta["class"] = null;
            }
        }
        return $_meta["class"];
    }
    /**
     * Gets class properties
     * 
     * @return array properties
     */
    public function getClassProperties() 
    {
        if (!isset($_properties)) {
            $properties = $this->_getClassProperties();
            foreach ($properties as $property) {
                $_properties[] = $property->getName();
            }
        }
        return $_properties; 
    }
    /**
     * Get class methods
     * 
     * @return array methods
     */
    public function getClassMethods() 
    {
        if(!isset($_methods)) {
            $methods = $this->_getClassMethods();
            foreach ($methods as $method) {
                $_methods[] = $methods->getName();
            }
        }
        return $_methods;
    }
    /**
     * Gets property metadata
     * 
     * @param String $property property
     * @return array property metadata
     */
    public function getPropertyMeta($property) 
    {
        if (!isset($_meta["properties"][$property])) {
            $comment = $this->_getPropertyComment($property);
            if (!empty($comment)) {
                $_meta["properties"][$property] = $this->_parse($comment);
            }
            else {
                $_meta["properties"][$property] = null;
            }
        }
        return $_meta["properties"][$property];
    }
    /**
     * Gets method metadata
     * 
     * @param String $method method
     * @return array method metadata
     */
    public function getMethodMeta($method) 
    {
        if (!isset($_meta["action"][$method])) {
            $comment = $this->_getMethodComment($method);
            if (!empty($comment)) {
                $_meta["methods"][$method] = $this->_parse($comment);
            }
            else {
                 $_meta["methods"][$method] = null;
            }
        }
        return $_meta["methods"][$method];
    }
 

}
