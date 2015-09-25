<?php

namespace Siht;

abstract class Pattern {

    const EMAIL = "/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(.[[:lower:]]{2,3})(.[[:lower:]]{2})?$/";
    const NUMBER = "/^[0-9]?$/";

}
