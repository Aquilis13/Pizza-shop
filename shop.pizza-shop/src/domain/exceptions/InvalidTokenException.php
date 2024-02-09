<?php

namespace pizzashop\shop\domain\exceptions;

use \Exception;

class InvalidTokenException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}