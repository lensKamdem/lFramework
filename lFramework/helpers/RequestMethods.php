<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Helpers;

/**
 * Description of RequestMethods
 *
 * @author Lens
 */

class RequestMethods {
    
    private function __construct() 
    {
        
    }
    private function __clone() 
    {
        
    }
    
    public static function get($key, $default = "") {
        if (!empty($_GET[$key])) {
            return $_GET[$key];
        }
        return $default;
    }
    public static function post($key, $default = "") {
        if (!empty($_POST[$key])) {
            return $_POST[$key];
        }
        return $default;
    }
    public static function server($key, $default = "") {
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        return $default;
    }
    
}
