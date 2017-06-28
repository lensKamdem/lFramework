<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Controllers;

use LFramework\Helpers\RequestMethods as RequestMethods;
use Application\Modules\Cms\Models\User as User;

/**
 * Description of HomeController
 *
 * @author Lens
 */
class HomeController extends Controller
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_layoutView = DS."login-layout";
    
    public function indexAction()
    {
        if (RequestMethods::server("REQUEST_METHOD") == "POST") {
            $userName = RequestMethods::post("userName");
            $password = RequestMethods::post("password");
            $error = false;
            $errors = array();
            if (empty($userName)) {
                $errors["userName"] = "Username is required";
                $error = true;
            }
            if (empty($password)) {
                $errors["password"] = "Password is required";
                $error = true;
            }
            
            if (!$error) {
                $user = User::first(array(
                   "userName=?" => $userName,
                    "password=?" => $password,
                    "live=?" => true,
                    "deleted=?" => false
                ));
                if (!empty($user)) {
                    $session = \LFramework::$application->getSession();
                    $session->set("user", \LFramework\Helpers\ArrayMethods::extractProps($user));
                    $this->redirect(\LFramework::$application->getHomeUrl()."/admin/dashboard/index");
                }
                else {
                    $errors["login"] = "Wrong username and/or password";
                    $this->render("login", ["errors" => $errors]);
                }
            }
            else {
                $this->render("login", ["errors" => $errors]);
            }
        }
        else {
            $errors = null;
            $this->render("login", array("errors" => $errors));
        }
    }

}
