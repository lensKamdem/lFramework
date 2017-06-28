<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Routing;

/**
 * Description of Router
 *
 * @author Lens
 */

use LFramework\Base\Component as Component;
use LFramework\Base\Inspector as Inspector;
use LFramework\Routing\Route as Route;
use LFramework\Exception\ControllerException as ControllerException;
use LFramework\Exception\ActionException as ActionException;

class Router extends Component {
    
    const EVENT_BEFORE_ROUTING = "lframework.routing.handleRouting.before";
    const EVENT_AFTER_ROUTING = "lframework.routing.handleRouting.before";
    /**
     * @readwrite
     */
    protected $_url;
    /**
     * @readwrite
     */
    protected $_extension;
    /**
     * @readwrite
     */
    protected $_controller;
    /**
     * @readwrite
     */
    protected $_action;
    /**
     * @var type 
     * 
     * @read
     */
    protected $_routes = array();
    
    public function addRoute($route) {
        $this->_routes[] = $route;
        return $this;
    }
    public function removeRoute($route) {
        foreach ($this->_routes as $i => $stored) {
            if ($stored == $route) {
                unset($this->_route[$i]);
            }
        }
        return $this;
    }
    public function getRoutes() {
        $list = array();
        foreach ($this->_routes as $route) {
            $list[$route->$pattern] = get_class($route);
        }
        return $list;
    }
    
    protected function _pass($id, $route) 
    {   
        try {
            // For modules
            $module = \LFramework::$application->getModule($id);
            if ($module !== null) {
                list($controller, $action, $parameters) = $this->resolveRoute($id, $route, true);
                $controllerName = $module->getControllerNamespace()."\\".trim($controller, "\\");
                $controllerId = strstr($controller, "Controller", true);
                
                // create controller
                $controller = new $controllerName($controllerId, $module, array(
                        "parameters" => $parameters
                    ));
                \LFramework::$application->setController($controller);
            }
            else {
                list($controller, $action, $parameters) = $this->resolveRoute($id, $route);
                $controllerName = \LFramework::$application->getControllerNamespace()."\\".trim($controller, "\\"); 
                $controllerId = strstr($controller, "Controller", true);
                
                // create controller
                $controller = new $controllerName($controllerId, null, array(
                        "parameters" => $parameters
                    ));
                \LFramework::$application->setController($controller);
            }          
        }
        catch (\Exception $e) {    
            throw new ControllerException("Controller {$controllerName} n {$id} n {$route} not found". print_r($e));
        }
        
        if (!method_exists($controller, $action)) {
            throw new ActionException("Action {$action} not found");
        }
        
        $inspector = new Inspector($controller);
        $methodMeta = $inspector->getMethodMeta($action);
        if (!empty($methodMeta["@protected"]) || !empty($methodMeta["@private"])) {
            throw new ActionException("Action {$action} not found");
        }
        
       $this->hooks($methodMeta, "@before", $controller);
       
        // Run Action
        call_user_func_array(array(
            $controller,
            $action
        ), is_array($parameters) ? $parameters : array());
        
        $this->hooks($methodMeta, "@after", $controller);
        
        /* Unset controller */
        \LFramework::$application->setController(null);
    }
    public function hooks($meta, $type, $instance)
    {
        if (isset($meta[$type])) {
                $run = array();
                foreach ($meta[$type] as $method) {
                    $inspector = new Inspector($instance);
                    $hookMeta = $inspector->getMethodMeta($method);
                    if (in_array($method, $run) && !empty($hookMeta["@once"])) {
                        continue;
                    }
                    $instance->$method();
                    $run[] = $method;
                }
        }
    }
    public function handleRouting() 
    {
        $url = $this->_url;
        
        // For defined routes
        list($id, $rUrl) = $this->resolveUrl($url);
        foreach ($this->_routes as $route) {
            $matches = $route->matches($rUrl);
            if ($matches) {
                $this->_pass($id, $route);
                return;
            }
        }
        
        // For infered routes
        list($id, $route) = $this->resolveUrl($url);
        $this->_pass($id, $route);
    }
    public function resolveUrl($url) 
    {
        $url = trim($url, "/");
        if (strpos($url, "/") !== false) {
            $parts = explode("/", $url, 2);
        }
        else {
            $parts[0] = $url;
            $parts[1] = $url;
        }
        
        return $parts;
    }
    public function resolveRoute($id, $route, $hasModule = false)
    {
        $results = array();
        if ($hasModule) {
            if ($route instanceof Route) {
                // for defined routes
                $results[] = $route->_controller."Controller";
                $results[] = $route->_action."Action";
                $results[] = $route->_parameters;
            }
            else {
                $slash = substr_count($route, "/");
                if ($slash >= 2) {
                    // ex. dashboard/index/parameters
                    $parts = explode("/", $route, 3);
                    $results[] = $parts[0]."Controller";
                    $results[] = $parts[1]."Action";
                    $results[] = $parts[2];
                }
                elseif ($slash == 1) {
                    // ex. dashboard/index
                    $parts = explode("/", $route, 2);
                    $results[] = $parts[0]."Controller";
                    $results[] = $parts[1]."Action";
                    $results[] = array();
                }
            }
        }
        else {
            if ($route instanceof Route) {
                // for defined routes
                $results[] = $route->_controller."Controller";
                $results[] = $route->_action."Action";
                $results[] = $route->_parameters;
            }
            else {
                $slash = substr_count($route, "/");
                if ($slash >= 1) {
                    // ex. index/parameters 
                    $parts = explode("/", $route, 2);
                    $results[] = $id."Controller";
                    $results[] = $parts[0]."Action";
                    $results[] = $parts[1];
                }
                elseif ($slash == 0) {
                    // ex. index
                    $results[] = $id."Controller";
                    $results[] = $route."Action";
                    $results[] = array();
                }
            }
        }
        $results[0] = ucfirst($results[0]);
        return $results;
    }
   
}
