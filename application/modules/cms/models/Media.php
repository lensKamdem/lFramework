<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of Media
 *
 * @author Lens
 */
class Media extends Model
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
     */
    protected $_address;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_title;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_legend;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_alternative;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_description;
    /**
     * @column
     * @readwrite
     * @type double
     */
    protected $_size;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_dimensions;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_length;

}
