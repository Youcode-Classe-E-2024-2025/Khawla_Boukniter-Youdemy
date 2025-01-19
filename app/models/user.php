<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;
use Exception;

class User
{
    private PDO $db;

    public int $role_id;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO users (nom, prenom, email, password, role_id, is_validated) VALUES (:nom, :prenom, :email, :password, :role_id, :is_validated)";
        $stmt = $this->db->prepare($sql);

        $is_validated = ($data['role_id'] == 2) ? 0 : 1;

        try {
            $stmt->execute([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $data['role_id'],
                'is_validated' => $is_validated
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion de l'utilisateur : " . $e->getMessage());
            throw new Exception("Erreur lors de la création de l'utilisateur.");
        }

        return $this->db->lastInsertId();
    }

    public function setRole(int $userId, string $role): bool
    {
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'role' => $role,
            'id' => $userId
        ]);
    }

    public function updateRole($userId, $role)
    {
        $query = "UPDATE users SET role = :role WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        error_log("Searching for user with ID: " . $id);
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            error_log("User found: " . print_r($user, true));
        } else {
            error_log("No user found for ID: " . $id);
        }
        return $user ?: null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
