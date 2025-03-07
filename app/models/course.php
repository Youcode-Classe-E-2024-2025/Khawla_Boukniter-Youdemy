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
        'content_type',
    ];

    public function create($data)
    {
        error_log("Creating course with data: " . print_r($data, true));

        $sql = "INSERT INTO cours (titre, description, categorie_id, enseignant_id, content_type) VALUES (:titre, :description, :categorie_id, :enseignant_id, :content_type)";

        try {
            $stmt = $this->db->prepare($sql);
            $resultat = $stmt->execute([
                'titre' => $data['titre'],
                'description' => $data['description'],
                'categorie_id' => $data['categorie_id'],
                'enseignant_id' => $data['enseignant_id'],
                'content_type' => $data['content_type'],
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

    public function getTeacherCoursesWithDetails($teacherId)
    {
        $query = "SELECT 
                    c.*,
                    cat.name as category_name,
                    COUNT(DISTINCT i.user_id) as student_count
                  FROM cours c
                  LEFT JOIN categories cat ON c.categorie_id = cat.id
                  LEFT JOIN inscriptions i ON c.id = i.cours_id
                  WHERE c.enseignant_id = :teacher_id
                  GROUP BY c.id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['teacher_id' => $teacherId]);
        $courses = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Add tags for each course
        foreach ($courses as &$course) {
            $course['tags'] = $this->getCourseTags($course['id']);
        }

        return $courses;
    }

    public function getWithDetails($id)
    {
        $query = "SELECT 
                c.*,
                u.prenom as teacher_prenom,
                u.nom as teacher_nom,
                cat.name as category_name,
                a.path as content_url,
                a.name as content_name,
                c.content_type,
                COUNT(DISTINCT e.user_id) as student_count
             FROM {$this->table} c
             JOIN users u ON c.enseignant_id = u.id
             LEFT JOIN categories cat ON c.categorie_id = cat.id
             LEFT JOIN attachments a ON c.id = a.cours_id
             LEFT JOIN inscriptions e ON c.id = e.cours_id
             WHERE c.id = :id
             GROUP BY c.id, u.prenom, u.nom, c.content_type";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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
                cat.name as category_name,
                COUNT(DISTINCT i.user_id) as student_count
             FROM cours c
             JOIN users u ON c.enseignant_id = u.id
             JOIN categories cat ON c.categorie_id = cat.id
             LEFT JOIN inscriptions i ON c.id = i.cours_id
             GROUP BY c.id, u.prenom, u.nom
             ORDER BY student_count DESC
             LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCourses()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
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

    public function getCourseCategory($courseId)
    {
        $query = "SELECT c.* 
                 FROM cours c
                 JOIN categories cat ON c.categorie_id = cat.id
                 WHERE c.id = :cours_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['cours_id' => $courseId]);
        return $stmt->fetchAll();
    }

    public function getEnrollments($courseId)
    {
        $query = "SELECT
        u.id, u.nom, u.prenom, u.email, i.inscription_date
        FROM inscriptions i
        JOIN users u ON i.user_id = u.id
        WHERE i.cours_id = :courseId
        ORDER BY i.inscription_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['courseId' => $courseId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTeacherCourses($teacherId, $limit = null, $offset = null)
    {
        $query = "SELECT 
                c.*, 
                cat.name as category_name,
                COUNT(DISTINCT e.user_id) as student_count
              FROM cours c
              LEFT JOIN categories cat ON c.categorie_id = cat.id
              LEFT JOIN inscriptions e ON c.id = e.cours_id
              WHERE c.enseignant_id = :teacher_id
              GROUP BY c.id, cat.name";

        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':teacher_id', $teacherId, \PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getLatestTeacherCourses($teacherId, $limit = 3)
    {
        $query = "SELECT 
                    c.*, 
                    cat.name as category_name,
                    COUNT(DISTINCT e.user_id) as student_count
                  FROM cours c
                  LEFT JOIN categories cat ON c.categorie_id = cat.id
                  LEFT JOIN inscriptions e ON c.id = e.cours_id
                  WHERE c.enseignant_id = :teacher_id
                  GROUP BY c.id, cat.name
                  ORDER BY c.created_at DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':teacher_id', $teacherId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function enroll($userId, $courseId)
    {
        $query = "INSERT INTO inscriptions (user_id, cours_id, inscription_date) 
                  VALUES (:user_id, :cours_id, NOW())";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'cours_id' => $courseId
        ]);
    }

    public function getEnrolledCoursesCount($userId)
    {
        $query = "SELECT COUNT(*) FROM inscriptions WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function getStudentRecentCourses($userId, $limit = 3)
    {
        $query = "SELECT 
                    c.*,
                    u.prenom as teacher_prenom,
                    u.nom as teacher_nom,
                    cat.name as category_name
                  FROM cours c
                  JOIN inscriptions i ON c.id = i.cours_id
                  JOIN users u ON c.enseignant_id = u.id
                  JOIN categories cat ON c.categorie_id = cat.id
                  WHERE i.user_id = :user_id
                  ORDER BY i.inscription_date DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStudentCourses($userId, $limit = 12, $offset = 0)
    {
        $query = "SELECT 
                c.*,
                u.prenom as teacher_prenom,
                u.nom as teacher_nom,
                cat.name as category_name
              FROM cours c
              JOIN inscriptions i ON c.id = i.cours_id
              JOIN users u ON c.enseignant_id = u.id
              JOIN categories cat ON c.categorie_id = cat.id
              WHERE i.user_id = :user_id
              ORDER BY i.inscription_date DESC
              LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isEnrolled($userId, $courseId)
    {
        $query = "SELECT COUNT(*) FROM inscriptions 
                  WHERE user_id = :user_id AND cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId,
            'cours_id' => $courseId
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function unenroll($userId, $courseId)
    {
        $query = "DELETE FROM inscriptions 
              WHERE user_id = :user_id 
              AND cours_id = :cours_id";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'cours_id' => $courseId
        ]);
    }

    public function search($keyword, $page = 1, $limit = 12)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT 
                c.*,
                u.prenom as teacher_prenom,
                u.nom as teacher_nom,
                cat.name as category_name,
                COUNT(DISTINCT e.user_id) as student_count
             FROM cours c
             JOIN users u ON c.enseignant_id = u.id
             JOIN categories cat ON c.categorie_id = cat.id
             LEFT JOIN inscriptions e ON c.id = e.cours_id
             WHERE c.titre LIKE :keyword1 
             OR c.description LIKE :keyword2
             OR cat.name LIKE :keyword3
             GROUP BY c.id, u.prenom, u.nom, cat.name
             ORDER BY student_count DESC
             LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindValue(':keyword1', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':keyword2', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':keyword3', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalSearchResults($keyword)
    {
        $query = "SELECT COUNT(DISTINCT c.id)
             FROM cours c
             JOIN categories cat ON c.categorie_id = cat.id
             WHERE c.titre LIKE :keyword1 
             OR c.description LIKE :keyword2
             OR cat.name LIKE :keyword3";

        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindValue(':keyword1', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':keyword2', $searchTerm, \PDO::PARAM_STR);
        $stmt->bindValue(':keyword3', $searchTerm, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAllCoursesWithDetails($limit = null, $offset = null)
    {
        $query = "SELECT 
                c.*,
                u.nom as teacher_nom,
                u.prenom as teacher_prenom,
                cat.name as category_name,
                COUNT(DISTINCT i.user_id) as student_count
              FROM cours c
              JOIN users u ON c.enseignant_id = u.id
              JOIN categories cat ON c.categorie_id = cat.id
              LEFT JOIN inscriptions i ON c.id = i.cours_id
              GROUP BY c.id
              ORDER BY c.created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($query);

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
