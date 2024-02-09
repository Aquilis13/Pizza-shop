<?php

namespace pizzashop\auth\api\domain\services;

use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;
use pizzashop\auth\api\app\auth\AuthProvider;
use pizzashop\auth\api\app\auth\JwtManager;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\dto\CredentialsDTO;
use pizzashop\auth\api\domain\dto\TokenDTO;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\exceptions\UserAlreadyExistException;
use pizzashop\auth\api\domain\exceptions\SaveUserException;
use pizzashop\auth\api\domain\services\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private AuthProvider $authProvider;
    private JwtManager $jwtManager;

    function __construct(AuthProvider $authProvider, JwtManager $jwtManager)
    {
        $this->authProvider = $authProvider;
        $this->jwtManager = $jwtManager;
    }

    /** 
     * Reçoit des credentials et retourne un couple (access_token, refresh_token)
     * 
    */
    public function signin(CredentialsDTO $credentialsDTO) : TokenDTO {
        $user = $this->authProvider->checkCredentials($credentialsDTO->email, $credentialsDTO->password);

        $accessToken = $this->jwtManager->create($credentialsDTO);
        
        $this->jwtManager->changeTokenDuration(2592000);
        $refresh_token = $this->jwtManager->create($credentialsDTO);
        $this->jwtManager->changeTokenDuration(getenv('TOKEN_EXPIRATION'));

        return new TokenDTO($accessToken, $refresh_token);
    }

    /**
     * Reçoit un access_token et vérifie sa validité, puis retourne le profil de l'utilisateur
     * authentifié,
     * 
     */
    public function validate(string $token) : UserDTO {
        try{
            $payload = $this->jwtManager->validate($token);

            return new UserDTO(
                $payload->upr->email,
                null, // password
                null, // active
                null, // activation_token
                null, // activation_token_expiration_date
                null, // refresh_token
                null, // refresh_token_expiration_date
                null, // reset_passwd_token
                null, // reset_passwd_token_expiration_date
                $payload->upr->username
            );

        // Cas ou quelque chose c'est mal passer avec le jeton jwt
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("La signature ou l'encodage du jeton jwt est incorect");

        } catch (BeforeValidException $e) {
            throw new BeforeValidException('La datetion du jeton jwt fournis est incohérente');

        } catch (ExpiredException $e) {
            throw new ExpiredException('Votre Token est expirer vous pouvez en regénérer un nouveau en vous reconnectant.');
        
        } catch (SignatureInvalidException $e) {
            throw new SignatureInvalidException('La signature du jeton est inccorect');
        
        } catch (DomainException $e) {
            // L'algorithme fourni n'est pas pris en charge 
            // OU la clé fournie est invalide.
            // OU une erreur inconnue est survenue dans OpenSSL ou Libsodium 
            // OU Libsodium est requis mais n'est pas disponible.
            throw new DomainException($e->message);
        
        } catch (UnexpectedValueException $e) {
            // Le jeton est mal formé 
            // OU le jeton manque d'un algorithme ou utilise un algorithme non pris en charge.
            // OU l'algorithme du jeton ne correspond pas à la clé fournie 
            // OU l'ID de la clé dans le tableau de clés est vide ou invalide.
            throw new UnexpectedValueException($e->message);
        } 
    }

    /**
     * Reçoit un refresh token et retourne un nouveau couple (access_token, refresh_token)
     * 
     */
    public function refresh(TokenDTO $tokenDTO) : TokenDTO {
        $userDTO = $this->validate($tokenDTO->refresh_token);
        $user = User::where('email', '=', $userDTO->email)->first();

        $credentialsDTO = new CredentialsDTO($user->email, $user->password, null, null);

        return $this->signin($credentialsDTO);
    }

    /**
     * Reçoit des credentials et enregistre un nouvel utilisateur, retourne son profil,
     * 
     */
    public function signup(CredentialsDTO $credentialsDTO) : UserDTO {
        $user = User::where('email', '=', $credentialsDTO->email)->first();

        if($user){
            throw new UserAlreadyExistException("L'addresse mail fournis est déjà associer à un compte");
        }

        $this->authProvider->register(
            $credentialsDTO->email, 
            $credentialsDTO->password
        );

        $user = User::where('email', '=', $credentialsDTO->email)->first();
        if(!$user){
            throw new SaveUserException("Un erreur est survenu dans la création du compte");
        }

        return new UserDTO(
            $user['email'], 
            $user['password'], 
            $user['active'],
            $user['activation_token'],
            $user['activation_token_expiration_date'], 
            $user['refresh_token'],
            $user['refresh_token_expiration_date'],
            $user['reset_passwd_token'],
            $user['reset_passwd_token_expiration_date'],
            $user['username']
        );
    }

    /**
     * Reçoit un token d'activation et active le nouveau compte correspondant.
     * 
     */
    public function activate(TokenDTO $tokenDTO) : void {

    }
}