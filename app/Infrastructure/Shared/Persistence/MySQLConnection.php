<?php

namespace Infrastructure\Shared\Persistence;

use PDO;
use PDOException;
use Dotenv\Dotenv;

final class MySQLConnection
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Cargar variables de entorno desde la raíz del proyecto
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../'); 
            $dotenv->safeLoad();

            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $db   = $_ENV['DB_DATABASE'] ?? 'ecommerce_temuv2';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException('Could not connect to the database: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
