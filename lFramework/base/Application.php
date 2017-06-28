<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

namespace LFramework\Base;

use LFramework;
use LFramework\Base\Module as Module;
use LFramework\Routing\Simple as Route;

/**
 * Application is the base class for web application classes
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class Application extends Module
{

    /**
     * 
     */
    const STATE_BEGIN = 0;
    /**
     * 
     */
    const STATE_INIT = 1;
    /**
     * 
     */
    const STATE_BEFORE_ROUTING = 2;
    /**
     * 
     */
    const STATE_HANDLING_ROUTING = 3;
    /**
     * 
     */
    const STATE_AFTER_ROUTING = 4;
    /**
     * 
     */
    const STATE_RENDERING_VIEW = 5;
    /**
     * 
     */
    const STATE_END = 6;
    
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_controllerNamespace = "Application\Controllers";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_name = "My Application";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_version = "1.0";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_charset = "UTF-8";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_language = "en-US";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_sourceLanguage = "en-US";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_controller;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layout = "main";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_requestedRoute;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_requestedAction;
    /**
     * @var type 
     * 
     * @read
     */
    protected $_loadedModules = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_bootstrap = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_state;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_vendorPath;   
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_routes = array();
     /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_homeUrl;
    /**
     * @var type 
     * 
     * $readwrite
     */
    protected $_defaultRoute = "home/index";
    
    public function __construct($config = array())
    {   
        LFramework::$application = $this;
        static::setInstance($this);
        
        $this->_state = self::STATE_BEGIN;
        
        $this->preInit($config);
        
        Component::__construct($config);
    }
    public function preInit(&$config)
    {
        if (!isset($config['id'])) {
            throw new InvalidConfigException('The "id" configuration for the Application is required.');
        }
        if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new InvalidConfigException('The "basePath" configuration for the '
                . 'Application is required.');
        }
        if (isset($config['vendorPath'])) {
            $this->setVendorPath($config['vendorPath']);
            unset($config['vendorPath']);
        } else {
            // set "@vendor"
            $this->getVendorPath();
        }
        if (isset($config['timeZone'])) {
            $this->setTimeZone($config['timeZone']);
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {
            $this->setTimeZone('UTC');
        }
        
        // merge core components with custom components
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }

    }
    public function initialize()
    {
        $this->_state = self::STATE_INIT;
        $this->_bootstrap();
    }
    private function _bootstrap()
    {
        // Define web root and url
        $request = $this->getRequest();
        define("WEBROOT", dirname($request->getScriptFile()));
        define("WEB", $request->getBaseUrl()); 
        
        // Load bootstrap components
        foreach ($this->_bootstrap as $class) {
            $component = null;
            if (is_string($class)) {
                if ($this->has($class)) {
                    $component = $this->get($class);
                } elseif ($this->hasModule($class)) {
                    $component = $this->getModule($class);
                } elseif (strpos($class, "\\") === false) {
                    throw new InvalidConfigException("Unknown bootstrapping component ID: $class");
                }
            }
            if (!isset($component)) {
                $component = \LFramework::createObject($class);
            }
        }
    }
    public function getUniqueId()
    {
        return "";
    }
    public function run()
    {
        try {
            $this->_state = self::STATE_BEFORE_ROUTING;
            
            $this->_state = self::STATE_HANDLING_ROUTING;
            
            $this->getRouter()->handleRouting();
            
            $this->_state = self::STATE_AFTER_ROUTING;
            
            if ($this->_state == self::STATE_RENDERING_VIEW) {
                $this->_state = self::STATE_END;
            }
        }
         catch (Exception $e) {
            // list of exceptions
            $exceptions = array(
                "exception" => array(
                    "ErrorException",
                    "InvalidArgumentException",
                    "InvalidConfigException",
                    "InvalidMethodException",
                    "InvalidServiceException",
                    "UnknownClassException",
                    "InvalidPropertyException" 
               ),
                "pageNotFound" => array(
                    "ActionException",
                    "ControllerException"
                )
            );
            $exception = get_class($e);
             // Find appropriate layout and render error
            foreach ($exceptions as $layout => $classes) {
                foreach ($classes as $class) {
                    if ($class == $exception) {
                        header("Content-type: text/html");
                        include(LFRAMEWORK_APP. DS. "views". DS. "errors". DS. "{$layout}.php");
                        exit;
                    }
                }
            }
            // render fallback layout
            header("Content-type: text/html");
            include(LFRAMEWORK_APP. DS. "views". DS. "errors". DS. "error.php");
            exit;
        }
    }
    public function setRoutes($routes) {
        foreach ($routes as $key => $value) {
            $route = new Route($value);
            $this->getRouter()->addRoute($route);
        }
    }
    public function getVendorPath()
    {
        if ($this->_vendorPath === null) {
            $this->setVendorPath($this->getBasePath() . DS . 'vendor');
        }
        return $this->_vendorPath;
    }
    public function getTimeZone()
    {
        return date_default_timezone_get();
    }
    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }
    public function getHomeUrl()
    {
        if ($this->_homeUrl === null) {
            $this->_homeUrl = $this->getRequest()->getBaseUrl();
            return $this->_homeUrl;
        } else {
            return $this->_homeUrl;
        }
    }
    public function getDb()
    {
        if (isset($this->_components["db"])) {
            return $this->_components["db"];
        }
        
        return $this->_components["db"] = $this->get("db")->configure();
    }
    public function getLog()
    {
        return $this->get("log");
    }
    public function getCache()
    {
        if (isset($this->_components["cache"])) {
            return $this->_components["cache"];
        }
        
        return $this->_components["cache"] = $this->get("cache")->configure();
    }
    public function getRouter()
    {
        return $this->get("router");
    }
    public function getRequest()
    {
        return $this->get("request");
    }
    public function getResponse()
    {
        return $this->get("response");
    }
    public function getSession()
    {
        if (isset($this->_components["session"])) {
            return $this->_components["session"];
        }
        
        return $this->_components["session"] = $this->get("session")->configure();
    }
    public function getUser()
    {
        return $this->get("user");
    }
    public function getView()
    {
        return $this->get("view");
    }
    public function getAssetManager()
    {
        return $this->get("assetManager");
    }
    public function getI18n()
    {
        return $this->get("i18n");
    }
    public function coreComponents()
    {
        return array(
            "db" => ["class" => "LFramework\Db\Database"],
            "cache" => [
                "class" => "LFramework\Caching\Cache",
                "type" => "memcache"
                ],
            "session" => [
                "class" => "LFramework\Base\Session",
                "type" => "server"
                ],
            "router" => [
                "class" => "LFramework\Routing\Router",
                "url" => isset($_GET['url']) ? $_GET['url'] : $this->_defaultRoute,
                "extension" => isset($_GET['url']) ? $_GET['url'] : "php"
                ],
            "view" => ["class" => "LFramework\Base\View"],
            "assetManager" => ["class" => "LFramework\Base\AssetManager"],
            "request" => ["class" => "LFramework\Base\Request"],
            "i18n" => ["class" => "LFramework\I18n\I18N"]
        );
    }

}
