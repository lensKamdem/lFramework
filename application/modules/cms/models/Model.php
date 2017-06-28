<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;


/**
 * Description of Model
 *
 * @author Lens
 */
class Model extends \LFramework\Base\Model
{

    /**
     * @column
     * @readwrite
     * @primary
     * @type autonumber
    */
    protected $_id;
    /**
     * @column
     * @readwrite
     * @type datetime
    */
    protected $_createDate;
    /**
     * @column
     * @readwrite
     * @type datetime
    */
    protected $_updateDate;
    /**
     * @column
     * @readwrite
     * @type boolean
     * @index
    */
    protected $_live;
    /**
     * @column
     * @readwrite
     * @type boolean
     * @index
    */
    protected $_deleted;
    /**
     * @column
     * @readwrite
     * @type integer
     * @foreign user(id)
    */
    protected $_createdBy;
    
    public function initialize()
    {
        parent::initialize();
        $db = \LFramework::$application->getDb()->connect();
        
    }
    
    public function save() 
    {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        if (empty($raw))
        {
            $this->setCreateDate(date("Y-m-d H:i:s"));
            $this->setDeleted(false);
            $this->setLive(true);
        }
        $this->setUpdateDate(date("Y-m-d H:i:s"));
        
        parent::save();
    }

}
