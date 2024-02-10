<?php

namespace pizzashop\shop\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\logs\CommandeLogger;
use pizzashop\shop\helpers\ResponseFormatter;

final class ValiderCommandeAction {

    public function __invoke(Request $request, Response $response, array $args): Response {
        $id = $args['id_commande'];
        
        try {
            $serviceCommande = new ServiceCommande();
            $commandeDTO = $serviceCommande->accederCommande($id);
            $objetCommandeDTO = json_decode($commandeDTO, true);

            if($objetCommandeDTO["delai"] == Commande::CREE){
                $commandeDTO = $serviceCommande->validerCommande($id);
                $responseData = [
                    'status' => 'success',
                    'data' => json_decode($commandeDTO, true)
                ];
                $statusCode = 200;
                new CommandeLogger("La commande $id est passer à l'état VALIDE", "info");

            }else{
                $responseData = [
                    'status' => 'error',
                    'message' => "La requête est déjà validée, ou la transition demandée n'est pas correcte",
                    'data' => json_decode($commandeDTO, true),
                ];
                $statusCode = 400;
            }

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        } catch (ServiceCommandeNotFoundException $e) {
            $responseData = [
                'status' => 'error',
                'message' => $e,
            ];
            $statusCode = 404;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        }catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Erreur interne au serveur',
                'exception' => $e,
            ];
            $statusCode = 500;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        }
    }

    private function formatResponse(Response $response, array $responseData, int $statusCode) {
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}