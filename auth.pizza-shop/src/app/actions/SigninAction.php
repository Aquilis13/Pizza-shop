<?php

namespace pizzashop\auth\api\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\dto\CredentialsDTO;

final class SigninAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $authService = $this->container->get('auth.service');

        // Récupére le nom d'utilisateur et le mot de passe dans les variables serveur
        $username = $_SERVER['PHP_AUTH_USER'] ?? null;
        $password = $_SERVER['PHP_AUTH_PW'] ?? null;

        // Si ça ne fonctionne pas on essaye de récupérer les informations en passant par l'uri
        if(!$username || !$password && $username == "" || $password == ""){
            $uri = $request->getUri();

            $userInfo = $uri->getUserInfo();
            $userInfoArray = explode(':', $userInfo);

            if (count($userInfoArray) >= 2) {
                $username = $userInfoArray[0];
                $password = $userInfoArray[1];
            } 
        }

        if(!$username || !$password && $username == "" || $password == "") {
            // Retourne l'erreur
            $responseData = [
                'status' => 'error',
                'message' => 
                    "Les informations fournies sont incomplètes ou inexistantes. ".
                    "Il est également possible qu'elles aient été mal transmises. ".
                    "Veuillez vous référer à la documentation du projet pour plus d'informations."
            ];
            $statusCode = 401;

            return $this->formatResponse($response, $responseData, $statusCode);
        }else {
            // Une fois les informations obtenu on peut essayer d'authentifier l'utilisateur
            try{
                $username = str_replace('%40', '@', $username);
                $user = User::where('username', '=', $username)->first();

                if(!$user){
                    $user = User::where('email', '=', $username)->first();
                }

                if(!$user){
                    $creditUser = new CredentialsDTO($username, $password, null, null);
                }else{
                    $creditUser = new CredentialsDTO($user->email, $user->password, $user->username, $user->refresh_token);
                }
                
                $tokenDTO = $authService->signin($creditUser);

                $responseData = [
                    'status' => 'success',
                    'data' => [
                        'access_token' => $tokenDTO->access_token,
                        'refresh_token' => $tokenDTO->refresh_token
                    ]
                ];
                $statusCode = 200;

                return $this->formatResponse($response, $responseData, $statusCode);
            }catch (Exception $e){
                $responseData = [
                    'status' => 'error',
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