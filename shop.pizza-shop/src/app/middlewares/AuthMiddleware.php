<?php

namespace pizzashop\shop\app\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Client;
use pizzashop\shop\domain\exceptions\TokenNotFoundException;
use pizzashop\shop\domain\exceptions\InvalidTokenException;

class AuthMiddleware {

    private $authApiBaseUrl;

    public function __construct(string $authApiBaseUrl) {
        $this->authApiBaseUrl = $authApiBaseUrl;
    }

    public function __invoke(Request $request, RequestHandlerInterface $next): Response {
        $header = $request->getHeader('Authorization');

        // Vérifie la présence du token
        $notTokenMessage = "Aucun token n'a était trouver dans le header Authorization en mode Bearer. Vous pouvez en générer à partir de la route d'authentification suivante : ".$this->authApiBaseUrl."/api/users/signup";
        if (empty($header)) {
            throw new TokenNotFoundException($notTokenMessage);
        }

        $authorizationHeader = $header[0];
        if(empty($authorizationHeader)){
            throw new TokenNotFoundException($notTokenMessage);
        }

        // Vérifie la validité du Token
        try {
            $client = new Client([
                'base_uri' => $this->authApiBaseUrl,
                'timeout' => 5.0,
            ]);

            $response = $client->request('GET', '/api/users/validate', [
                'headers'=> ['Authorization' => $header]
            ]);
        } catch (RequestException $e){
            throw new RequestException();
        } catch (Exception $e) {
            throw new InvalidTokenException('Le token fournis est invalide.');
        }

        // Ajoute les information de l'utilisateur à la requête
        $jsonString = $response->getBody()->getContents();
        $data = json_decode($jsonString, true); 
        
        $request = $request->withAttribute('user_email', $data['data']['user']['email']);
        $request = $request->withAttribute('username', $data['data']['user']['username']);

        // Si on a pas eu d'erreur on continue la requête
        return $next->handle($request);
    }
}
