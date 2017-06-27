<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

/**
 * Description of AssetManager
 *
 * @author Lens
 */
class AssetManager extends Component
{

    /**     
     * @var type 
     * 
     * @readwrite
     */
    protected $_bundles = array();
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
    protected $_baseUrl;
    /**     
     * @var type 
     * 
     * @readwrite
     */
    private $_dummyBundles = array();
    
    
    public function initialize()
    {
        parent::initialize();
    }
    public function getBundle($name)
    {
        if ($this->_bundles === false) {
            return $this->_loadDummyBundle($name);
        } elseif (!isset($this->_bundles[$name])) {
            return $this->_bundles[$name] = $this->_loadBundle($name);
        } elseif ($this->_bundles[$name] instanceof AssetBundle) {
            return $this->_bundles[$name];
        } elseif (is_array($this->_bundles[$name])) {
            return $this->_bundles[$name] = $this->_loadBundle($name, $this->_bundles[$name]);
        } elseif ($this->_bundles[$name] === false) {
            return $this->_loadDummyBundle($name);
        } else {
            throw new InvalidConfigException("Invalid asset bundle configuration: {$name}");
        }
    }
    protected function _loadBundle($name, $config = array())
    {
         if (!isset($config['class'])) {
            $config['class'] = $name;
        }
        /* @var $bundle AssetBundle */
        $bundle = \LFramework::createObject($config);
        return $bundle;
    }
    protected function _loadDummyBundle($name)
    {
        if (!isset($this->_dummyBundles[$name])) {
            $this->_dummyBundles[$name] = $this->_loadBundle($name, [
                'sourcePath' => null,
                'js' => [],
                'css' => [],
                'depends' => [],
            ]);
        }
        return $this->_dummyBundles[$name];
    }
    public function getAssetUrl($bundle, $asset)
    {
        $baseUrl = $bundle->getBaseUrl();
        $asset = ltrim($asset, "/");
        return "{$baseUrl}/{$asset}";
    }

}
