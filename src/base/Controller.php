<?php

namespace app\base;

class Controller
{
    public $view;

    function __construct()
    {
        $childController = get_class($this);
        $pieces = explode("\\", $childController);

        $this->view = new View(array_pop($pieces));
    }
}