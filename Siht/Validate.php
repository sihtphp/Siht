<?php

namespace Siht;

final class Validate {

    private $value;
    private $message;

    private function __construct($value, $message) {
        $this->value = $value;
        $this->message = $message;
    }

    public static function expected($value, $message = "") {
        $validate = new Validate($value, $message);
        return $validate;
    }

    private function error() {
        throw new \Exception($this->message);
    }

    public function equal($value) {
        if (is_object($this->value)) {
            
        } elseif (is_array($this->value)) {
            if (!(count(array_diff($this->value, $value)) == 0) || !(count(array_diff($value, $this->value)) == 0))
                $this->error();
        }elseif (!($this->value === $value)) {
            $this->error();
        }

        return $this;
    }

    public function contain($value) {
        if (is_array($this->value)) {
            if (!(in_array($value, $this->value)))
                $this->error();
        } else if (is_array($value)) {
            if (!(in_array($this->value, $value)))
                $this->error();
        } else {
            if (!(strstr($this->value, $value)))
                $this->error();
        }
        return $this;
    }

    public function match($pattern) {

        if (!preg_match($pattern, $this->value)) {
            $this->error();
        }
        return $this;
    }

    //Validates

    public function isNull() {
        if (!is_null($this->value))
            $this->error();
        return $this;
    }

    public function isNotNull() {
        if (is_null($this->value))
            $this->error();
        return $this;
    }

    public function isEmpty() {
        if (!empty($this->value))
            $this->error();
        return $this;
    }

    public function isNotEmpty() {
        if (empty($this->value))
            $this->error();
        return $this;
    }

    public function isNumber() {
        if (!is_numeric($this->value))
            $this->error();
        return $this;
    }

    public function isNotNumber() {
        if (is_numeric($this->value))
            $this->error();
        return $this;
    }

    public function isInteger() {
        if (!is_integer($this->value))
            $this->error();
        return $this;
    }

    public function isNotInteger() {
        if (is_integer($this->value))
            $this->error();
        return $this;
    }

    public function isFloat() {
        if (!is_float($this->value))
            $this->error();
        return $this;
    }

    public function isNotFloat() {
        if (is_float($this->value))
            $this->error();
        return $this;
    }

    public function isArray() {
        if (!is_array($this->value))
            $this->error();
        return $this;
    }

    public function isNotArray() {
        if (is_array($this->value))
            $this->error();
        return $this;
    }

    public function isInstance($value) {
        if (!($this->value instanceof $value))
            $this->error();
        return $this;
    }

    public function isNotInstance($value) {
        if (($this->value instanceof $value))
            $this->error();
        return $this;
    }

    public function isBool() {
        if (!is_bool($this->value))
            $this->error();
        return $this;
    }

    public function isNotBool() {
        if (is_bool($this->value))
            $this->error();
        return $this;
    }

    public function isTrue() {
        $this->isBool();
        if (!($this->value === TRUE))
            $this->error();
        return $this;
    }

    public function isFalse() {
        $this->isBool();
        if (!($this->value === FALSE))
            $this->error();
        return $this;
    }

    public function isGreaterThan($value) {
        if (!($this->value > $value))
            $this->error();
        return $this;
    }

    public function isLessThan($value) {
        if (!($this->value < $value))
            $this->error();
        return $this;
    }

}
