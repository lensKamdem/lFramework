<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\AssetBundles;

use LFramework\Base\AssetBundle as AssetBundle;
use LFramework\Base\View as View;

/**
 * Description of CmsAsset
 *
 * @author Lens
 */
class CmsAsset extends AssetBundle
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath = WEBROOT .DS."assets".DS."adminLTE-2.3.6";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_baseUrl = WEB. "/assets/adminLTE-2.3.6";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_css = array(
        /* Bootstrap Core CSS */
        "css/bootstrap.min.css",
        /* Template CSS */
        "css/adminLTE.min.css",
        "css/all-skins.min.css",
        "css/custom.css"
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_js = array(
        "js/jquery-2.2.3.min.js",
        "js/bootstrap.min.js",
        "js/demo.js"
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_jsOptions = array(
        "position" => View::POS_END
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_depends = array(
        
    );

}
