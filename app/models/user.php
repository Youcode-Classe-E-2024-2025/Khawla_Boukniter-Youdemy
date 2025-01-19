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
    public function updateStatus($id, $status)
    {
        $query = "UPDATE users SET is_active = :status WHERE id = :id AND role_id != 3";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }

    public function delete($id)
    {
        $query = "SELECT role_id FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if ($user['role_id'] === 3) {
            return false;
        }

        $query = "DELETE FROM inscriptions WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        $query = "DELETE FROM users WHERE id = :id AND role_id != 3";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getTotalUsers()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function getTotalTeachers()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role_id = 2");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPendingTeachers()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role_id = 2 AND is_validated = 0");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTopTeachers($limit = 3)
    {
        $query = "SELECT 
                u.*, 
                COUNT(DISTINCT c.id) as course_count,
                COUNT(DISTINCT i.user_id) as student_count
              FROM users u
              LEFT JOIN cours c ON u.id = c.enseignant_id
              LEFT JOIN inscriptions i ON c.id = i.cours_id
              WHERE u.role_id = 2
              GROUP BY u.id
              ORDER BY student_count DESC
              LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingTeachersDetails()
    {
        $query = "SELECT id, nom, prenom, email, created_at 
              FROM users 
              WHERE role_id = 2 AND is_validated = 0 
              ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function validateTeacher($id)
    {
        $query = "UPDATE users SET is_validated = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function rejectTeacher($id)
    {
        $query = "UPDATE users SET role_id = 1, is_validated = 1 WHERE id = :id AND role_id = 2";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getAllUsersWithDetails()
    {
        $query = "SELECT u.*, r.nom as role_name 
              FROM users u 
              JOIN roles r ON u.role_id = r.id 
              ORDER BY u.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
