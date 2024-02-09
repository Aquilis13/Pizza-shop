<?php

namespace pizzashop\shop\domain\exceptions;

use \Exception;

class ServiceException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}