<?php

namespace pizzashop\shop\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\shop\domain\service\commande\commandeService;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\exceptions\ServiceCommandeNotFoundException;
use pizzashop\shop\helpers\ResponseFormatter;

final class AccederCommandeAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $idCommande = $args['id_commande'];
        
        try {
            $commandeService = $this->container->get('commande.service');
            $commandeDTO = $commandeService->accederCommande($idCommande);

            $responseData = [
                'status' => 'success',
                'data' => json_decode($commandeDTO, true)
            ];
            $statusCode = 200;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);

        } catch (ServiceCommandeNotFoundException $e) {
            $responseData = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            $statusCode = 404;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
            
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Erreur interne du serveur',
                'exception' => $e->getMessage(),
            ];
            $statusCode = 500;

            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        }
    }
}
