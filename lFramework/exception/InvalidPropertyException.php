<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Exception;

use LFramework\Base\Exception as Exception;

/**
 * Description of PropertyException
 *
 * @author Lens
 */
class InvalidPropertyException extends Exception
{

    public function getName()
    {
        return "Invalid Property";
    }

}
