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
 * 
 *
 * @author Lens
 */
class IaiAsset extends AssetBundle
{

   /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath = WEBROOT .DS. "assets";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_baseUrl = WEB. "/assets";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_css = array(
        /* Bootstrap Core CSS */
        "css/bootstrap.css",
        /* Template CSS */
        "css/animate.css",
        "css/font-awesome.css",
        "css/nexus.css",
        "css/responsive.css",
        /* Customer CSS */
        "css/custom.css"
    );
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_js = array(
        "js/jquery.min.js",
        "js/bootstrap.min.js",
        "js/scripts.js",
        /* Isotope - Profolio Sorting */
        "js/jquery.isotope.js",
        /* Mobile Menu - Slicknav */
        "js/jquery.slicknav.js",
        /* Animte on Scroll */
        "js/jquery.visible.js",
        /* Sticky Div */
        "js/jquery.sticky.js",
        /* Slimbox2 */
        "js/slimbox2.js",
        /* Modernizr */
        "js/modernizr.custom.js"
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
