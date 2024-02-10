<?php

namespace pizzashop\catalog\app\actions;

use gift\app\services\BoxService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use pizzashop\catalog\domain\exceptions\ProductNotFoundException;
use pizzashop\catalog\domain\exceptions\CategorieNotFoundException;
use pizzashop\catalog\helpers\ResponseFormatter;

final class AccederProduitByCategorie {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        try {
            $catalogService = $this->container->get('catalog.service');
            $id_categorie = $args['id_categorie'];
            
            $produitsDTO = $catalogService->accederProduitsInCategorie($id_categorie);
        
            $responseData = [
                'status' => 'success',
                'data' => json_decode($produitsDTO, true)
            ];
            $statusCode = 200;
        
            return ResponseFormatter::formatResponse($response, $responseData, $statusCode);
        
        } catch (ProductNotFoundException | CategorieNotFoundException $e) {
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