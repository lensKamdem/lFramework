<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Base;

use LFramework\Base\BaseClass as BaseClass;

/**
 * Component is the base class that implements the "property" and "event" features.
 * 
 * The "property" feature is implemented by it parent and it provides the "event feature.
 * 
 * Even is a way to "inject" custom code into existing code at certain places. 
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class Component extends BaseClass
{
    /**
     * @var array the attached event handler (event name => handlers)
     * 
     * @read
     */
    private $_events = array();
    
    public function on($name, $handler)
    {
        if (!empty($name)) {
            if (empty($this->_events[$name])) {
                $this->_events[$name][] = $handler;
            }
            Event::add($this, $name, $handler);
        }
    }
    public function off($name, $handler = null)
    {
        if(!empty($name)) {
            if (!empty($this->_events[$name])) {
                unset($this->_events[$name]);
            }
            elseif (!empty($this->_events[$name][$handler])) {
                unset($this->_events[$name][$handler]);
            }
        }
    }
    public function trigger($name, $paramters = array())
    {
        if(!empty($this->_events[$name])) {
            $classes = array_merge(
                [$this],
                class_parents($this, true),
                class_implements($this, true)
            );
            foreach ($classes as $class) {
                Event::trigger($class, $name, $parameters);
            }
        }
    }

}
