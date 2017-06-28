<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Controllers;


/**
 * Description of Controller
 *
 * @author Lens
 */
class Controller extends \LFramework\Base\Controller
{

    public function __construct($id, $module, $config = array()) 
    {
        parent::__construct($id, $module, $config);
        $db = \LFramework::$application->getDb();
        $db->connect();
    }

}
