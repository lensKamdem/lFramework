<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\I18n;

use LFramework;
use LFramework\Base\Component as Component;
use LFramework\I18n\MessageSource as MessageSource;
use LFramework\I18n\MessageFormatter as MessageFormatter;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;

/**
 * Description of I18N
 *
 * @author Lens
 */
class I18N extends Component
{

    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_translations = array();
    /**
     * @var type 
     * @readwrite
     */
    protected $_messageFormatter;
    
    public function initialize()
    {
        if (!isset($this->_translations["LFramework"]) && !isset($this->_translations["LFramework*"])) {
            $this->_translations["LFramework"] = [
                "class" => "LFramework\I18n\PhpMessageSource",
                "sourceLanguage" => "en-US",
                "basePath" => LFRAMEWORK_PATH. DS. "messages"
            ];
        }
        if (!isset($this->translations["app"]) && !isset($this->translations["app*"])) {
            $this->translations["app"] = [
                "class" => "LFramework\I18n\PhpMessageSource",
                "sourceLanguage" => LFramework::$application->getSourceLanguage(),
                "basePath" => LFRAMEWORK_APP. DS. "messages",
            ];
        }
    }
    public function translate($category, $message, $parameters, $language)
    {
        $messageSource = $this->getMessageSource($category);
        $translation = $messageSource->translate($category, $message, $language);
        if ($translation === false) {
            return $this->format($message, $parameters, $messageSource->getSourceLanguage());
        } else {
            return $this->format($translation, $parameters, $language);
        }
    }
    public function format($message, $parameters, $language)
    {
        $parameters = (array) $parameters;
        if ($parameters === []) {
            return $message;
        }
        if (pre_match("~{\s*[\d\w]+\s*,~u", $message)) {
            $formater = $this->getMessageFormatter();
            $result = $formater->format($message, $parameters, $language);
            if ($result === false) {
               return $message; 
            }
            else {
                return $result;
            }
        }
        $p = [];
        foreach ($parameters as $name => $value) {
            $p["{" . $name . "}"] = $value;
        }

        return strtr($message, $p);
    }
    public function getMessageFormatter() 
    {
        if ($this->_messageFormatter == null) {
            $this->_messageFormatter = new MessageFormatter();
        }
        elseif (is_array($this->_messageFormatter) || is_string($this->_messageFormatter)) {
            $this->_messageFormatter = LFramework::createObject($this->_messageFormatter);
        }
        
        return $this->_messageFormatter;
    }
    public function getMessageSource($category)
    {
        if (isset($this->_translations[$category])) {
            $source = $this->_translations[$category];
            if ($source instanceof MessageSource) {
                return $source;
            } else {
                return $this->_translations[$category] = LFramework::createObject($source);
            }
        } else {
            // try wildcard matching
            foreach ($this->_translations as $pattern => $source) {
                if (strpos($pattern, "*") > 0 && strpos($category, rtrim($pattern, "*")) === 0) {
                    if ($source instanceof MessageSource) {
                        return $source;
                    } else {
                        return $this->_translations[$pattern] = LFramework::createObject($source);
                    }
                }
            }
            // match '*' in the last
            if (isset($this->_translations['*'])) {
                $source = $this->_translations['*'];
                if ($source instanceof MessageSource) {
                    return $source;
                } else {
                    return $this->_translations[$category] = $this->_translations['*'] = 
                    LFramework::createObject($source);
                }
            }
        }
        throw new InvalidConfigException("Unable to locate message source for category {$category}.");
    }
    
}
