<?php

namespace App\Models;

use App\Core\Model;

class Course extends Model
{
    protected $table = 'cours';
    protected $fillable = [
        'titre',
        'description',
        'categorie_id',
        'enseignant_id',
    ];

    public function create($data)
    {
        error_log("Creating course with data: " . print_r($data, true));

        $sql = "INSERT INTO cours (titre, description, categorie_id, enseignant_id) VALUES (:titre, :description, :categorie_id, :enseignant_id)";

        try {
            $stmt = $this->db->prepare($sql);
            $resultat = $stmt->execute([
                'titre' => $data['titre'],
                'description' => $data['description'],
                'categorie_id' => $data['categorie_id'],
                'enseignant_id' => $data['enseignant_id'],
            ]);

            if ($resultat) {
                return $this->db->lastInsertId();
            }

            error_log("Database error: " . print_r($stmt->errorInfo(), true));

            return false;
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        // if (isset($data['title']) && !isset($data['slug'])) {
        //     $data['slug'] = $this->generateSlug($data['title']);
        // }
        return parent::update($id, $data);
    }

    public function getPublishedCourses($filters = [], $page = 1, $limit = 12)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT 
                    c.*,
                    u.prenom as teacher_prenom,
                    u.nom as teacher_nom,
                    cat.name as category_name,
                    COUNT(DISTINCT e.user_id) as student_count,
                    GROUP_CONCAT(t.name) as tag_names
                  FROM {$this->table} c
                  JOIN users u ON c.enseignant_id = u.id
                  JOIN categories cat ON c.categorie_id = cat.id
                  LEFT JOIN inscriptions e ON c.id = e.cours_id
                  LEFT JOIN cours_tags ct ON c.id = ct.cours_id
                  LEFT JOIN tags t ON t.id = ct.tag_id";

        if (isset($filters['enseignant_id'])) {
            $query .= " WHERE c.enseignant_id = :enseignant_id";
        }

        $query .= " GROUP BY c.id
                    ORDER BY c.created_at DESC
                    LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        if (isset($filters['enseignant_id'])) {
            $stmt->bindValue(':enseignant_id', $filters['enseignant_id'], \PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($keyword)
    {
        $query = "SELECT 
                    c.*,
                    u.prenom as teacher_prenom,
                    u.nom as teacher_nom,
                    cat.name as category_name
                 FROM {$this->table} c
                 JOIN users u ON c.enseignant_id = u.id
                 JOIN categories cat ON c.categorie_id = cat.id
                 WHERE c.status = 'published'
                 AND (
                     c.titre LIKE :keyword
                     OR c.description LIKE :keyword
                     OR cat.name LIKE :keyword
                 )
                 ORDER BY c.created_at DESC
                 LIMIT 10";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    public function getWithDetails($id)
    {
        $query = "SELECT 
                    c.*,
                    u.prenom as teacher_prenom,
                    u.nom as teacher_nom,
                    -- u.avatar_url as teacher_avatar,
                    cat.name as category_name,
                    COUNT(DISTINCT e.user_id) as student_count,
                    -- AVG(r.rating) as average_rating,
                    -- COUNT(DISTINCT r.id) as rating_count
                 FROM {$this->table} c
                 JOIN users u ON c.enseignant_id = u.id
                 JOIN categories cat ON c.categorie_id = cat.id
                 LEFT JOIN inscriptions e ON c.id = e.cours_id
                --  LEFT JOIN course_ratings r ON c.id = r.course_id
                 WHERE c.id = :id
                 GROUP BY c.id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getTags()
    {
        $stmt = $this->db->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCourseTags($courseId)
    {
        $query = "SELECT t.* 
                 FROM tags t
                 JOIN cours_tags ct ON t.id = ct.tag_id
                 WHERE ct.cours_id = :cours_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['cours_id' => $courseId]);
        return $stmt->fetchAll();
    }

    public function addTag($courseId, $tagId)
    {
        $query = "INSERT IGNORE INTO cours_tags (cours_id, tag_id) 
                 VALUES (:cours_id, :tag_id)";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'cours_id' => $courseId,
            'tag_id' => $tagId
        ]);
    }

    public function removeTags($courseId)
    {
        $query = "DELETE FROM cours_tags WHERE cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['cours_id' => $courseId]);
    }

    public function getTopCourses($limit = 5)
    {
        $query = "SELECT 
                    c.*,
                    u.prenom as teacher_prenom,
                    u.nom as teacher_nom,
                    COUNT(DISTINCT e.user_id) as student_count,
                    -- AVG(r.rating) as average_rating
                 FROM {$this->table} c
                 JOIN users u ON c.enseignant_id = u.id
                 LEFT JOIN inscriptions e ON c.id = e.cours_id
                --  LEFT JOIN course_ratings r ON c.id = r.course_id
                --  WHERE c.status = 'published'
                 GROUP BY c.id
                 ORDER BY student_count DESC
                 LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCourses()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE enseignant_id = :teacherId");
        $stmt->execute(['teacherId' => $_SESSION['user_id']]);
        return $stmt->fetchColumn();
    }

    public function getTotalStudentsByTeacher($teacherId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT e.user_id) 
            FROM inscriptions e 
            JOIN {$this->table} c ON e.cours_id = c.id 
            WHERE c.enseignant_id = :teacherId
        ");
        $stmt->execute(['teacherId' => $teacherId]);
        return $stmt->fetchColumn();
    }

    public function getTotalCoursesByTeacher($teacherId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE enseignant_id = :teacherId");
        $stmt->execute(['teacherId' => $teacherId]);
        return $stmt->fetchColumn();
    }

    public function getCategories()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
