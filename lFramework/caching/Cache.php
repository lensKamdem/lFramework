<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Caching;

/**
 * Description of Cache
 *
 * @author Lens
 */

use LFramework\Base as BaseClass;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

class Cache extends BaseClass {
    
    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_config = array();
    
    
    public function initialize() {
        if (empty($this->type)) {
            throw new InvalidConfigException("Invalid type");
        }
        switch ($this->_type) {
            case "memcached": {
                return new Cache\Driver\Memcached($this->_config);
                break;
            }
            default: {
                throw new InvalidConfigException("Invalid type");
                break;
            }
        }
    }
}

