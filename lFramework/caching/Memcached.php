<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Caching;

/**
 * Description of Memcached
 *
 * @author Lens
 */

use LFramework\Caching\CacheDriver as CacheDriver;
use LFramework\Exception\InvalidServiceException as InvalidServiceException;

class Memcached extends CacheDriver {
    
    protected $_services;
    /**
     * @readwrite
     */
    protected $_host = "127.0.0.1";
    /**
     * @readwrite
     */
    protected $_port = "11211";
    /**
     * @readwrite
     */
    protected $_isConnected = false;
    
    protected function _isValidService() {
        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof \Memcache;
        if($this->_isConnected && $isInstance && !$isEmpty) {
            return true;
        }
        return false;
    }
    
    public function connect() {
        try {
            $this->_service = new \Memcache();
            $this->_service->connect(
                $this->_host,
                $this->_port
            );
            $this->_isConnected = true;
        }
        catch (\Exception $e) {
            throw new InvalidServiceException("Unable to connect to service");
        }
        return $this;
    }
    public function disconnect() {
        if ($this->isValidService()) {
            $this->_service->close();
            $this->_isConnected = false;
        }
        return $this;
    }
    public function get($key, $default = null) {
        if (!$this->isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        $value = $this->_serice->get($key, MEMCACHE_COMPRESSED);
        if ($value) {
            return $value;
        }
        return $default;
    }
    public function set($key, $value, $duration = 120) {
        if (!$this->isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }$this->_service->set($key, MEMCACHE_COMPRESSED, $duration);
        return $this;
    }
    public function erase($key) {
        if (!$this->isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        $this->_service->delete($key);
        return $this;
    }
}
