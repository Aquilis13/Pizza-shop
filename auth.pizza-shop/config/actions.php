<?php

use pizzashop\auth\api\domain\services\AuthService;
use pizzashop\auth\api\app\auth\AuthProvider;
use pizzashop\auth\api\app\auth\JwtManager;

return [
    'auth.service' => function (\Psr\Container\ContainerInterface $c) {
        return new AuthService(
            new AuthProvider(), 
            new JwtManager(getenv('TOKEN_EXPIRATION'), getenv('JWT_SECRET'))
        );
    },
];