<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controllers;

use LFramework\Base\Controller as Controller;

/**
 * Description of HomeController
 *
 * @author Lens
 */
class HomeController extends Controller
{

    /**
     * @readwrite
     */
    protected $_layoutView = "/home";
    
    public function indexAction()
    {
        \LFramework::$application->getHomeUrl();
        $this->render("index");
    }
    public function inConstructionAction()
    {
        $this->render("inConstruction");
    }
}
