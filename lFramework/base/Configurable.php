<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */


namespace LFramework\Base;

/**
 * Configurable is the interface implemented by classes that support configuring
 * their properties via the last paramter to their constructor.
 * 
 * The interface does not declare any method but classes implementing this interface
 * must declare thier constructor with a last parameter that accepts a configuration
 * array.
 * 
 * public function __constructor($param1, $param2, ..., $config = array())
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
interface Configurable
{

    

}

