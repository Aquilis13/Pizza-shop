<?php

namespace pizzashop\auth\api\domain\exceptions;

use \Exception;

class UserAlreadyExistException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}