<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Controllers;


/**
 * Description of SettingsController
 *
 * @author Lens
 */
class SettingsController extends Controller
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layoutView = "";
    
    public function indexAction()
    {
        $this->render("index");
    }

}
