<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Exception
 *
 * @author Lens
 */
class Exception extends \Exception 
{

    public function getName()
    {
        return "Eror";
    }

}
