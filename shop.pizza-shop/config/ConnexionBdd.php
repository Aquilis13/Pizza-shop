<?php

use Illuminate\Database\Capsule\Manager as DB;

return function(array $config) {
    // var_dump($config);

    $db = new DB();
    $db->addConnection( [
        'driver' => $config["driver"],
        'host' => $config["host"],
        'database' => $config["database"],
        'username' => $config["username"],
        'password' => $config["password"],
        'charset' => $config["charset"],
        'collation' => $config["collation"],
        'prefix' => ''
    ], 'conn');
    $db->setAsGlobal('conn');
    $db->bootEloquent('conn');
};