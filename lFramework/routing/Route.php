<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Routing;

/**
 * Description of Route
 *
 * @author Lens
 */

use LFramework\Base\BaseClass as BaseClass;

class Route extends BaseClass {
    
    /**
     * @readwrite
     */
    protected $_pattern;
    /**
     * @readwrite
     */
    protected $_controller;
    /**
     * @readwrite
     */
    protected $_action;
    /**
     * @readwrite
     */
    protected $_parameters = array();
    
}
