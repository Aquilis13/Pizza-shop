<?php
/**
 * On oriente les utilisateurs en fonction des Actions
 * Dans les actions on récupères les données des APIs et on renvoie la réponse à travers l'API Gateway
 * 
 */
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(\Slim\App $app):void {

    // Routes de l'API Commande :
    $app->post('/commandes[/]', pizzashop\gateway\app\actions\CommandeAction::class)
        ->setName('creer_commande');
    
    $app->get('/commandes/{id_commande}[/]', pizzashop\gateway\app\actions\CommandeAction::class)
        ->setName('commande');

    $app->patch('/commandes/{id_commande}[/]', pizzashop\gateway\app\actions\CommandeAction::class)
        ->setName('valider_commande');

    
    // Routes de l'API Catalogue :
    $app->get('/produits[/]', pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produits');

    $app->get('/produits/{id_produit}[/]', pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produit');

    $app->get('/categories/{id_categorie}/produits[/]', pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produit_categorie');

    $app->get('/produits/?mot-cle={mot_cle}[/]', pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produits_motcle');


    // Routes de l'API d'authentification :
    $app->post('/api/users/signup', pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('signup');
    
    $app->post('/api/users/signin', pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('signin');

    $app->get('/api/users/validate', pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('validate');

    $app->post('/api/users/refresh', pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('refresh');
};