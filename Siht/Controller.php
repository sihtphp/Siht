<?php

namespace Siht;

abstract class Controller {

    private $factory;
    private $handleIn;
    private $handleOut;
    private $repository;

    public function __construct() {        
        $class = new \ReflectionClass(get_called_class());
        $this->setController($class->getNamespaceName());
    }

    private function getFactory() {
        return $this->factory;
    }

    private function getHandleIn() {
        return $this->handleIn;
    }

    private function getHandleOut() {
        return $this->handleOut;
    }

    private function getRepository() {
        return $this->repository;
    }

    protected function setFactory($factory) {
        $this->factory = $factory;
    }

    protected function setHandleIn($handleIn) {
        $this->handleIn = $handleIn;
    }

    protected function setHandleOut($handleOut) {
        $this->handleOut = $handleOut;
    }

    protected function setRepository($repository) {
        $this->repository = $repository;
    }

    protected function setController($namespace) {

        $className = "\\{$namespace}\Factory";
        if (class_exists($className))
            $this->setFactory(new $className());

        $className = "\\{$namespace}\HandleIn";
        if (class_exists($className))
            $this->setHandleIn(new $className());

        $className = "\\{$namespace}\Repository";
        if (class_exists($className))
            $this->setRepository(new $className());

        $className = "\\{$namespace}\HandleOut";
        if (class_exists($className))
            $this->setHandleOut(new $className());
    }

    public function __call($methodName, $arguments) {

        $response = NULL;

        if (is_object($this->getFactory()) && method_exists($this->getFactory(), $methodName)) {
            $obj = call_user_func_array(array($this->getFactory(), $methodName), $arguments);
            $arguments = array($obj);
        }

        if (is_object($this->getHandleIn()) && method_exists($this->getHandleIn(), $methodName)) {
            $response = call_user_func_array(array($this->getHandleIn(), $methodName), $arguments);

            if ($this->validateResponse($response))
                return $response->getContent();
        }

        if (is_object($this->getRepository()) && method_exists($this->getRepository(), $methodName)) {
            $response = call_user_func_array(array($this->getRepository(), $methodName), $arguments);

            if ($this->validateResponse($response))
                return $response->getContent();
        }

        if (is_object($this->getHandleOut()) && method_exists($this->getHandleOut(), $methodName)) {
            $response = call_user_func_array(array($this->getHandleOut(), $methodName), array($response));

            if ($this->validateResponse($response))
                return $response->getContent();
        }

        return $response;
    }

    private function validateResponse($response) {
        if (is_object($response) && $response instanceof \Siht\ResponseHalt)
            return $response->getContent();
    }

}
