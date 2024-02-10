<?php

namespace pizzashop\catalog\helpers;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatter
{
    public static function formatResponse(Response $response, array $responseData, int $statusCode): Response
    {
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
