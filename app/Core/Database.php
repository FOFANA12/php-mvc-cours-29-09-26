<?php

namespace App\Core;
use Dotenv\Dotenv;
use PDO;
use PDOException;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


final class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo === null) {

            try {
                self::$pdo = new PDO($_ENV['DSN'], $_ENV['DB_USER'], $_ENV['DB_PWD'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

            } catch (PDOException $e) {
                exit("Erreur de connexion " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
