<?php

return [
    'displayErrorDetails' => true ,
    'auth.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('AUTH_PORT'),
    'base.path' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('CATALOG_PORT')
] ;