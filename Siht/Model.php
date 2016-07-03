<?php

namespace Siht;

abstract class Model {

    private $__controller__;

    protected function getController() {
        return $this->__controller__;
    }

    protected function setController($controller) {
        $this->__controller__ = $controller;
    }

}
