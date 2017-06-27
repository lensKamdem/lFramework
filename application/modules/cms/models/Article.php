<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of Article
 *
 * @author Lens
 */
class Article extends Model
{

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_type;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_title;
    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_content;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_status;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_postedDate;
    /**
     * @column
     * @readwrite
     * @type boolean
     */
    protected $_newsletter;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_newsletterDate;

}
