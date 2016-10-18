<?php

namespace A2Design\AIML\Error;

use Exception;

class AdapterWrongInheritanceException extends Exception {
    protected $message = 'Provided class is not subclass of Adapter';
    protected $code = 406;
}
