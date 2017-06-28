<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Event
 *
 * @author Lens
 */

class Event {
    /**
     *
     * @var type 
     */
    public static $_events = array();
    
    private function __construct() {}
    private function __clone(){}
    
    public static function add($class, $type, $callback) {
         if (is_object($class)) {
            $class = get_class($class);
        } elseif (is_string($class)) {
            $class = ltrim($class, "\\");
        }
        if (empty(self::$_events[$type][$class])) {
            self::$_events[$type][] = $class; 
        }
        self::$_events[$type][$class][] = $callback;
    }
    public static function trigger($class, $type, $parameters=null) {
        if (is_object($class)) {
            $class = get_class($class);
        } elseif (is_string($class)) {
            $class = ltrim($class, '\\');
        }
        if (!empty(self::$_events[$type][$class])) {
            foreach (self::$_events[$type][$class] as $callback) {
                call_user_func_array($callback, $parameters);
            }
        }
    }
    public static function remove($class, $type, $callback = null) {
        if (!empty(self::$_events[$type][$class])) {
            foreach (self::$_events[$type][$class] as $i => $found) {
                if ($callback == $found) {
                    unset(self::$_events[$type][$class][$i]);
                }
            }
            if (empty($callback)) {
                    unset(self::$_events[$type][$class]);
                }
        }
    }
    
}
