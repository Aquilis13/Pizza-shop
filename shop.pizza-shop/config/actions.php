<?php
use pizzashop\shop\domain\service\commande\ServiceCommande;

return [
    'commande.service' => function (\Psr\Container\ContainerInterface $c) {
        return new ServiceCommande();
    },
];