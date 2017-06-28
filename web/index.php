<?php

/**
 * This constant defines the appplication's root directory
 */ 
define("ROOT", dirname(__DIR__));
/**
 * This constant defines the application directory
 */
define("APP_PATH", ROOT. DIRECTORY_SEPARATOR. "application");

define("HOMEPATH", __DIR__);

defined("LFRAMEWORK_DEBUG") or define("LFRAMEWORK_DEBUG", true);
defined("LFRAMEWORK_ENV") or define('LFRAMEWORK_ENV', 'dev');

require(ROOT . DIRECTORY_SEPARATOR. "application". DIRECTORY_SEPARATOR. "vendor".
    DIRECTORY_SEPARATOR. "autoload.php");
require(ROOT . DIRECTORY_SEPARATOR. "lFramework". DIRECTORY_SEPARATOR. "Base". 
    DIRECTORY_SEPARATOR. "LFramework.php");

$config = require(ROOT . DIRECTORY_SEPARATOR. "application". DIRECTORY_SEPARATOR.
    "configuration". DIRECTORY_SEPARATOR. "config.php");
$app = new LFramework\Base\Application($config);
$app->run();

