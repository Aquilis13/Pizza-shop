<?php

namespace pizzashop\auth\api\domain\services;

class PasswordManager {

    private static $pepper = 'c1isvFdxMDdmjOlvxpecFw';

    /**
     * Hache un mot de passe donner
     *
     * @param string $password Mot de passe à hacher.
     * @return string Le mot de passe haché.
     */
    public static function hashPassword($password) {
        $pwd_peppered = hash_hmac("sha256", $password, self::$pepper);
        $salt = password_hash($pwd_peppered, PASSWORD_ARGON2ID);
        $salt = base64_encode($salt);
        $salt = str_replace('+', '.', $salt);
        $hash = crypt($password, '$2y$10$'.$salt.'$');

        return $hash;
    }

    /**
     * Vérifie si un mot de passe correspond à un hachage donné.
     *
     * @param string $password Mot de passe à vérifier.
     * @param string $hashedPassword Hachage stocké à comparer.
     * @return bool True si le mot de passe est correct, sinon False.
     */
    public static function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
