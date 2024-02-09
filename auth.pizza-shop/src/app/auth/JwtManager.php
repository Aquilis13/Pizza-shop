<?php

namespace pizzashop\auth\api\app\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\dto\CredentialsDTO;

class JwtManager {

    private $secret;
    private $expiration;

    public function __construct(int $expiration, string $secret)
    {
        $this->expiration = $expiration;
        $this->secret = $secret;
    }

    /**
     * créer des jetons à partir de données qui lui sont transmises et qui sont ajoutées au jeton
     * (payload)
     * 
     */
    public function create(CredentialsDTO $user){
        $payload = [ 
            'iss'=>'pizzashop',
            'iat'=>time(), 
            'exp'=>time()+$this->expiration,
            'upr' => [
                'email' => $user->email,
                'username' => $user->username
            ]
        ];

        return JWT::encode($payload, $this->secret, 'HS512');
    }

    public function validate(string $jwtToken){
        return JWT::decode($jwtToken, new Key($this->secret, 'HS512'));
    }


    /**
     * Modifie la date d'expiration pour les jetons crée
     * 
     */
    public function changeTokenDuration(int $duration) {
        $this->expiration = $duration;
    }
}