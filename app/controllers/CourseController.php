<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Attachment;

class CourseController extends Controller {

    private $attachmentModel;
    private $courseModel;

    public function __construct() {
        $this->courseModel = new Course();
        $this->attachmentModel = new Attachment();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $filters = [];
        $courses = $this->courseModel->getPublishedCourses($filters, $page, $limit);

        $total = $this->courseModel->getTotalCourses(); 
        $pages = ceil($total / $limit);

        $categories = $this->courseModel->getCategories();

        $this->render('index', ['courses' => $courses, 'categories' => $categories, 'pages' => $pages, 'page' => $page]);
    }

    public function show($id) {
        $course = $this->courseModel->getWithDetails($id);
        if (!$course) {
            $this->redirect('courses');
        }
        $categorie = $this->courseModel->getCategorie();
        $tags = $this->courseModel->getCourseTags($id);
    
        $this->render('courses/show', ['course' => $course, 'categorie' => $categorie, 'tags' => $tags]);
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 2) {
            $_SESSION['error'] = "Vous devez être un enseignant pour créer un cours.";
            $this->redirect('login');
        }

        $categories = $this->courseModel->getCategories();
        $tags = $this->courseModel->getTags();

        $this->render('users/teacher/create_course', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 2) {
            $_SESSION['error'] = "Vous devez être un enseignant pour créer un cours.";
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['error'] = "Token CSRF invalide.";
                $this->redirect('teacher/courses/create');
            }

            $courseData = [
                'titre' => htmlspecialchars(filter_input(INPUT_POST, 'titre', FILTER_DEFAULT) ?? ''),
                'description' => htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_DEFAULT) ?? ''),
                'categorie_id' => filter_input(INPUT_POST, 'categorie_id', FILTER_VALIDATE_INT),
                'enseignant_id' => $_SESSION['user_id'],
                // 'tags' => $_POST['tags'] ?? [],
            ];

            error_log(print_r($courseData, true));

            if (is_null($courseData['categorie_id'])) {
                $_SESSION['error'] = "La catégorie est requise.";
                $this->redirect('teacher/courses/create');
            }

            if (is_null($courseData['titre'])) {
                $_SESSION['error'] = "Le titre est requis.";
                $this->redirect('teacher/courses/create');
            }

            if (is_null($courseData['description'])) {
                $_SESSION['error'] = "La description est requise.";
                $this->redirect('teacher/courses/create');
            }

            if (is_null($courseData['tags'])) {
                $_SESSION['error'] = "Les tags sont requis.";
                $this->redirect('teacher/courses/create');
            }

            error_log(print_r($courseData, true)); // Log the course data for debugging

            $courseId = $this->courseModel->create($courseData);

            if (isset($_FILES['attachments']) && $_FILES['attachments']['error'][0] === UPLOAD_ERR_OK) {
                foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
                    $fileName = $_FILES['attachments']['name'][$key];
                    $filePath = BASE_PATH . '/public/uploads/' . basename($fileName);
    
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $attachmentData = [
                            'name' => $fileName,
                            'path' => $filePath,
                            'cours_id' => $courseId,
                        ];
                        $this->attachmentModel->create($attachmentData);
                    }
                }
            }

            // $this->courseModel->create($data);
            $_SESSION['success'] = "Cours créé avec succès.";
            $this->redirect('teacher/courses');
        }
    }

    public function enroll($courseId) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 1) {
            $_SESSION['error'] = "Vous devez être un étudiant pour vous inscrire à ce cours.";
            $this->redirect('login');
        }

        $this->courseModel->enroll($_SESSION['user_id'], $courseId);
        $_SESSION['success'] = "Vous êtes maintenant inscrit au cours.";
        $this->redirect("courses/$courseId");
    }
}