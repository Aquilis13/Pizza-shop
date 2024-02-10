<?php
use pizzashop\catalog\domain\service\ServiceCatalog;

return [
    'catalog.service' => function (\Psr\Container\ContainerInterface $c) {
        return new ServiceCatalog();
    },
];