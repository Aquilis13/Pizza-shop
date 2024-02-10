<?php

return [
    'displayErrorDetails' => true,
    'base.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('GATEWAY_PORT'),
    'commande.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('COMMANDE_PORT'),
    'catalog.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('CATALOG_PORT'),
    'auth.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('AUTH_PORT')
] ;