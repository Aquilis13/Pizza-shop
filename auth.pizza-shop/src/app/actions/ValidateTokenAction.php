<?php

namespace pizzashop\auth\api\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\auth\api\domain\dto\TokenDTO;

final class ValidateTokenAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $authService = $this->container->get('auth.service');

        $header = $request->getHeader('Authorization');
        if (empty($header)) {
            $responseData = [
                'status' => 'error',
                'message' => "Le Token n'est pas présent ou n'a pas été correctement transmis. Veuillez vous référer à la documentation du projet pour plus d'informations.",
            ];
            $statusCode = 401;
            
            return $this->formatResponse($response, $responseData, $statusCode);
        }else{
            try{
                $authorizationHeader = $header[0];
                $token = str_replace("Bearer ", "", $authorizationHeader) ?? null;     
            
                $userDTO = $authService->validate($token);

                $responseData = [
                    'status' => 'success',
                    'data' => [
                        'user' => [
                            'username' => $userDTO->username,
                            'email' => $userDTO->email
                        ]
                    ]
                ];
                $statusCode = 200;

                return $this->formatResponse($response, $responseData, $statusCode);
            } catch (Exception $e){
                $responseData = [
                    'status' => 'error',
                    'se_reconnecter' => 'http://'.$_SERVER['HTTP_HOST'].'/api/users/signin',
                    'message' => 'Une erreur inattendue est survenue.',
                    'exception' => $e->message,
                ];
                $statusCode = 401;

                return $this->formatResponse($response, $responseData, $statusCode);
            }            
        }
    }

    private function formatResponse(Response $response, array $responseData, int $statusCode) {
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}