<?php

namespace pizzashop\auth\api\app\auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\PasswordManager;

class AuthProvider {

    private User $authenticatedUser;

    /**
     * Vérifie une authentification sur la base de credentials
     * 
     */
    public function checkCredentials(string $email, string $password){
        $user = User::where('email', $email)->where('password', $password)->first();

        if (!$user) {
            throw new UserNotAuthenticateException("L'utilisateur n'existe pas");
        }

        $this->authenticatedUser = $user;
    }

    /**
     * Vérifie une authentification sur la base d'un refresh token
     * 
     */
    public function checkToken(string $token){
        $user = User::where('refresh_token', $token)->first();

        if (!$user) {
            throw new UserNotAuthenticateException("L'utilisateur n'est pas authentifié");
        }

        $this->authenticatedUser = $user;
    }

    /**
     * Récupére le profil de l'utilisateur authentifié (username, email, refresh token)
     * 
     */
    public function getUserProfile() {
        $user = $this->authenticatedUser;

        return [
            "username" => $user->username,
            "email" => $user->email,
            "refresh_token" => $user->refresh_token
        ];
    }

    /**
     * Enregistre un nouvel utilisateur
     * 
     */
    public function register(string $email, string $password) {
        $newUser = new User([
            'email' => $email,
            'password' => PasswordManager::hashPassword($password),
            'active' => 0,
            'activation_token' => null,
            'activation_token_expiration_date' => null,
            'refresh_token' => null,
            'refresh_token_expiration_date' => null,
            'reset_passwd_token' => null,
            'reset_passwd_token_expiration_date' => null,
            'username' => explode("@", $email)[0]
        ]);

        $newUser->save();
    }

    /**
     * Activer un nouveau compte utilisateur
     * 
     */
    public function activateAccount(string $token) {
        $user = User::where('activation_token', $token)->firstOrFail();

        if (!$user) {
            throw new UserNotAuthenticateException("L'utilisateur n'est pas authentifié");
        } else {
            if ($user->active == User::NOT_ACTIVE) {
                $user->active = User::ACTIVE;
                $user->save();
            }
        }
    }

    /**
     * Réinitialiser le mot de passe de l'utilisateur
     * 
     */
    public function resetPassword(string $token, string $oldPassword, string $newPassword) {
        $user = User::where('reset_passwd_token', $token)->firstOrFail();

        if (!$user) {
            throw new UserNotAuthenticateException("L'utilisateur n'est pas authentifié ou le token n'est pas valide");
        } else {
            if ($user->password == $oldPassword) {
                $user->password = PasswordManager::hashPassword($newPassword);
                $user->save();
            }
        }
    }
}
