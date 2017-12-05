<?php
//sending files directly
if (preg_match('/\.(?:png|jpg|jpeg|gif|svg|ico|css|js)$/', $_SERVER["REQUEST_URI"]))
    return false;

require (__DIR__."/src/base/App.php");

$app = new app\base\App();
$app->init(__DIR__."/src/config/config.php");

if(\app\base\App::$app->debug){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$app->run();