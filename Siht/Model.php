<?php

namespace Siht;

abstract class Model {

    private $__controller__;

    public function getController() {
        return $this->__controller__;
    }

    public function setController($controller) {
        $this->__controller__ = $controller;
    }
}
