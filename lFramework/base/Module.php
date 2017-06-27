<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Base;

use LFramework;
use LFramework\Base\Registry as Registry;
use LFramework\Exception\InvalidArgumentException as InvalidArgumentException;

/**
 * Module is the base class for module and application classes
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class Module extends Registry
{
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_parameters = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_id;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_parentModule;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layout;
    /**
     * @var type 
     * 
     * @read
     */
    protected $_controllerMap = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_controllerNamespace;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_defaultRoute = "default";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_viewPath;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layoutPath;
    /**
     * @var type 
     * 
     * @read
     */
    protected $_modules = array();
    
    public function __construct($id, $parent = null, $config = array())
    {
        $this->_id = $id;
        $this->_parentModule = $parent;
        parent::__construct($config);
    }
    public static function getInstance()
    {
        $class = get_called_class();
        return isset(LFramework::$application->_loadedModules[$class]) ? 
            LFramework::$application->_loadedModules[$class] : null;
    }
    public static function setInstance($instance)
    {
        if ($instance === null) {
            unset(LFramework::$application->_loadedModules[get_called_class()]);
        } else {
            LFramework::$application->_loadedModules[get_class($instance)] = $instance;
        }
    }
    public function initialize()
    {
        if ($this->_controllerNamespace === null) {
            $class = get_class($this);
            if (($pos = strrpos($class, "\\")) !== false) {
                $this->_controllerNamespace = substr($class, 0, $pos) . "\\Controllers";
            }
        }
    }
    public function getUniqueId()
    {
        return $this->_parentmodule ? ltrim($this->_parentmodule->getUniqueId() .
            "/" . $this->_id, "/") : $this->_id;
    }
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }
        return $this->_basePath;
    }
    public function setBasePath($path)
    {
        $p = realpath($path);
        if ($p !== false && is_dir($p)) {
            $this->_basePath = $p;
        } else {
            throw new InvalidArgumentException("The directory does not exist: {$path}");
        }
    }
    public function getControllerPath()
    {
        return strtolower(str_replace("\\", DS , $this->_controllerNamespace));
    }
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->getBasePath() . DS . "views";
        }
        return $this->_viewPath;
    }
    public function getLayoutPath()
    {
        if ($this->_layoutPath === null) {
            $this->_layoutPath = $this->getViewPath() . DS . 'layouts';
        }

        return $this->_layoutPath;
    }
    public function getModule($id, $load = true)
    {
        if (($pos = strpos($id, "/")) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));
            return $module === null ? null : $module->getModule(substr($id, $pos + 1));
        }

        if (isset($this->_modules[$id])) {
            if ($this->_modules[$id] instanceof Module) {
                return $this->_modules[$id];
            }
            elseif ($load) {
                /* @var $module Module */
                try {
                    $class = $this->_modules[$id];
                    $module = new $class($id, $this);
                    static::setInstance($module);
                    return $this->_modules[$id] = $module;
                }
                catch (\Exception $e) {
                    throw new Exception(" error: class = {$class}");
                }
            }
        }
        return null;
    }
    public function setModule($id, $module) 
    {
        if ($module === null) {
            unset($this->_modules[$id]);
        } else {
            $this->_modules[$id] = $module;
        }
    }
    public function setModules($modules)
    {
        foreach ($modules as $id => $module) {
            $this->_modules[$id] = $module;
        }
    }
    public function hasModule($id)
    {
        if (($pos = strpos($id, "/")) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));

            return $module === null ? false : $module->hasModule(substr($id, $pos + 1));
        } else {
            return isset($this->_modules[$id]);
        }
    }
    
    
    
    
}
