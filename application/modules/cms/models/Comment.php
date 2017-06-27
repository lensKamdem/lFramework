<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of Comment
 *
 * @author Lens
 */
class Comment extends Model
{

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_content;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_parent;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_article;

}
