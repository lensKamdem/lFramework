<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

/**
 * Description of User
 *
 * @author Lens
 */
class User extends Component
{

    /**
     * @var type 
     * @readwrite
     */
    protected $_identityClass;
    /**
     * @var type 
     * @readwrite
     */
    protected $_enableSession = true;
    /**
     * @var type 
     * @readwrite
     */
    protected $isParam;
    /**
     * @var type 
     * @readwrite
     */
    protected $_identity;
    
    public function initialize()
    {
        parent::initialize();
        if ( $this->_identityClass == null) {
            throw new InvalidConfigException("User::indentityClass must be set.");
        }
    }
    public function getIdentity(){}
    public function setIdentity(){}
    public function login(){}
    public function logout(){}
    public function isGuest(){}
    public function getId(){}
    public function switchIdentity(){}
    protected function renewAuthStatus(){}
    
}
