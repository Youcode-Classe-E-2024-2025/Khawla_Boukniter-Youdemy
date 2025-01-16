<?php

namespace App\Models;

use App\Core\Model;

class Attachment extends Model {

    protected $table = 'attachments';
    protected $fillable = ['name', 'path', 'cours_id'];

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, path, cours_id) VALUES (:name, :path, :cours_id)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':path' => $data['path'],
            ':cours_id' => $data['cours_id'],
        ]);
    }
}
