<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as DB;

require_once __DIR__ . '/../vendor/autoload.php';

$conf = parse_ini_file('auth.db.ini');

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/actions.php');
$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$db = new DB();
$db->addConnection( [
    'driver' => $conf["driver"],
    'host' => $conf["host"],
    'database' => $conf["database"],
    'username' => $conf["username"],
    'password' => $conf["password"],
    'charset' => $conf["charset"],
    'collation' => $conf["collation"],
    'prefix' => ''
], 'auth');
$db->setAsGlobal('auth');
$db->bootEloquent('auth');

return $app;
