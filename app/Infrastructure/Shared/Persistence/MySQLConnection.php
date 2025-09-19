<?php

namespace Infrastructure\Shared\Persistence;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Log; // 👈 importar Log de Laravel

class MySQLConnection
{
    private PDO $connection;

    public function __construct()
    {
        // Solo cargar .env si aún no está cargado
        if (!isset($_ENV['DB_DATABASE'])) {
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();
        }

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $db   = $_ENV['DB_DATABASE'] ?? 'ecommerce_temu'; // 👈 default corregido
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            Log::info("✅ Conectado a DB", [
                'db'   => $db,
                'host' => $host,
                'user' => $user,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error conexión DB: " . $e->getMessage());
            throw new \RuntimeException('Could not connect to the database: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
