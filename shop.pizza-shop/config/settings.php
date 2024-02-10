<?php

return [
    'displayErrorDetails' => true ,
    'auth.service' => 'http://'.$_SERVER['SERVER_NAME'].':'.getenv('AUTH_PORT')
] ;