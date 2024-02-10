<?php

namespace pizzashop\gateway\domain\exceptions;
use Psr\Http\Message\ServerRequestInterface as Request;

use \Exception;

class HttpUnauthorizedException extends Exception
{
    protected $request;

    public function __construct(Request $request, $message, $code = 0, Exception $previous = null)
    {
        $this->request = $request;

        parent::__construct($message, $code, $previous);
    }

    public function getRequest()
    {
        return $this->request;
    }
}