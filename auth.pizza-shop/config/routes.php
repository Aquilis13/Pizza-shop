<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(\Slim\App $app):void {

    $app->post('/api/users/signup', pizzashop\auth\api\app\actions\SignupAction::class)
        ->setName('signup');
    
    $app->post('/api/users/signin', pizzashop\auth\api\app\actions\SigninAction::class)
        ->setName('signin');

    $app->get('/api/users/validate', pizzashop\auth\api\app\actions\ValidateTokenAction::class)
        ->setName('validate');

    $app->post('/api/users/refresh', pizzashop\auth\api\app\actions\RefreshTokenAction::class)
        ->setName('refresh');
};