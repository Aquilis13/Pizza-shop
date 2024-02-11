<?php
/**
 * Consomme les message envoyer et les affiches dans la console
 * 
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    '192.168.42.80', 
    5672, 
    'user', 
    'password'
);

$message_queue = 'nouvelles_commandes';
$channel = $connection->channel();

$callback = function(AMQPMessage $msg) {
    $msg_body = json_decode($msg->body, true); 

    print "[x] message reçu : \n";
    print $msg->body;
    print "\n";

    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};

$msg = $channel->basic_consume($message_queue, '', false, false, false, false, $callback );

try {
    $channel->consume();
} catch (Exception $e) { 
    print $e->getMessage();
}

$channel->close(); 
$connection->close();
