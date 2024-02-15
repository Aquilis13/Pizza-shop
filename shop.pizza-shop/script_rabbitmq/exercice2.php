<?php
/**
 * Supprime le dernier message transmis et l'affiche dans la console
 * 
 */

require_once __DIR__ . '/../vendor/autoload.php';
$host = '192.168.42.80';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    $host, 
    5672, 
    'user', 
    'password'
);

$message_queue = 'nouvelles_commandes';
        
$channel = $connection->channel();
$msg = $channel->basic_get($message_queue);

if ($msg) {
    $content = json_decode($msg->body, true);
    
    print "[x] message reçu : \n" ;
    print $msg->body;

    $channel->basic_ack($msg->getDeliveryTag());
    print "\n";
} else {
    print "[x] pas de message reçu\n";
    exit(0);
}

$channel->close();
$connection->close();
