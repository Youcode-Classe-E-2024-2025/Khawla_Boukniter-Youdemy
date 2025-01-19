<?php

namespace App\Models;

use App\Core\Model;

class Tag extends Model
{
    protected $table = 'tags';

    public function getAll()
    {
        $query = "SELECT t.*, COUNT(ct.cours_id) as usage_count 
                  FROM tags t
                  LEFT JOIN cours_tags ct ON t.id = ct.tag_id
                  GROUP BY t.id
                  ORDER BY t.name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $query = "INSERT INTO tags (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['name' => $data['name']]);
    }

    public function delete($id)
    {
        // First delete associations in cours_tags
        $query1 = "DELETE FROM cours_tags WHERE tag_id = :id";
        $stmt1 = $this->db->prepare($query1);
        $stmt1->execute(['id' => $id]);

        // Then delete the tag
        $query2 = "DELETE FROM tags WHERE id = :id";
        $stmt2 = $this->db->prepare($query2);
        return $stmt2->execute(['id' => $id]);
    }
}
