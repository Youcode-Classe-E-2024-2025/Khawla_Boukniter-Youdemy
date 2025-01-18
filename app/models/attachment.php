<?php

namespace App\Models;

use App\Core\Model;

class Attachment extends Model
{

    protected $table = 'attachments';
    protected $fillable = ['name', 'path', 'cours_id'];

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, path, cours_id) VALUES (:name, :path, :cours_id)";
        $stmt = $this->db->prepare($sql);

        try {
            $result = $stmt->execute([
                'name' => $data['name'],
                'path' => $data['path'],
                'cours_id' => $data['cours_id']
            ]);
            error_log("Attachment creation result: " . ($result ? "success" : "failed"));
            return $result;
        } catch (\PDOException $e) {
            error_log("Error creating attachment: " . $e->getMessage());
            return false;
        }
    }

    public function getCourseAttachment($courseId)
    {
        $query = "SELECT * FROM {$this->table} WHERE cours_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['course_id' => $courseId]);
        return $stmt->fetchAll();
    }
}
