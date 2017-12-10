<?php

namespace app\base;


class App
{

    private $config;
    public static $app;

    public function loadConfig($config)
    {
        $this->config = require $config;
    }

    public function registerAutoload()
    {
        spl_autoload_register(function ($class) {
            $prefix = 'app\\';
            $baseDir = __DIR__ . '/../';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }

    public function resolveRawInputData ()
    {
        if(!empty($_GET)) {
            $_GET = filter_input_array(INPUT_GET,FILTER_SANITIZE_STRING);
            array_walk_recursive($_GET, function(&$val, $idx){
                $val = htmlspecialchars_decode($val,ENT_QUOTES);
            });
        }
        if(!empty($_POST)){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            array_walk_recursive($_POST, function(&$val, $idx){
                $val = htmlspecialchars_decode($val,ENT_QUOTES);
            });
        }
        if(!empty($_COOKIE)){
            $_COOKIE = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING);
            array_walk_recursive($_COOKIE, function(&$val, $idx){
                $val = htmlspecialchars_decode($val,ENT_QUOTES);
            });
        }

        if($_SERVER["REQUEST_METHOD"] === "POST" && ($_SERVER["CONTENT_TYPE"] ?? false) && strtolower($_SERVER["CONTENT_TYPE"]) === "application/json"){
            $json = file_get_contents("php://input");
            if($json){
                $json = json_decode($json,true);
                if($json){
                    $json = filter_var_array($json, FILTER_SANITIZE_STRING);
                    foreach ($json as $key => $val)
                        $_POST[$key] = htmlspecialchars_decode($val);
                }
            }
        }

        if(isset($_REQUEST)) {
            $_REQUEST = array_merge($_COOKIE ?? [], $_POST ?? [], $_GET ?? []);
        }
    }

    public function init($config)
    {
        $this->registerAutoload();
        $this->loadConfig($config);
        $this->resolveRawInputData();

        $container = new Container();

        foreach ($this->config as $key => $item) {
            $container->$key = $item;
        }
        self::$app = $container;

        foreach (self::$app as $key => &$item) {
            if(isset($item["class"])){
                spl_autoload_call($item["class"]);
                $item["class"] = new $item["class"]();
            }
        }

    }

    public function run()
    {
        $router = new Router();
        $errorHandler = new ErrorHandler();

        try{
            $path = $router->resolvePath();

            $controller = new $path[0]();
            $response = call_user_func([$controller,$path[1]],$path[2]);

            $this->sendResponse($response);

        }catch (\Throwable $e){
            if(App::$app->debug)
                throw $e;
            else

                $this->sendResponse($errorHandler->handleError($e->getCode(), $e->getMessage()),500,$e->getMessage());
        }
    }

    private function sendResponse($data, $code=200, $msg="OK")
    {
        if(headers_sent())
            return;
        header("HTTP/2.0 $code $msg");
        ob_start();

        echo $data;

        ob_end_flush();
        die;
    }
}