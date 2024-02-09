<?php

namespace pizzashop\auth\api\domain\services;

use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\dto\CredentialsDTO;
use pizzashop\auth\api\domain\dto\TokenDTO;

interface AuthServiceInterface
{
    public function signin(CredentialsDTO $credentialsDTO) : TokenDTO;
    public function validate(string $token) : UserDTO;
    public function refresh(TokenDTO $tokenDTO) : TokenDTO;
    public function signup(CredentialsDTO $credentialsDTO) : UserDTO;
    public function activate(TokenDTO $tokenDTO) : void;
}