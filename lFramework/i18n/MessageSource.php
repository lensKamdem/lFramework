<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\I18n;

use LFramework\Base\BaseClass as BaseClass;

/**
 * Description of MessageSource
 *
 * @author Lens
 */
class MessageSource extends BaseClass
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_sourceLanguage;
     /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_message = array();
    
    public function initialize()
    {
        parent::initialize();
        if ($this->_sourceLanguage === null) {
            $this->_sourceLanguage = \LFramework::$application->_sourceLanguage;
        }
    }
    protected function loadMessages($category, $language)
    {
        return array();
    }
    public function translate($category, $message, $language)
    {
        if ($language !== $this->_sourceLanguage) {
            return $this->translateMessage($category, $message, $language);
        } else {
            return false;
        }
    }
    public function translateMessage($category, $message, $language)
    {
        $key = $language . DS . $category;
        if (!isset($this->_messages[$key])) {
            $this->_messages[$key] = $this->loadMessages($category, $language);
        }
        if (isset($this->_messages[$key][$message]) && $this->_messages[$key][$message] !== "") {
            return $this->_messages[$key][$message];
        } 
        
        return $this->_messages[$key][$message] = false;
    }

}
