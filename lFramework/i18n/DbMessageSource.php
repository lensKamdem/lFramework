<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\I18n;

use LFramework\I18n\MessageSource as MessageSource;

/**
 * Description of DbMessageSource
 *
 * @author Lens
 */
class DbMessageSource extends MessageSource
{

    /**
     * @var type 
     * @readwrite
     */
    protected $_db;
    protected $_messageSourceTable = "{{%source_message}}";
    protected $_messageTable = "{{%message}}";
    
    public function initialize() 
    {
        parent::initialize();
        $this->_db = \LFramework::$application->getDb();
    }
    protected function loadMessages($category, $language)
    {
        return $this->loadMessagesFromDb($category, $language);
    }
    protected function loadMessagesFromDb($category, $language)
    {
        $mainQuery = $this->_db->query()
            ->from(["t1" => $this->_messageSourceTable, "t2" => $this->_messageTable],
                ["message" => "t1.message", "translation" => "t2.translation"])
            ->where([
                "t1.id=?" => "{t2.id}",
                "t1.category=?" => $category,
                "t2.language=?" => $language]);
        $query = $mainQuery->_buildSelect();
        
        $fallbackLanguage = substr($language, 0, 2);
        $fallbackSourceLanguage = substr($this->_sourceLanguage, 0, 2);

        if ($fallbackLanguage !== $language) {
            $query = $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackLanguage));
        } elseif ($language === $fallbackSourceLanguage) {
            $query = $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackSourceLanguage));
        }

        $messages = $mainQuery->all($query);

        return ArrayHelper::map($messages, 'message', 'translation');
    }
    protected function createFallBackQuery($category, $language, $fallbackLanguage)
    {
        return $this->_db->query()
            ->from(["t1" => $this->_messageSourceTable, "t2" => $this->_messageTable],
                ["message" => "t1.message", "translation" => "t2.translation"])
            ->where([
                "t1.id=?" => "{t2.id}",
                "t1.category=?" => $category,
                "t2.language=?" => $fallbackLanguage])
            ->all();
    }

}
