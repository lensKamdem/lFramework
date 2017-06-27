<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\I18n;

use LFramework\I18n\MessageSource as MessageSource;

/**
 * Description of PhpMessageSource
 *
 * @author Lens
 */
class PhpMessageSource extends MessageSource
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_basePath = LFRAMEWORK_APP. DS. "messages";
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_fileMap = array();
    
    protected function loadMessages($category, $language)
    {
        $messageFile = $this->getMessageFilePath($category, $language);
        $messages = $this->loadMessagesFromFile($messageFile);

        $fallbackLanguage = substr($language, 0, 2);
        $fallbackSourceLanguage = substr($this->_sourceLanguage, 0, 2);

        if ($language !== $fallbackLanguage) {
            $messages = $this->loadFallbackMessages($category, $fallbackLanguage, $messages);
        } elseif ($language === $fallbackSourceLanguage) {
            $messages = $this->loadFallbackMessages($category, $this->_sourceLanguage, $messages);
        }
        
        return (array) $messages;
    }
    protected function getMessageFilePath($category, $language)
    {
        $messageFile = $this->_basePath . DS. $language. DS;
        if (isset($this->_fileMap[$category])) {
            $messageFile .= $this->_fileMap[$category];
        } else {
            $messageFile .= str_replace("\\", DS, $category) . ".php";
        }

        return $messageFile;
    }
    protected function loadMessagesFromFile($messageFile)
    {
        if (is_file($messageFile)) {
            $messages = include($messageFile);
            if (!is_array($messages)) {
                $messages = [];
            }
            return $messages;
        } else {
            return null;
        }
    }
    protected function loadFallbackMessages($category, $fallbackLanguage, $messages)
    {
        $fallbackMessageFile = $this->getMessageFilePath($category, $fallbackLanguage);
        $fallbackMessages = $this->loadMessagesFromFile($fallbackMessageFile);
        if (empty($messages)) {
            return $fallbackMessages;
        } elseif (!empty($fallbackMessages)) {
            foreach ($fallbackMessages as $key => $value) {
                if (!empty($value) && empty($messages[$key])) {
                    $messages[$key] = $fallbackMessages[$key];
                }
            }
        }

        return (array) $messages;
    }


}
