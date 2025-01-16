<?php

namespace App\Core;

use App\Config\Database;

abstract class Model {
    protected $db;
    protected $table;
    protected $fillable = [];


    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll($conditions = [], $order = null, $limit = null, $offset = null) {
        $query = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $query .= " WHERE " . $this->buildWhereClause($conditions);
        }
        
        if ($order) {
            $query .= " ORDER BY " . $order;
        }
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
            if ($offset) {
                $query .= " OFFSET " . (int)$offset;
            }
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($conditions);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        error_log(print_r($data, true));
        $data = $this->filterFillable($data);
        $fields = array_keys($data);
        $values = ':' . implode(', :', $fields);
        $fields = implode(', ', $fields);
        
        $query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($data)) {
            error_log("SQL Error: " . implode(", ", $stmt->errorInfo())); // Log any SQL errors
        }
        
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        } else {
            error_log(print_r($stmt->errorInfo(), true));
        }
        return false;
    }

    public function update($id, $data) {
        $data = $this->filterFillable($data);
        $fields = array_map(function($field) {
            return "{$field} = :{$field}";
        }, array_keys($data));
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count($conditions = []) {
        $query = "SELECT COUNT(*) FROM {$this->table}";
        
        if (!empty($conditions)) {
            $query .= " WHERE " . $this->buildWhereClause($conditions);
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($conditions);
        return $stmt->fetchColumn();
    }

    protected function filterFillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function buildWhereClause($conditions) {
        return implode(' AND ', array_map(function($field) {
            return "{$field} = :{$field}";
        }, array_keys($conditions)));
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollback() {
        return $this->db->rollBack();
    }
}