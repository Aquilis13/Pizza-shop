<?php
/**
 * Ajoute un nouveau message à partir du body transmis et l'affiche dans la console
 * Le body est initialiser à partir de la méthode accederCommande du Service commande. 
 * L'objet renvoyer par accederCommande est au format JSON
 * 
 */
$host = '192.168.42.80';
require_once __DIR__ . '/../vendor/autoload.php';

use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use Illuminate\Database\Capsule\Manager as DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


// CONNECTION A LA BASE DE DONNEES
$confMariaDB = parse_ini_file('../config/commande.db.ini');

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


// RESTE DU CODE
$idCommande = '112e7ee1-3e8d-37d6-89cf-be3318ad6368';
$commandeService = new ServiceCommande();
$commandeDtoInJSON = $commandeService->accederCommande($idCommande);

$connection = new AMQPStreamConnection(
    $host, 
    5672, 
    'admin', 
    '@dm1#!'
);

$msg_body = $commandeDtoInJSON;

$channel = $connection->channel();
$channel->basic_publish(new AMQPMessage($msg_body), 'pizzashop', 'nouvelle');

print "[x] commande publiée : \n";
echo $msg_body;

$channel->close();
$connection->close();