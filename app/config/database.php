<?php

namespace App\Config;

use PDO;
use PDOException;
use InvalidArgumentException;
use Exception;

class Database
{
    private const HOST = 'localhost';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const DBNAME = 'youdemy';

    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true
                ];

                self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, $options);
                self::$instance->exec('SET NAMES utf8mb4');
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                throw new Exception("Impossible de se connecter à la base de données.");
            }
        }

        return self::$instance;
    }

    public static function closeConnection()
    {
        self::$instance = null;
    }

    public static function checkUserPermission(int $userId, string $role): bool
    {
        try {
            if ($userId <= 0 || empty($role)) {
                throw new InvalidArgumentException("ID utilisateur ou role invalide.");
            }

            $pdo = self::getConnection();
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.id = ? AND r.name = ?"
            );
            $stmt->execute([$userId, $role]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification du role : " . $e->getMessage());
            throw new Exception("Erreur lors de la vérification du role.");
        }
    }

    public static function getUserRole(int $userId): ?int
    {
        try {
            if ($userId <= 0) {
                throw new InvalidArgumentException("ID utilisateur invalide.");
            }

            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $role = $stmt->fetchColumn();
            return $role !== false ? (int)$role : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du rôle : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération du rôle.");
        }
    }
}
