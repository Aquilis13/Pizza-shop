<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';

$confMariaDB = parse_ini_file('commande.db.ini');

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/actions.php');
$builder->addDefinitions(__DIR__ . '/settings.php');

$c=$builder->build();
$app = AppFactory::createFromContainer($c);

// middleware pour parser le body
$app->addBodyParsingMiddleware();
// le routage est réalisé par un middleware !
$app->addRoutingMiddleware();
// et la gestion des erreurs aussi !!
$app->addErrorMiddleware(true, false, false) ;

$app->add(new pizzashop\shop\app\middlewares\CorsMiddleware());
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$dbCommande = new DB();
$dbCommande->addConnection( [
    'driver' => $confMariaDB["driver"],
    'host' => $confMariaDB["host"],
    'database' => $confMariaDB["database"],
    'username' => $confMariaDB["username"],
    'password' => $confMariaDB["password"],
    'charset' => $confMariaDB["charset"],
    'collation' => $confMariaDB["collation"],
    'prefix' => ''
], 'commande');
$dbCommande->setAsGlobal('commande');
$dbCommande->bootEloquent('commande');

return $app;
