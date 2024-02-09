<?php

namespace pizzashop\shop\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\shop\domain\service\commande\commandeService;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use \Datetime;

final class CreerCommandeAction {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        try {
            $commandeService = $this->container->get('commande.service');
            $commandeData = json_decode($request->getBody()->getContents(), true);
            $currentDate = new DateTime();

            if ($commandeData === null && json_last_error() !== JSON_ERROR_NONE) {
                $responsecommandeData = [
                    'status' => 'error',
                    'message' => 'Bad Request - Corps de la requête invalide.',
                    'detail' => json_last_error_msg()
                ];
                $statusCode = 400;
            } else {
                $mailClient = $request->getAttribute('user_email') ?? null;

                $dateCommande = $commandeData['date_commande'] ?? $currentDate->format('Y-m-d H:i:s');
                $typeLivraison = $commandeData['type_livraison'] ?? null;
                $montantTotal = $commandeData['montant_total'] ?? 0;
                $etat = $commandeData['etat'] ?? null;
                $delai = $commandeData['delai'] ?? null;
                $items = $commandeData['items'] ?? null;

                if($montantTotal == 0){
                    foreach($items as $item){
                        $montantTotal += $item['tarif'] ?? 0;
                    }
                }

                $newCommande = new CommandeDTO(
                    null,
                    $dateCommande,
                    $typeLivraison,
                    $mailClient,
                    $montantTotal,
                    $etat,
                    $delai,
                    $items
                );
                
                // Commande::valideDTO($newCommande);

                $commandeDTO = $commandeService->creerCommande($newCommande);

                $responsecommandeData = [
                    'status' => 'success',
                    'message' => 'Les données ont était ajoutés à la base de données avec succès.'
                ];
                $statusCode = 201;
            }

            return $this->formatResponse($response, $responseData, $statusCode);
        } catch (ServiceException $e) {
            $responsecommandeData = [
                'status' => 'error',
                'message' => 'Bad Request - Données manquantes ou invalides.',
            ];
            $statusCode = 400;

            return $this->formatResponse($response, $responseData, $statusCode);
        } catch (Exception $e) {
            $responsecommandeData = [
                'status' => 'error',
                'message' => 'Erreur interne du serveur',
                'exception' => $e,
            ];
            $statusCode = 500;

            return $this->formatResponse($response, $responseData, $statusCode);
        }
    }

    private function formatResponse(Response $response, array $responseData, int $statusCode) {
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}