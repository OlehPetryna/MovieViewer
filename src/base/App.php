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
        if($_SERVER["REQUEST_METHOD"] === "POST" && ($_SERVER["CONTENT_TYPE"] ?? false) && strtolower($_SERVER["CONTENT_TYPE"]) === "application/json"){
            $json = file_get_contents("php://input");
            if($json){
                $json = json_decode($json,true);
                foreach ($json as $key => $val)
                    $_POST[$key] = $val;
            }
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

                $this->sendResponse($errorHandler->handleError($e->getCode(), $e->getMessage()),$e->getCode(),"Internal Server Error");
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