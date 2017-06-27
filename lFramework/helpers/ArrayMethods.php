<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Helpers;

/**
 * ArrayMethods groupe utility methods used to work with arrays
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */

class ArrayMethods
{
    /**
     * Blocks instantiation
     */
    private function __construct() 
    {
        
    }
    /**
     * Blocks clonning 
     */
    private function __clone() 
    {
        
    }
    /**
     * Removes empty values form $array
     * 
     * @param $array
     * @return array with no empty values
     */
    public static function clean($array) 
    {
        return array_filter($array, function($item){
            return !empty($item);
        });
    }
    /**
     * Removes white spaces from the elements of the array
     * 
     * @param  $array
     * @return trimmed array elements
     */
    public static function trim($array) 
    {
        return array_map(function($item){
            return trim($item); 
        }, $array); 
    }
    
    public static function toObject($array) 
    {
        $result = \stdClass();
        foreach ($array as $key => $value) {
            if(is_array($value)) {
                $result->{$key} = self::toObject($value); 
            }
            else {
                $result->{$key} = $value;
            }
        }
        return $result;
    }
    public static function flatten($array, $return = array()) 
    {
        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $return = self::flatten($value, $return);
            }
            else {
                $return[] = $value;
            }
        }
        return $return;
    }
    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        return $default;
    }
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
    public static function first($array)
    {
        if (is_array($array) && !empty($array)) {
            $first = $array[0];
            return $first;
        }
        
        return;
    }
    public static function extractProps($object) {
        $class = get_class($object);
        $inspector = new \LFramework\Base\Inspector($class);
        $properties = $inspector->getClassProperties();
        
        $parentInspector = new \LFramework\Base\Inspector("LFramework\Base\Model");
        $parentProperties = $parentInspector->getClassProperties();
        foreach ($parentProperties as $property) {
            foreach($properties as $key => $value) {
                if ($properties[$key] == $property) {
                    unset($properties[$key]);
                }
            }
        }
        
        $array = array();
        foreach ($properties as $property) {
            $name = preg_replace("#^_#", "", $property);
            $array[$name] = $object->$property;
        }
        
        return $array;
    }
}
