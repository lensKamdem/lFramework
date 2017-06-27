<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Session
 *
 * @author Lens
 */

use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

class Session extends Component {
    
    /**
    * @readwrite
    */
    protected $_type;
    /**
    * @readwrite
    */
    protected $_config;

    public function configure() {
        if (!$this->_type) {
            throw new InvalidConfigException("Invalid type");
        }
        switch ($this->_type) {
            case "server": {
                return new \LFramework\Base\SessionServer($this->_config);
                break;
            }
            default: {
                throw new InvalidConfigException("Invalid type");
                break;
            }
        }
    }
    
}
