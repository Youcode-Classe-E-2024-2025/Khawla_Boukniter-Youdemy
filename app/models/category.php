<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Category {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): int {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            'name' => $data['name'],
        ]);

        return $this->db->lastInsertId();
    }

    public function update(int $id, string $name, int $projectId): bool {
        $sql = "UPDATE categories SET name = :name WHERE id = :id AND project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'project_id' => $projectId
        ]);
    }

    public function delete(int $id, int $projectId): bool {
        $sql = "DELETE FROM categories WHERE id = :id AND project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'project_id' => $projectId
        ]);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ?: null;
    }

    public function getByProject(int $projectId): array {
        $sql = "SELECT c.*, COUNT(t.id) as task_count 
                FROM categories c 
                LEFT JOIN tasks t ON c.id = t.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByCategory(int $categoryId): array {
        $sql = "SELECT t.*, u.name as assigned_to_name 
                FROM tasks t 
                LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE t.category_id = :category_id 
                ORDER BY t.priority DESC, t.deadline ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
