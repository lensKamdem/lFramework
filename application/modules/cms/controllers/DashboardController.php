<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Controllers;

/**
 * Description of DefaultController
 *
 * @author Lens
 */
class DashboardController extends Controller
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layoutView = DS."main";
    
    public function indexAction()
    {
        $session = \LFramework::$application->getSession();
        if (!empty($session->get("user"))) {
            $this->render("index");
        }
        else {
            $this->redirect(\LFramework::$application->getHomeUrl()."/admin/home/index");
        }
    }
    public function logoutAction()
    {
        $session = \LFramework::$application->getSession();
        if ($session->get("user")) {
            $session->erase("user");
            $this->redirect(\LFramework::$application->getHomeUrl()."/admin/home/index");
        }
    }
    public function inConstructionAction()
    {
        $this->render("inConstruction");
    }

}
