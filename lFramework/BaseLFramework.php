<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework;

use LFramework\Exception\InvalidArgumentException as InvalidArgumentException;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;
use LFramework\Exception\UnknownClassException as UnknownClassException;
use LFramework\Base\Exception as Exception;

/**
 * This constant defines a shortcut for directory separator
 */
define("DS", DIRECTORY_SEPARATOR);
/**
 * This constant defines the framework installation directory.
 */
define('LFRAMEWORK_PATH', __DIR__);
/**
 * This constant defines the application root directory
 */
define('LFRAMEWORK_ROOT', dirname(LFRAMEWORK_PATH));
/**
 * This constant defines the application's directory
 */
define('LFRAMEWORK_APP', LFRAMEWORK_ROOT . DS. "application");
/**
 * This constant defines whether the application should be in debug mode or not.
 * Defaults to false.
 */
defined('LFRAMEWORK_DEBUG') or define('LFRAMEWORK_DEBUG', false);
/**
 * This constant defines in which environment the application is running. 
 * Defaults to 'prod', meaning production environment. 
 * The value could be 'prod' (production), 'dev' (development), 'test', 'staging', etc.
 */
defined('LFRAMEWORK_ENV') or define('LFRAMEWORK_ENV', 'prod');
/**
 * Whether the the application is running in production environment
 */
defined('LFRAMEWORK_ENV_PROD') or define('LFRAMEWORK_ENV_PROD', LFRAMEWORK_ENV === 'prod');
/**
 * Whether the the application is running in development environment
 */
defined('LFRAMEWORK_ENV_DEV') or define('LFRAMEWORK_ENV_DEV', LFRAMEWORK_ENV === 'dev');
/**
 * Whether the the application is running in testing environment
 */
defined('LFRAMEWORK_ENV_TEST') or define('LFRAMEWORK_ENV_TEST', LFRAMEWORK_ENV === 'test');

/**
 * This constant defines whether error handling should be enabled. Defaults to false.
 */
defined('LFRAMEWORK_ENABLE_ERROR_HANDLER') or define('LFRAMEWORK_ENABLE_ERROR_HANDLER', false);


/**
 * BaseLFramework is the core helper class for LFramework
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class BaseLFramework
{
    
    /**
     * @var \LFramework\Base\Application the aplication instance
     */
    public static $application;
    /**
     * @var type 
     */
    public static $_logger;
    
    /**
     * Returns the current version of LFramework.
     * @return String the version of LFramework
     */
    public static function getVersion()
    {
        return "1.0.0";
    }
    public static function autoload($className)
    {
        $paths = array(LFRAMEWORK_ROOT);
        // position of last \
        $pos = strrpos(trim($className, "\\"), "\\");
        $file = lcfirst(str_replace("\\", DS, substr($className, 0, $pos))) . DS.
                substr($className, $pos+1) . ".php";
        
        foreach ($paths as $path) {
            $classFile = $path. DS. $file;
            if (file_exists($classFile)) {
                include($classFile);
                return;
            }    
        }
        if (LFRAMEWORK_DEBUG && !class_exists($className, false) 
            && !interface_exists($className, false)
                && !trait_exists($className, false)) {
                throw new UnknownClassException("Class {$className} not found in {$classFile}");
        }
    }
    /**
     * Configures an object with initial property values
     * 
     * @param object $object object to be configured
     * @param array $properties properties' initial values given in terms of name-value pairs
     * @return object the configured object
     */
    public static function configure($object, $properties)
    {
        if (is_array($properties) || is_object($properties)) {
            foreach ($properties as $name => $value) {
                $key = ucfirst($name);
                $method = "set{$key}";
                $object->$method($value);
            }
            return $object;
        }
    }
    public static function createObject($definition)
    {
        if (is_string($definition)) {
            return self::_instantiate($definition);
        }
        elseif (is_array($definition) && isset($definition['class'])) {
            $class = $definition['class'];
            unset($definition['class']);
            return self::_instantiate($class, $definition);
        }
        elseif (is_object($definition)) {
            $class = get_class($definition);
            return self::_instantiate($class);
        }
        elseif (is_array($definition)) {
            throw new InvalidConfigException("Configuration must contain a 'class' element");
        }
        else {
            throw new InvalidConfigException("Unsupported configuration type". gettype($defintion));
        }
    }
    private static function _instantiate($class, $config = array())
    {
        try {
            $object = new $class($config);
        }
        catch (Exception $e) {
            throw new Exception("Unknown class {$class}, or not instantiable!\n\n ". 
                print_r($e));
        }
        return $object;
    }
    
    public static function t($category, $message, $parameters = array(), $language = null)
    {
        if (static::$application !== null) {
            return static::$application->getI18n()->translate($category, $message, $parameters, $language ?: 
                static::$application->getLanguage());
        }
        else {
            $p = [];
            foreach ((array) $parameters as $name => $value) {
                $p['{' . $name . '}'] = $value;
            }

            return ($p === []) ? $message : strtr($message, $p);
        }
       
    }
    public static function getLogger()
    {
        
    }
    public static function setLogger($logger)
    {
        
    }
    public static function traceLog($message, $category = 'application')
    {
        
    }
    public static function errorLog($message, $category = 'application')
    {
        
    }
    public static function warningLog($message, $category = 'application')
    {
        
    }
    public static function infoLog($message, $category = 'application')
    {
        
    }
    public static function powered()
    {
        
    }

}
