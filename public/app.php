<?php
date_default_timezone_set("America/Port-au-Prince");
//inclure autoload
require "../vendor/autoload.php";
require "../app/DefaultApp/configuration.php";
$configurations = \systeme\Application\Configuration::getConfiguration();
//nom de votre app
\systeme\Application\Application::$app = "DefaultApp";

try {
    foreach ($configurations as $key => $config) {
        $app = "\\app\\$key\\$key";
        if ($key == \systeme\Application\Application::$app) {
            new $app($config);
            $app::routing();
            $app::run();
        }
    }
} catch (Exception $ex) {
   http_response_code("404");
   echo $ex->getMessage();
}





