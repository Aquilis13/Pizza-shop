<?php

namespace pizzashop\catalog\domain\exceptions;

use \Exception;

class ProductNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}