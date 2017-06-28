<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Exception;

use LFramework\Base\Exception as Exception;
/**
 * Description of UnknownClassException
 *
 * @author Lens
 */
class UnknownClassException extends Exception
{

    public function getName()
    {
        return "Unkonwn Class";
    }

}
