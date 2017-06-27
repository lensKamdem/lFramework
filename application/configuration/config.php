<?php

$parameters = require(__DIR__. DS. "parameters.php");
$routes = require(__DIR__. DS. "routes.php");

$config = array(
    "id" => "IAI",
    "name" => "IAI Cameroun",
    "basePath" => dirname(__DIR__),
    "components" => [
        "db" => require(__DIR__. DS. "db.php"),
        "i18n" => [
            "translations" => [
                "app*" => [
                    "class" => "LFramework\I18n\PhpMessageSource",
                    "basePath" => LFRAMEWORK_APP. DS."messages"
                ],
                "LFramework*" => [
                    "class" => "LFramework\I18n\PhpMessageSource",
                    "basePath" => LFRAMEWORK_PATH. DS."messages"
                ],
                "admin*" => [
                    "class" => "LFramework\I18n\PhpMessageSource",
                    "basePath" => LFRAMEWORK_APP. DS."messages"
                ]
            ]
        ]
    ],
    "parameters" => $parameters,
    "routes" => $routes,
    "modules" => [
        "admin" => "Application\Modules\Cms\CMS"
    ]
);

return $config;

