<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\AssetBundles;

use LFramework\Base\AssetBundle as AssetBundle;
use LFramework\Base\Application;

/**
 * Description of AppAsset
 *
 * @author Lens
 */
class AppAsset extends AssetBundle
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath = \LFramework\Base\Application::WEBROOT .DS. "assets" . DS. "enlighten";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_baseUrl = \LFramework\Base\Application::WEB. "/assets/enlighten";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_css = array(
        
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_jss = array(
        
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_jsOptions = array(
        
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_depends = array(
        
    );

}
