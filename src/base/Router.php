<?php

namespace app\base;


class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = App::$app->router["rules"];
    }

    private function getUri()
    {
        if(!empty($_SERVER['REQUEST_URI'])) {
            return $_SERVER['REQUEST_URI'];
        }

    }

    function resolvePath()
    {
        $uri = $this->getUri();
        $controller = null;
        $action = null;
        $params = [];

        foreach ($this->routes as $pattern => $route) {
            if(preg_match("~$pattern~", $uri)){
                if($uri !== "/")
                    $uri=substr($uri, 1);

                $path = preg_replace("~$pattern~", $route, $uri);
                $pieces = explode("/", $path);

                $controller = array_shift($pieces);
                $action = array_shift($pieces);
                if(strpos($action, "?"))
                    list($action,$pieces) = explode("?", $action);
                $params = $pieces;
                break;
            }
        }

        if(!$controller)
            throw new \Exception("Page not found", 404);

        $controller = "app\\controller\\".strtoupper(substr($controller, 0,1)).substr($controller, 1)."Controller";

        if(!class_exists($controller))
            throw new \Exception("Page not found", 404);

        if($action){
            $action = "action".strtoupper(substr($action, 0,1)).substr($action, 1);

            $action = preg_replace_callback("/-+\w{1}/", function($matches){
                return strtoupper($matches[0][1]);
            }, $action);
        }

        return [$controller,$action,$params];
    }

}