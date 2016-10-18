<?php

namespace A2Design\AIML\Error;

use Exception;

class AdapterClassNotFoundException extends Exception {
    protected $message = 'Provided class not found';
    protected $code = 404;
}
