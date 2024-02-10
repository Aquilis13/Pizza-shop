<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/actions.php');
$builder->addDefinitions(__DIR__ . '/settings.php');

$c=$builder->build();
$app = AppFactory::createFromContainer($c);

// middleware pour parser le body
$app->addBodyParsingMiddleware();
// le routage est réalisé par un middleware !
$app->addRoutingMiddleware();

$app->add(new pizzashop\gateway\app\middlewares\CorsMiddleware());
// $app->add(new pizzashop\gateway\app\middlewares\CatalogMiddleware());

// et la gestion des erreurs aussi !!
$app->addErrorMiddleware(true, false, false) ;

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

return $app;
