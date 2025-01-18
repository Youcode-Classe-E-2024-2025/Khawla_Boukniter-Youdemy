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

        return $stmt->execute($data);
    }

    public function getCourseAttachment($courseId)
    {
        $query = "SELECT * FROM {$this->table} WHERE cours_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['course_id' => $courseId]);
        return $stmt->fetchAll();
    }
}
