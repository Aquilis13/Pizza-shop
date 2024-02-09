<?php

namespace pizzashop\auth\api\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\auth\api\domain\dto\CredentialsDTO;

final class SignupAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $authService = $this->container->get('auth.service');

        // Récupére le nom d'utilisateur et le mot de passe dans les variables serveur
        $email = $_SERVER['PHP_AUTH_USER'] ?? null;
        $password = $_SERVER['PHP_AUTH_PW'] ?? null;

        // Si ça ne fonctionne pas on essaye de récupérer les informations en passant par l'uri
        if(!$email || !$password && $email == "" || $password == ""){
            $uri = $request->getUri();

            $userInfo = $uri->getUserInfo();
            $userInfoArray = explode(':', $userInfo);

            if (count($userInfoArray) >= 2) {
                $email = $userInfoArray[0];
                $password = $userInfoArray[1];
            } 
        }

        if(!$email || !$password && $email == "" || $password == "") {
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
            // Une fois les informations obtenu on peut essayer d'enregistrer le nouvel utilisateur dans la base de données
            try{
                $email = str_replace('%40', '@', $email);

                $creditUser = new CredentialsDTO($email, $password, null, null);
                $authService->signup($creditUser);

                $responseData = [
                    'status' => 'success',
                    'message' => "Votre compte a était enregistrer avec success. Vous pouvez vous connecter à partir du lien d'authentification.",
                    'signin' => 'http://'.$_SERVER['HTTP_HOST'].'/api/users/signin'
                ];
                $statusCode = 200;

                return $this->formatResponse($response, $responseData, $statusCode);
            }catch (SaveUserException $e){
                $responseData = [
                    'status' => 'error',
                    'message' => $e->message
                ];
                $statusCode = 401;

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
