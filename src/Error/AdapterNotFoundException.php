<?php

namespace A2Design\AIML\Error;

use Exception;

class AdapterNotFoundException extends Exception {
    protected $message = 'Adapter by provided type not found';
    protected $code = 404;
}
