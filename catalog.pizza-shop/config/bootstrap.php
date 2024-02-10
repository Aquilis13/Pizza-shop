<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';

$confPostgre = parse_ini_file('catalog.db.ini');

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

// $app->add(new pizzashop\catalog\app\middlewares\CorsMiddleware());
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$dbCatalog = new DB();
$dbCatalog->addConnection( [
    'driver' => $confPostgre["driver"],
    'host' => $confPostgre["host"],
    'database' => $confPostgre["database"],
    'username' => $confPostgre["username"],
    'password' => $confPostgre["password"],
    'charset' => $confPostgre["charset"],
    'collation' => $confPostgre["collation"],
    'prefix' => ''
], 'catalog');
$dbCatalog->setAsGlobal('catalog');
$dbCatalog->bootEloquent('catalog');

return $app;
