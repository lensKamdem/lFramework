<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Routing;

use LFramework\Routing\Route as Route;
use LFramework\Helpers\ArrayMethods as ArrayMethods;

/**
 * Description of Simple
 *
 * @author Lens
 */
class Simple extends Route {
    
    public function matches($url) {
        $pattern = $this->_pattern;
        /* Get keys */
        preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);
        if(sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1])) {
            $keys = $keys[1];
        }
        else {
            /*No keys in the return , retutn a simple mathch */
            return preg_match("#^{$pattern}$#", $url);
        }
        /* Normalize route pattern */
        $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);
        /* Check values */
        preg_match_all("#^{$pattern}$#", $url, $values);
        
        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            /* unset the matched url */
            unset($values[0]);
            /* values found, modify paramters and return */
            $derived = array_combine($keys, ArrayMethods::flatten($values));
            $this->_parameters = array_merge($this->_parameters, $derived);
            
            return true;
        } 
        return false;
    }
    
}
