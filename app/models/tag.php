<?php

namespace App\Models;

use App\Core\Model;

class Tag extends Model
{

    public function create(array $data)
    {
        $sql = "INSERT INTO tags (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $data['name']]);
        return $this->db->lastInsertId();
    }

    public function findByName(string $name): ?array
    {
        $sql = "SELECT * FROM tags WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
