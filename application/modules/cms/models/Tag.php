<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of Tag
 *
 * @author Lens
 */
class Tag extends Model
{

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_name;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_description;

}
