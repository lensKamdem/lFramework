<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Controller
 *
 * @author Lens
 */

use LFramework;
use LFramework\Base\BaseClass as BaseClass;
use LFramework\Base\ViewContextInterface as ViewContextInterface;

class Controller extends BaseClass implements ViewContextInterface
{
    
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
    protected $_module;
    /**
     * @readwrite
     */
    protected $_parameters = array();
    /**
    * @readwrite
    */
    protected $_layoutView;
    /**
    * @readwrite
    */
    protected $_view;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_viewPath;
    /**
    * @readwrite
    */
    protected $_defaultContentType = "text/html";
    
    public function __construct($id, $module, $config = array())
    {
        $this->_id = $id;
        $this->_module = $module;
        if (is_null($this->_module)) {
            $this->_module = \LFramework::$application;
        }
        parent::__construct($config);
    }
    public function render($view, $parameters = array()) 
    {    
        $content = $this->getView()->render($view, $parameters, $this);
        $output = $this->renderContent($content);
        
        \LFramework::$application->setState(Application::STATE_INIT);
        
        header("Content-type : {$this->_defaultContentType}");
        echo $output;
    }
     public function renderContent($content)
    {
        $layoutFile = $this->_findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, ['content' => $content], $this);
        } else {
            return $content;
        }
    }
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = LFramework::$application->getView();
        }
        return $this->_view;
    }
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->_module->getViewPath() . DS . $this->_id;
        }
        return $this->_viewPath;
    }

    protected function _findLayoutFile($view)
    {
        $module = $this->_module;
        if (is_string($this->_layoutView)) {
            $layout = $this->_layoutView;
        } elseif ($this->_layoutView === null) {
            while ($module !== null && $module->_layout === null) {
                $module = $module->_parentModule;
            }
            if ($module !== null && is_string($module->_layout)) {
                $layout = $module->_layout;
            }
        }

        if (!isset($layout)) {
            return false;
        }

        if (strncmp($layout, '/', 1) === 0) {
            $file = \LFramework::$application->getLayoutPath() . DIRECTORY_SEPARATOR . substr($layout, 1);
        } else {
            $file = $module->getLayoutPath() . DIRECTORY_SEPARATOR . $layout;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $view->_defaultExtension;
        if ($view->_defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }
        return $path;
    }
    public function redirect($url) 
    {
        header("location: {$url}");
        exit();
    }
    
    
}
