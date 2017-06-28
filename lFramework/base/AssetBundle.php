<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

use LFramework\Base\BaseClass as BaseClass;
use LFramework\Helpers\ArrayMethods as ArrayMethods;

/**
 * Description of AssetBundle
 *
 * @author Lens
 */
class AssetBundle extends BaseClass
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath = LFramework\Base\Application::WEBROOT . DS. "assets";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_baseUrl = LFramework\Base\Application::WEB . DS. "assets";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_depends = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_js = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_css = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_jsOptions = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_cssOptions = array();
    
    public static function register($view)
    {
        return $view->registerAssetBundle(get_called_class());
    }
    public function registerAssetFiles($view)
    {
        $manager = $view->getAssetManager();
        foreach ($this->_js as $js) {
            if (is_array($js)) {
                $file = array_shift($js);
                $options = ArrayMethods::merge($this->_jsOptions, $js);
                $view->registerJsFile($manager->getAssetUrl($this, $file), $options);
            } else {
                if ($js !== null) {
                    $view->registerJsFile($manager->getAssetUrl($this, $js), $this->_jsOptions);
                }
            }
        }
        foreach ($this->_css as $css) {
            if (is_array($css)) {
                $file = array_shift($css);
                $options = ArrayMethods::merge($this->_cssOptions, $css);
                $view->registerCssFile($manager->getAssetUrl($this, $file), $options);
            } else {
                if ($css !== null) {
                    $view->registerCssFile($manager->getAssetUrl($this, $css), $this->_cssOptions);
                }
            }
        }
    }

}
