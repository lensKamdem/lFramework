<?php

/**
 * @copywright Copywirght (c) 2016 Pro Future Technologogies
 * @license MIT
 */

require(dirname(__DIR__). DIRECTORY_SEPARATOR.  "BaseLFramework.php");


/**
 * LFramework is the helper class serving common framework functionalities
 *
 * @author Lens Kamdem <kamdem.lens@gmail.com>
 * @version 1.0
 */
class LFramework extends \LFramework\BaseLFramework
{



}

spl_autoload_register(['LFramework', 'autoload']);

