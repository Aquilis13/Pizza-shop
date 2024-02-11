<?php

namespace pizzashop\auth\api\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\auth\api\domain\dto\TokenDTO;
use pizzashop\auth\api\helpers\ResponseFormatter;

final class RefreshTokenAction {

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
            
            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        }else{
            try{
                $authorizationHeader = $header[0];
                $token = str_replace("Bearer ", "", $authorizationHeader) ?? null;     
            
                $tokenDTO = $authService->refresh(new TokenDTO(null, $token));

                $responseData = [
                    'status' => 'success',
                    'data' => [
                        'access_token' => $tokenDTO->access_token,
                        'refresh_token' => $tokenDTO->refresh_token
                    ]
                ];
                $statusCode = 200;

                return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
            } catch(Firebase\JWT\ExpiredException $e){
                $responseData = [
                    'status' => 'error',
                    'message' => 'Votre refresh Token est expirer vous pouvez en regénérer un nouveau en vous reconnectant.',
                    'connection' => 'http://'.$_SERVER['HTTP_HOST'].'/api/users/signin',
                ];
                $statusCode = 401;
           
                return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
            }catch (Exception $e){
                $responseData = [
                    'status' => 'error',
                    'message' => 'Une erreur inattendue est survenue.',
                    'exception' => $e->message,
                ];
                $statusCode = 401;

                return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
            }            
        }
    }

}