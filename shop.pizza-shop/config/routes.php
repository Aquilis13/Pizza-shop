<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\dto\commande\CommandeDTO;

return function(\Slim\App $app):void {
    $container = $app->getContainer();

    $app->post('/commandes[/]', \pizzashop\shop\app\actions\CreerCommandeAction::class)
        ->setName('creer_commande')
        ->add(new pizzashop\shop\app\middlewares\AuthMiddleware($container->get('auth.service')));
    
    $app->get('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\AccederCommandeAction::class)
        ->setName('commande');

    $app->patch('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        ->setName('valider_commande');
};