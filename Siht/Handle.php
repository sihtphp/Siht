<?php

namespace Siht;

abstract class Handle {

    private $__controller__;

    protected function getController() {
        return $this->__controller__;
    }

    protected function setController($controller) {
        $this->__controller__ = $controller;
    }

    protected function exception($message = NULL, $code = NULL) {
        if ($code)
            $exception = new \Exception($message, $code);
        else
            $exception = new \Exception($message);

        throw $exception;
    }

    protected function halt($response) {
        return new \Siht\ResponseHalt($response);
    }
}
