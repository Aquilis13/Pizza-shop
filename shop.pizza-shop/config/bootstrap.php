<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';

$confMariaDB = parse_ini_file('commande.db.ini');
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

// $dbCatalog = new DB();
// $dbCatalog->addConnection( [
//     'driver' => $confPostgre["driver"],
//     'host' => $confPostgre["host"],
//     'database' => $confPostgre["database"],
//     'username' => $confPostgre["username"],
//     'password' => $confPostgre["password"],
//     'charset' => $confPostgre["charset"],
//     'collation' => $confPostgre["collation"],
//     'prefix' => ''
// ], 'catalog');
// $dbCatalog->setAsGlobal();
// $dbCatalog->bootEloquent('catalog');



// return [
//     'displayErrorDetails' => true ,
//     'db.config' => __DIR__ . '/conf.db.ini',
//     'mongo.server' => 'mongodb://mongo.auth',
//     'mongo.db' => ['db'=>'auth','collection'=>'profiles'],
//     'jwt_secret' => 'TGST54Z3BVHDT5Q!GST(3'
//     ] ;

// $builder = new ContainerBuilder();
// $c=$builder->build();
// $app = AppFactory::createFromContainer($c);
// $container = $app->getContainer();

// $builder = new ContainerBuilder();
// $builder->addDefinitions(__DIR__ . '/settings.php');
// $c=$builder->build();
// $app = AppFactory::createFromContainer($c);
