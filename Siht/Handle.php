<?php

namespace Siht;

abstract class Handle {

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
