<?php
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\RabbitmqService;

return [
    'commande.service' => function (\Psr\Container\ContainerInterface $c) {
        return new ServiceCommande();
    },

    'rabbitmq.connexion' => function (\Psr\Container\ContainerInterface $c) {
        return new \PhpAmqpLib\Connection\AMQPStreamConnection(
            $_SERVER['SERVER_NAME'], 
            5672, 
            'user', 
            'password'
        );
    },

    // 'rabbitmq.connexion' => function (\Psr\Container\ContainerInterface $c) {
    //     return new \PhpAmqpLib\Connection\AMQPStreamConnection(
    //         $_SERVER['SERVER_NAME'], 
    //         getenv('RABBIT_PORT'), 
    //         getenv('RABBIT_USER'), 
    //         getenv('RABBIT_PASSWORD')
    //     );
    // },
];