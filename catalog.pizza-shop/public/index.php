<?php
declare(strict_types=1);

use pizzashop\shop\domain\service\commande\ServiceCommande;


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

(require_once __DIR__ . '/../config/routes.php')($appli);

// $confMariaDB = parse_ini_file(__DIR__ . '/../config/commande.db.ini');
// $confPostgre = parse_ini_file(__DIR__ . '/../config/catalog.db.ini');

// // $bdd = (require_once __DIR__ . '/../config/ConnexionBdd.php')($confMariaDB);

// $config = $confMariaDB;

// $db = new DB();
// $db->addConnection( [
//     'driver' => $config["driver"],
//     'host' => $config["host"],
//     'database' => $config["database"],
//     'username' => $config["username"],
//     'password' => $config["password"],
//     'charset' => $config["charset"],
//     'collation' => $config["collation"],
//     'prefix' => ''
// ], 'conn');
// $db->setAsGlobal('conn');
// $db->bootEloquent('conn');

$appli->run();