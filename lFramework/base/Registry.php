<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Base;

use LFramework;
use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

/**
 * Registry stores application components.
 * 
 * Components can be declared in 3 ways: with a string, an object or a configuration
 * array.
 * 
 * php
 * 
 * $register = new Register();
 * $regiser = setComponents(
 *      'router' => 'LFramework\Routing\Router',
 *      'request' => new LFramework\Base\Request(),
 *      'db' => array(
 *              'class' => 'LFramework\Db\Database',
 *              'type' => 'mysql'
 *              )
           ); 
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class Registry extends Component
{
    /**
     * @var type 
     * 
     * @read
     */
    protected $_components = array();
    /**
     * @var type 
     * 
     * @read
     */
    protected $_definitions = array();
    
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }
        else {
            return parent::__get($name);
        }
    }
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_components[$id]) : isset($this->_definitions[$id]);
    }
    public function get($id) {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];        
        } 
        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition)) {
                return $this->_components[$id] = $definition;
            } else {
                return $this->_components[$id] = LFramework::createObject($definition);
            }
        } 
    }
    public function set($id, $definition) {
        if ($definition === null) {
            unset($this->_components[$id], $this->_definitions[$id]);
            return;
        }
        unset($this->_components[$id]);

        if (is_object($definition) || is_string($definition)) {
            // an object or a class name
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            // a configuration array
            if (isset($definition["class"])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The configuration for the {$id} component"
                . " must contain a class element" ); 
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration type for "
                . "the {$id} component: " . gettype($definition));
        }
    }
    public function erase($id) {
        unset($this->_components[$id]);
    }
     public function getComponents($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_components;
    }
    public function setComponents($components)
    {
        foreach ($components as $id => $component)
        {
            $this->set($id, $component);
        }
    }
    

}
