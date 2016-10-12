<?php

namespace A2Design\AIML\Error\Dictionary;

use Exception;

class NotFoundException extends Exception {
    protected $message = 'Dictionary by provided string not found';
    protected $code = 404;
}
