<?php

namespace pizzashop\gateway\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use pizzashop\gateway\helpers\ResponseFormatter;

final class CatalogAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        try{
            $client = new Client([
                'base_uri' => $this->container->get('catalog.path'),
                'timeout' => 5.0
            ]);
            
            // Ajoute le header Authorization uniquement s'il n'est pas vide
            $headers = [];
            if ($authorizationHeader = $request->getHeader('Authorization')) {
                $headers['Authorization'] = $authorizationHeader;
            }
            
            $path = $request->getUri()->getPath();

            return $client->request($_SERVER['REQUEST_METHOD'], $path, [
                'headers' => $headers,
            ]);

        } catch(ClientException | ServerException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();

            $responseData = [
                'status' => 'error',
                'message' => $responseBody,
            ];
            $statusCode = $response->getStatusCode();

            // Retourne la réponse Slim avec le corps de la réponse Guzzle
            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);

        } catch(\Exception $e){
            $responseData = [
                'status' => 'error',
                'message' => 'Une erreur inattendu est survenu',
                'exception' => $e->getMessage(),
            ];
            $statusCode = 500;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        }
    }
}
