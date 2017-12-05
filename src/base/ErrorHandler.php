<?php

namespace app\base;


class ErrorHandler extends Controller
{
    private $codes = [
        404,
        500
    ];
    public function handleError($code, $message)
    {
        if(!in_array($code, $this->codes))
            $message = "Internal server error";
        return $this->view->renderPart("error/error",["code" => $code, "msg" => $message]);
    }
}