<?php

namespace app\base;


class View
{
    public $layout;
    private $viewFolder;

    public function __construct($controllerName)
    {
        $this->layout = App::$app->viewPath."layout/".(!isset($this->layout) ? "main" : $this->layout).".php";
        $this->viewFolder = strtolower(str_replace("Controller", "", $controllerName));
    }

    public function render($viewName, array $arguments = [])
    {
        $view = $this->findView($viewName);

        extract($arguments,EXTR_OVERWRITE);

        ob_start();
        ob_implicit_flush(false);

        require $view;

        $content = ob_get_clean();

        return $this->renderLayout($content);
    }

    public function renderPart($viewName, array $arguments = [])
    {
        extract($arguments,EXTR_OVERWRITE);
        ob_start();

        include App::$app->viewPath.$viewName.".php";

        $res = ob_get_contents();
        ob_clean();

        return $res;
    }

    public function renderWithoutLayout(string $viewName, array $arguments = [])
    {
        $view = $this->findView($viewName);

        extract($arguments,EXTR_OVERWRITE);

        ob_start();
        ob_implicit_flush(false);

        require $view;

        return ob_get_clean();

    }

    private function renderLayout($content)
    {
        ob_start();

        include $this->layout;

        $res = ob_get_contents();
        ob_clean();

        return $res;
    }

    private function findView($viewName)
    {
        return App::$app->viewPath.$this->viewFolder."/$viewName.php";
    }
}