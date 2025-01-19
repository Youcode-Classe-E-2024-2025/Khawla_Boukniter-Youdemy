<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function getAllWithCount()
    {
        $query = "SELECT 
                    c.*,
                    COUNT(DISTINCT co.id) as course_count
                  FROM categories c
                  LEFT JOIN cours co ON c.id = co.categorie_id
                  GROUP BY c.id
                  ORDER BY c.name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $query = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['name' => $data['name']]);
    }

    public function delete($id)
    {
        $query = "SELECT COUNT(*) FROM cours WHERE categorie_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return 'has_courses';
        }

        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
