<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of TagArticle
 *
 * @author Lens
 */
class TagArticle extends \LFramework\Base\Model
{

    /**
     * @column
     * @readwrite
     * @primary
     * @type integer
     */
    protected $_article;
    /**
     * @column
     * @readwrite
     * @primary
     * @type integer
     */
    protected $_tag;

}
