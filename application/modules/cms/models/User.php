<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Modules\Cms\Models;

/**
 * Description of User
 *
 * @author Lens
 */
class User extends Model
{

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, alphaNumeric, min(8), max(32)
     * @label username
     */
    protected $_userName;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, min(6), max(32)
     * @label Password
     */
    protected $_password;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label role
     */
    protected $_role;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, alpha, min(3), max(32)
     * @label First name
     */
    protected $_firstName;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, alpha, min(3), max(32)
     * @label Last name
     */
    protected $_lastName;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, max(100)
     * @label Email
     */
    protected $_email;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, alphaNumeric, min(8), max(32)
     * @label Contact
     */
    protected $_contact;
    

}
