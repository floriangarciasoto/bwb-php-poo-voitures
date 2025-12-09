<?php
class Conf {
    private static $databases = array(
        // Serveur
        'hostname' => 'localhost',

        // Nom de la bdd
        'database' => 'parc_automobile',

        // Utilisateur ('root' par défaut)
        'login' => 'root',

        // Mot de passe
        'password' => ''
    );

    public static function getLogin(): string {
        return self::$databases['login'];
    }

    public static function getHostname(): string {
        return self::$databases['hostname'];
    }

    public static function getDatabase(): string {
        return self::$databases['database'];
    }

    public static function getPassword(): string {
        return self::$databases['password'];
    }
}
