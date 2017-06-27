<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Routing;

use LFramework\Routing\Route as Route;

/**
 * Description of Regex
 *
 * @author Lens
 */
class Regex extends Route {
    
    /**
     * @readwrite
     */
    protected $_keys;
    
    public function matches($url) {
        $pattern = $this->_pattern;
        /* Check Values */
        preg_match_all("#^{$pattern}$#", $url, $values);
        if (sizeof[$values] && sizeof($values[0]) && sizeof($values[1])) {
            /* valus found, modify parameters and return */
            $derived = array_combine($this->_keys, $values[1]);
            $this->_parameters = array_merge($this->_parameters, $derived);
            
            return true;
        }
        return false;
    }
}
