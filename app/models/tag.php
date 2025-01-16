<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Tag {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int {
        $sql = "INSERT INTO tags (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $data['name']]);
        return $this->db->lastInsertId();
    }

    public function findByName(string $name): ?array {
        $sql = "SELECT * FROM tags WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
