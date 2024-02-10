<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(\Slim\App $app):void {

    $app->get('/produits[/]', \pizzashop\catalog\app\actions\AccederProduitsAction::class)
        ->setName('produits');

    $app->get('/produits/{id_produit}[/]', \pizzashop\catalog\app\actions\AccederProduitByIdAction::class)
        ->setName('produit');

    $app->get('/categories/{id_categorie}/produits[/]', \pizzashop\catalog\app\actions\AccederProduitByCategorie::class)
        ->setName('produit_categorie');
};