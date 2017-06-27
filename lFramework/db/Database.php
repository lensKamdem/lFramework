<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Db;

/**
 * Description of Database
 *
 * @author Lens
 */

use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;


class Database extends Component {
    
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
        // Add type to configurations
        $this->_config["type"] = $this->_type;
        
        switch ($this->_type) {
            case "mysql": {
                return new Mysql\Connector($this->_config);
                break;
            }
            default: {
                throw new InvalidConfigException("Invalid type");
                break;
            }
        }
    }
}
