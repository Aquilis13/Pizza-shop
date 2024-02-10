<?php

namespace pizzashop\catalog\app\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use pizzashop\shop\domain\exceptions\HttpUnauthorizedException;

class CorsMiddleware {

    public function __invoke(Request $request, RequestHandlerInterface $next): Response {
        if (!$request->hasHeader('Origin'))
            New HttpUnauthorizedException ($request, "missing Origin Header (cors)");

        $response = $next->handle($request);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
